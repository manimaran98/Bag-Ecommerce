<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HelpDeskController extends Controller
{
    public function index(Request $request): View
    {
        $status = (string) $request->query('status', 'open');
        if (! in_array($status, ['open', 'resolved', 'all'], true)) {
            $status = 'open';
        }
        $q = SupportRequest::query()->with('user')->orderByDesc('id');
        if ($status === 'open') {
            $q->where('status', 'open');
        } elseif ($status === 'resolved') {
            $q->where('status', 'resolved');
        }
        $requests = $q->paginate(25)->withQueryString();

        return view('admin.help-desk.index', [
            'requests' => $requests,
            'filter' => $status,
        ]);
    }

    public function show(SupportRequest $supportRequest): View
    {
        $supportRequest->load('user');

        return view('admin.help-desk.show', ['ticket' => $supportRequest]);
    }

    public function update(Request $request, SupportRequest $supportRequest): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:open,resolved'],
        ]);

        $supportRequest->status = $data['status'];
        $supportRequest->save();

        return redirect()->route('admin.help-desk.show', $supportRequest)->with('status', 'Ticket updated.');
    }
}
