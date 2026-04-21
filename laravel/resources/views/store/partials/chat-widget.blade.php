@php
    $aiReady = config('ai_chat.enabled', true) && app(\App\Services\AiChatService::class)->isConfigured();
    $captchaDriver = config('ai_chat.captcha.driver', 'turnstile');
    $captchaSite = $captchaDriver === 'recaptcha'
        ? config('ai_chat.captcha.recaptcha_site_key')
        : config('ai_chat.captcha.site_key');
    $captchaNeeded = app(\App\Services\CaptchaVerifier::class)->captchaConfigured()
        && (auth()->guest() || config('ai_chat.captcha.require_for_authenticated', false));
@endphp

<div id="hm-chat-root" class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
    <button type="button" id="hm-chat-toggle" class="btn btn-primary rounded-circle shadow d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;" aria-label="Open chat" title="Chat">
        <i class="bi bi-chat-dots fs-4" aria-hidden="true"></i>
    </button>
    <div id="hm-chat-panel" class="card shadow-lg border-0 d-none" style="width: min(100vw - 2rem, 380px); max-height: 85vh;">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <span class="fw-semibold small">{{ config('app.name') }} — Chat</span>
            <button type="button" class="btn-close btn-sm" id="hm-chat-close" aria-label="Close"></button>
        </div>
        <div class="card-body p-2 d-flex flex-column" style="min-height: 280px; max-height: 55vh;">
            <div id="hm-chat-messages" class="flex-grow-1 overflow-auto small mb-2 p-2 bg-light rounded" style="min-height: 160px;"></div>
            @unless($aiReady)
                <p class="text-muted small mb-2">Assistant is not configured (set API keys in <code>.env</code>).</p>
            @endunless
            <div id="hm-chat-captcha" class="mb-2"></div>
            <div class="input-group input-group-sm">
                <input type="text" id="hm-chat-input" class="form-control" placeholder="Type a message…" maxlength="4000" @unless($aiReady) disabled @endunless>
                <button type="button" id="hm-chat-send" class="btn btn-primary" @unless($aiReady) disabled @endunless>Send</button>
            </div>
            <div class="d-flex flex-wrap gap-1 mt-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="hm-helpdesk-btn">Talk to help desk</button>
            </div>
        </div>
        <div id="hm-helpdesk-panel" class="card-body border-top d-none">
            <p class="small text-muted mb-2">Describe your issue. We will follow up by email.</p>
            @guest
                <div class="mb-2">
                    <input type="text" id="hm-guest-name" class="form-control form-control-sm mb-1" placeholder="Your name">
                    <input type="email" id="hm-guest-email" class="form-control form-control-sm" placeholder="Email">
                </div>
            @endguest
            <textarea id="hm-helpdesk-body" class="form-control form-control-sm mb-2" rows="3" placeholder="How can we help?"></textarea>
            <div id="hm-helpdesk-captcha" class="mb-2"></div>
            <button type="button" class="btn btn-success btn-sm" id="hm-helpdesk-submit">Submit request</button>
            <button type="button" class="btn btn-link btn-sm" id="hm-helpdesk-cancel">Back to chat</button>
        </div>
    </div>
</div>

