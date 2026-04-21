<?php

namespace App\Http\Controllers;

use App\Services\ProductRecommendationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly ProductRecommendationService $recommendations
    ) {}

    public function __invoke(): View
    {
        $recommended = collect();
        if (Auth::check()) {
            $recommended = $this->recommendations->recommendForUser(Auth::user(), (int) config('halenmiaga.recommendation_limit', 8));
        }

        return view('store.home', [
            'recommended' => $recommended,
        ]);
    }
}
