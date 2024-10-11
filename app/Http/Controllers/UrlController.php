<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Rules\SafeUrl;
use App\Services\UrlShortenerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;


class UrlController extends Controller
{
    public function __construct(protected UrlShortenerService $urlShortenerService) {}

    public function index() {
        return view('dashboard')->with(['urls' => Url::all()]);
    }

    public function shorten(Request $request) {

        $url = RateLimiter::attempt(
            'create-url:' . $request->ip(),
            $maxAttempts = 5, // Allow 5 attempts...
            function() use ($request) {

                $validator = Validator::make($request->all(), [
                    'url' => ['required', 'url', 'max:2048', new SafeUrl],
                ]);

                return $this->urlShortenerService->shorten($request->url);
            },
            $decaySeconds = 60 // ...per minute
        );

        if (! $url) {
            return redirect()->back()->withErrors(['error' => 'Too many requests. Please try again later.']);
        }

        return redirect()->back()->with([
            'original_url' => $url->original_url,
            'short_url' => url($url->short_code),
            'expires_at' => $url->expires_at,
        ]);

    }

    public function redirect($shortCode) {
        $url = $this->urlShortenerService->findByShortCode($shortCode);

        if (!$url) {
            abort(404);
        }

        $this->urlShortenerService->recordClick($url, request());
        return redirect()->away($url->original_url);
    }

    public function getAnalytics($shortCode) {
        $url = $this->urlShortenerService->findByShortCode($shortCode);

        if (!$url) {
            abort(404);
        }

        return view('shortCode.analytics', ['url' => $url]);



    }
}
