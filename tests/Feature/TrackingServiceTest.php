<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use App\Helpers\Constants;
use App\Services\TrackingService;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TrackingServiceTest extends TestCase
{
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new TrackingService();
    }

   public function test_can_get_public_ip_address(): void
    {
        Http::fake([
            Constants::APIFY_URL => Http::response(['ip' => '127.0.0.1'], Response::HTTP_OK),
        ]);

        $result = $this->service->getPublicIpAddress();

        $this->assertEquals('127.0.0.1', $result);
    }

    public function test_cannot_get_public_ip_address(): void
    {
        Http::fake([
            Constants::APIFY_URL => Http::response([], Response::HTTP_INTERNAL_SERVER_ERROR),
        ]);

        $result = $this->service->getPublicIpAddress();

        $this->assertEquals('Public ip not found', $result);
    }

   public function test_can_get_location(): void
    {
        Http::fake([
            Constants::IPAPI_URL => Http::response([
                'status' => 'success',
                'country' => 'Brazil',
                'countryCode' => 'BR',
                'region' => 'MS',
                'regionName' => 'Mato Grosso do Sul',
                'city' => 'Campo Grande',
                'zip' => '79000',
                'lat' => -20.4428,
                'lon' => -54.6464,
                'timezone' => 'America/Campo_Grande',
                'isp' => 'Flextel Network Telecomunicacoes Eireli - ME',
                'org' => 'Flextel Network Telecomunicacoes Eireli - ME',
                'as' => 'AS263959 FLEXTEL NETWORK TELECOMUNICACOES EIRELI - ME',
                'query' => '170.238.251.179',
                ], Response::HTTP_OK),
        ]);

        $result = $this->service->getLocation($this->service->publicIpAddress);

        $this->assertEquals('Campo Grande, Mato Grosso do Sul, Brazil', $result);
    }

    public function test_cannot_get_location(): void
    {
        Http::fake([
            Constants::IPAPI_URL => Http::response([], Response::HTTP_INTERNAL_SERVER_ERROR),
        ]);

        $result = $this->service->getLocation('192.168.1.0');

        $this->assertEquals('Location not available', $result);
    }

    public function test_can_get_os(): void
    {
        $dataSet = [
            [
                'userAgent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36',
                'expectedOS' => 'Windows'
            ],
            [
                'userAgent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36',
                'expectedOS' => 'Mac'],
            [
                'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36',
                'expectedOS' => 'GNU/Linux'
            ],
            [
                'userAgent' => 'Mozilla/5.0 (Android 7.0; Mobile; rv:68.0) Gecko/68.0 Firefox/68.0',
                'expectedOS' => 'Android'
            ],
            [
                'userAgent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148',
                'expectedOS' => 'iOS'
            ],
        ];

        foreach ($dataSet as $data) {
            $result = $this->service->getOS($data['userAgent']);

            $this->assertEquals($data['expectedOS'], $result);
        }
    }


    public function test_cannot_get_os(): void
    {
        $data = [
            'userAgent' => '',
            'expectedOS' => 'UNK'
        ];

        $result = $this->service->getOS($data['userAgent']);

        $this->assertEquals($data['expectedOS'], $result);
    }

    public function test_can_get_device(): void
    {
        $dataSet = [
            [
                'userAgent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36',
                'expectedDevice' => 'desktop'
            ],
            [
                'userAgent' => 'Mozilla/5.0 (Linux; Android 10; SM-G975F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Mobile Safari/537.36',
                'expectedDevice' => 'smartphone'
            ],
            [
                'userAgent' => 'Invalid User Agent',
                'expectedDevice' => 'Unknown Device'
            ],
        ];

        foreach ($dataSet as $data) {
            $result = $this->service->getDevice($data['userAgent']);

            $this->assertEquals($data['expectedDevice'], $result);
        }
    }

    public function test_connot_get_device(): void
    {
        $data = [
                'userAgent' => 'Invalid User Agent',
                'expectedDevice' => 'Unknown Device'
            ];

        $result = $this->service->getDevice($data['userAgent']);

        $this->assertEquals($data['expectedDevice'], $result);
    }
}
