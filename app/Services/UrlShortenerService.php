<?php
namespace App\Services;

use App\Models\Url;
use App\Models\Click;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UrlShortenerService {

    public function shorten($longUrl) {

        $url = Url::where('original_url', $longUrl)->first();

        if ($url) {
            return $url;
        }

        DB::beginTransaction();

        try {
            $shortCode = $this->generateUniqueShortCode();

            $url = Url::create([
                'original_url' => $longUrl,
                'short_code' => $shortCode,
                'expires_at' => now()->addDays(30),
            ]);

            DB::commit();
            return $url;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function generateUniqueShortCode() {

        do {
            $shortCode = Str::random(6);
        } while (Url::where('short_code', $shortCode)->exists());

        return $shortCode;
    }

    public function findByShortCode($shortCode) {

        return Url::where('short_code', $shortCode)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();
    }

    public function recordClick(Url $url, Request $request) {

        DB::transaction(function () use ($url, $request) {
            $url->increment('clicks');

            Click::create([
                'url_id' => $url->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        });
    }
}