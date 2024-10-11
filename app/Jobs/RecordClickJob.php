<?php

namespace App\Jobs;

use App\Models\Click;
use App\Models\Url;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Jenssegers\Agent\Agent;
use Torann\GeoIP\Facades\GeoIP;

class RecordClickJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $request;

    /**
     * Create a new job instance.
     */
    public function __construct(Url $url, $request)
    {
        $this->url = $url;
        $this->request = $request;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->url->increment('clicks');

        $agent = new Agent();
        $agent->setUserAgent($this->request['userAgent']);

        $geoip = GeoIP::getLocation($this->request['ip']);

        Click::create([
            'url_id' => $this->url->id,
            'ip_address' => $this->request['ip'],
            'user_agent' => $this->request['userAgent'],
            'referer' => $this->request['referer'],
            'country' => $geoip->iso_code,
            'city' => $geoip->city,
            'device_type' => $this->getDeviceType($agent),
            'browser' => $agent->browser(),
            'os' => $agent->platform(),
        ]);
    }

    private function getDeviceType($agent) {

        if ($agent->isDesktop()) return 'desktop';
        if ($agent->isMobile()) return 'mobile';
        if ($agent->isTablet()) return 'tablet';
        return 'other';
    }
}
