<?php
namespace App\Services;

use App\Jobs\RecordClickJob;
use App\Models\Url;
use App\Models\Click;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
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

        $cacheKey = "url:{$shortCode}";

        return Cache::remember($cacheKey, 3600, function () use ($shortCode, $cacheKey) {
            $url = Url::where('short_code', $shortCode)->first();

            if (!$url) {
                return null;
            }

            if ($url->expires_at && $url->expires_at->isPast()) {
                Cache::forget($cacheKey);
                return null;
            }

            return $url;
        });
    }

    public function recordClick(Url $url) {

        $clicksKey = "clicks:{$url->short_code}";
        $clickDatakey = "click_data:{$url->short_code}";

        $clicks = Redis::incr($clicksKey);

        // Store Click Data Into Redis
        $clickData = [
            'ip' => request()->ip(),
            'userAgent' => request()->userAgent(),
            'referer' => request()->header('referer')
        ];
        Redis::rpush($clickDatakey, json_encode($clickData));

        // Dispatch RecordClickJob every 10 clicks
        if ($clicks % 10 == 0) {
            RecordClickJob::dispatch($url);

            // Reset Redis Click Counter
            Redis::set($clicksKey, 0);
        }
    }
}