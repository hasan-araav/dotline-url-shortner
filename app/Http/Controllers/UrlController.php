<?php

namespace App\Http\Controllers;

use App\Jobs\RecordClickJob;
use App\Models\Url;
use App\Rules\SafeUrl;
use App\Services\ClickAnalyticsService;
use App\Services\UrlShortenerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;


class UrlController extends Controller
{
    public function __construct(protected UrlShortenerService $urlShortenerService) {}

    public function dashboard() {
        return view('dashboard', ['urls' => Url::all()]);
    }

    public function index() {
        return view('homepage')->with(['urls' => Url::all()]);
    }

    public function shorten(Request $request) {

        $validator = $request->validate([
            'url' => ['required', 'url', 'max:2048', new SafeUrl],
        ]);

        $url = RateLimiter::attempt(
            'create-url:' . $request->ip(),
            $maxAttempts = 5, // Allow 5 attempts...
            function() use ($request) {
                return $this->urlShortenerService->shorten($request->url);
            },
            $decaySeconds = 60 // ...per minute
        );

        if (! $url) {
            return redirect()->back()->withErrors(['error' => 'Too many requests. Please try again later.']);
        }

        return redirect()->back()->with([
            'original_url' => $url->original_url,
            'short_url' => url('t/'.$url->short_code),
            'short_code' => $url->short_code,
            'expires_at' => $url->expires_at,
        ]);

    }

    public function redirect($shortCode) {
        $url = $this->urlShortenerService->findByShortCode($shortCode);

        if (!$url) {
            abort(404);
        }

        $this->urlShortenerService->recordClick($url);

        return redirect()->away($url->original_url);
    }

    public function getAnalytics($shortCode) {

        $url = $this->urlShortenerService->findByShortCode($shortCode);

        if (!$url) {
            abort(404);
        }

        $analytics = ClickAnalyticsService::getAnalytics($url->id);

        return view('shortCode.analytics', [
            'url' => $url,
            'analytics' => $analytics
        ]);
    }
}