@push('scripts')
@if($captchaSite && $captchaNeeded)
    @if($captchaDriver === 'recaptcha')
        <script src="https://www.google.com/recaptcha/api.js?render={{ $captchaSite }}"></script>
    @else
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
@endif
<script>
(function () {
    const chatUrl = @json(route('chat.message'));
    const supportUrl = @json(route('support-requests.store'));
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const aiReady = @json($aiReady);
    const captchaNeeded = @json($captchaNeeded);
    const captchaDriver = @json($captchaDriver);
    const captchaSite = @json($captchaSite);
    const isGuest = @json(auth()->guest());

    const panel = document.getElementById('hm-chat-panel');
    const toggle = document.getElementById('hm-chat-toggle');
    const closeBtn = document.getElementById('hm-chat-close');
    const messagesEl = document.getElementById('hm-chat-messages');
    const input = document.getElementById('hm-chat-input');
    const sendBtn = document.getElementById('hm-chat-send');
    const history = [];

    function append(role, text) {
        const div = document.createElement('div');
        div.className = role === 'user' ? 'text-end mb-1' : 'text-start mb-1';
        div.innerHTML = '<span class="d-inline-block px-2 py-1 rounded ' + (role === 'user' ? 'bg-primary text-white' : 'bg-white border') + '">' + escapeHtml(text) + '</span>';
        messagesEl.appendChild(div);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function escapeHtml(s) {
        const d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    async function getCaptchaToken(containerId) {
        if (!captchaNeeded || !captchaSite) return '';
        if (captchaDriver === 'recaptcha') {
            return new Promise(function (resolve) {
                if (typeof grecaptcha === 'undefined') { resolve(''); return; }
                grecaptcha.ready(function () {
                    grecaptcha.execute(captchaSite, { action: 'chat' }).then(resolve).catch(function () { resolve(''); });
                });
            });
        }
        return window._hmTurnstileToken || '';
    }

    function renderTurnstileIfNeeded(containerId) {
        if (!captchaNeeded || !captchaSite || captchaDriver !== 'turnstile') return;
        const el = document.getElementById(containerId);
        if (!el) return;
        if (typeof turnstile === 'undefined') {
            setTimeout(function () { renderTurnstileIfNeeded(containerId); }, 100);
            return;
        }
        el.innerHTML = '';
        window._hmTurnstileToken = null;
        turnstile.render(el, {
            sitekey: captchaSite,
            callback: function (token) { window._hmTurnstileToken = token; },
            'expired-callback': function () { window._hmTurnstileToken = null; },
        });
    }

    toggle.addEventListener('click', function () {
        panel.classList.toggle('d-none');
        if (!panel.classList.contains('d-none') && captchaNeeded && captchaDriver === 'turnstile') {
            renderTurnstileIfNeeded('hm-chat-captcha');
        }
    });
    closeBtn.addEventListener('click', function () { panel.classList.add('d-none'); });

    async function postJson(url, payload) {
        const data = Object.assign({}, payload);
        if (captchaNeeded) {
            if (captchaDriver === 'recaptcha') {
                data.captcha_token = await getCaptchaToken();
            } else {
                data.captcha_token = window._hmTurnstileToken || '';
            }
        }
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
        };
        const res = await fetch(url, { method: 'POST', headers: headers, body: JSON.stringify(data), credentials: 'same-origin' });
        const json = await res.json().catch(function () { return {}; });
        if (!res.ok) {
            const msg = json.message || ('Error ' + res.status);
            throw new Error(msg);
        }
        return json;
    }

    async function sendChat() {
        if (!aiReady) return;
        const text = (input.value || '').trim();
        if (!text) return;
        if (captchaNeeded && captchaDriver === 'turnstile' && !window._hmTurnstileToken) {
            append('assistant', 'Please complete the CAPTCHA above.');
            return;
        }
        append('user', text);
        input.value = '';
        sendBtn.disabled = true;
        try {
            const payload = { message: text, messages: history };
            const data = await postJson(chatUrl, payload);
            const reply = data.reply || '';
            append('assistant', reply);
            history.push({ role: 'user', content: text });
            history.push({ role: 'assistant', content: reply });
            if (history.length > 40) history.splice(0, history.length - 40);
            if (captchaNeeded && captchaDriver === 'turnstile') {
                renderTurnstileIfNeeded('hm-chat-captcha');
            }
        } catch (e) {
            append('assistant', e.message || 'Something went wrong.');
        } finally {
            sendBtn.disabled = false;
        }
    }

    sendBtn.addEventListener('click', sendChat);
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendChat(); }
    });

    const helpdeskBtn = document.getElementById('hm-helpdesk-btn');
    const helpdeskPanel = document.getElementById('hm-helpdesk-panel');
    const helpdeskSubmit = document.getElementById('hm-helpdesk-submit');
    const helpdeskCancel = document.getElementById('hm-helpdesk-cancel');
    const helpdeskBody = document.getElementById('hm-helpdesk-body');

    helpdeskBtn.addEventListener('click', function () {
        helpdeskPanel.classList.remove('d-none');
        if (captchaNeeded && captchaDriver === 'turnstile') {
            renderTurnstileIfNeeded('hm-helpdesk-captcha');
        }
    });
    helpdeskCancel.addEventListener('click', function () {
        helpdeskPanel.classList.add('d-none');
        if (captchaNeeded && captchaDriver === 'turnstile') {
            renderTurnstileIfNeeded('hm-chat-captcha');
        }
    });

    helpdeskSubmit.addEventListener('click', async function () {
        const body = (helpdeskBody.value || '').trim();
        if (!body) return;
        if (isGuest) {
            const gn = (document.getElementById('hm-guest-name')?.value || '').trim();
            const ge = (document.getElementById('hm-guest-email')?.value || '').trim();
            if (!gn || !ge) { alert('Please enter name and email.'); return; }
        }
        if (captchaNeeded && captchaDriver === 'turnstile' && !window._hmTurnstileToken) {
            alert('Please complete the CAPTCHA.');
            return;
        }
        try {
            const payload = { body: body };
            if (isGuest) {
                payload.guest_name = (document.getElementById('hm-guest-name').value || '').trim();
                payload.guest_email = (document.getElementById('hm-guest-email').value || '').trim();
            }
            const data = await postJson(supportUrl, payload);
            append('assistant', data.message || 'Request sent.');
            helpdeskPanel.classList.add('d-none');
            helpdeskBody.value = '';
            if (captchaNeeded && captchaDriver === 'turnstile') {
                renderTurnstileIfNeeded('hm-helpdesk-captcha');
            }
        } catch (e) {
            alert(e.message || 'Failed to submit.');
        }
    });
})();
</script>
@endpush

@if($captchaSite && $captchaNeeded && $captchaDriver === 'turnstile')
    {{-- turnstile loads via script; render on panel open --}}
@endif
