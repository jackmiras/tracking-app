<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DeviceDetector\DeviceDetector;
use App\Helpers\Constants;

class TrackingService
{
    public function __construct(public ?string $publicIpAddress = null)
    {
        $this->publicIpAddress = $this->getPublicIpAddress();
    }

    public function getPublicIpAddress(): string
    {
        try {
            $response = Http::get(Constants::APIFY_URL);

            if ($response->successful()) {
                return $response->json()['ip'];
            }
        } catch (\Exception $e) {
            Log::error('Exception occurred: ' . $e->getMessage(), ['exception' => $e]);
        }

        return 'Public ip not found';
    }

    public function getLocation(string $ip): string
    {
        try {
            $response = Http::get(Constants::IPAPI_URL . "/$ip");

            if ($response->successful()) {
                $location = $response->json();

                if ($location['status'] === 'success') {
                    return $location['city'] . ', '
                        . $location['regionName'] . ', '
                        . $location['country'];
                }
            }
        } catch (\Exception $e) {
            Log::error('Exception occurred: ' . $e->getMessage(), ['exception' => $e]);
        }

        return 'Location not available';
    }


    public function getOS(string $userAgent)
    {
        $deviceDetector = new DeviceDetector($userAgent);
        $deviceDetector->parse();
        return $deviceDetector->getOs('name');
    }

    public function getDevice(string $userAgent)
    {
        $deviceDetector = new DeviceDetector($userAgent);
        $deviceDetector->parse();
        return $deviceDetector->getDeviceName() ?: 'Unknown Device';
    }
}
