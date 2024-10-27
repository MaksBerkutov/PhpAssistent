<?php

namespace App\Jobs;
use App\Models\Device;
use App\Services\DevicesReqest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AvalibeDevice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

        Device::chunk(100, function ($devices) {
            foreach ($devices as $device) {
                $isAvailable = DevicesReqest::isAvalibe($device->url);
                if ($device->available !== $isAvailable) {
                    $device->update(['available' => $isAvailable]);
                }
            }
        });

    }
}
