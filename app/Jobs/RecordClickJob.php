<?php

namespace App\Jobs;

use App\Models\Click;
use App\Models\Url;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecordClickJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $ipAddress;
    protected $userAgent;

    /**
     * Create a new job instance.
     */
    public function __construct(Url $url, $ipAddress, $userAgent)
    {
        $this->url = $url;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->url->increment('clicks');

        Click::create([
            'url_id' => $this->url->id,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent
        ]);
    }
}
