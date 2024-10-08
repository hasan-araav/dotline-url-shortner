<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Services\UrlShortenerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{
    public function __construct(protected UrlShortenerService $urlShortenerService) {}

    public function shorten(Request $request) {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url|max:2048',
        ]);

        $url = $this->urlShortenerService->shorten($request->url);

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
}
