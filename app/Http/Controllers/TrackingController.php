<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use App\Services\TrackingService;
use Illuminate\Http\Request;
use App\Models\TrackingData;

class TrackingController extends Controller
{
    public function show(Request $request): Response
    {
        $service = new TrackingService();

        $trackingData = TrackingData::create([
            'datetime' => now(),
            'ip_address' => $request->ip(),
            'public_ip_address' => $service->publicIpAddress,
            'location' => $service->getLocation($service->publicIpAddress),
            'os' => $service->getOS($request->userAgent()),
            'device' => $service->getDevice($request->userAgent()),
            'referer' => $request->header('referer'),
            'url' => $request->input('url'),
            'language' => $request->server('HTTP_ACCEPT_LANGUAGE'),
        ]);

        return redirect($trackingData->url);
    }
}
