<?php

namespace App\Jobs;

use App\Models\Click;
use App\Models\Url;
use App\Services\ClickAnalyticsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use Jenssegers\Agent\Agent;
use Torann\GeoIP\Facades\GeoIP;

class RecordClickJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;

    /**
     * Create a new job instance.
     */
    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $clickDataKey = "click_data:{$this->url->short_code}";

        // Get all stored click data
        $clickDataList = Redis::lrange($clickDataKey, 0, -1);

        // Process all click data
        foreach ($clickDataList as $clickDataJson) {
            $clickData = json_decode($clickDataJson, true);

            $agent = new Agent();
            $agent->setUserAgent($clickData['userAgent']);

            $geoip = GeoIP::getLocation($clickData['ip']);

            Click::create([
                'url_id' => $this->url->id,
                'ip_address' => $clickData['ip'],
                'user_agent' => $clickData['userAgent'],
                'referer' => $clickData['referer'],
                'country' => $geoip->iso_code,
                'city' => $geoip->city,
                'device_type' => $this->getDeviceType($agent),
                'browser' => $agent->browser(),
                'os' => $agent->platform(),
            ]);
        }

        // Clear processes click data
        Redis::del($clickDataKey);
        // Clear Analytics Cache Data
        ClickAnalyticsService::invalidateCache($this->url->id);
    }

    private function getDeviceType($agent) {

        if ($agent->isDesktop()) return 'desktop';
        if ($agent->isMobile()) return 'mobile';
        if ($agent->isTablet()) return 'tablet';
        return 'other';
    }
}
