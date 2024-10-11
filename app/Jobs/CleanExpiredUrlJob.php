<?php

namespace App\Jobs;

use App\Models\Url;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CleanExpiredUrlJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $expiredUrls = Url::where('expires_at', '<', now())->get();

        foreach($expiredUrls as $url) {
            // Remove From Cache
            Cache::forget("url:{$url->short_code}");

            // Optionally you can softdeletes. For now we will just delete it.
            $url->delete();
        }
    }
}
