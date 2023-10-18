<?php

namespace Tests\Feature;

use App\Models\TrackingData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_to_specified_url(): void
    {
        $url = 'https://www.crunchyroll.com';

        $response = $this->get("track?url=$url");

        $response->assertRedirect($url);
    }

    public function test_single_track_object_was_created(): void
    {
        $url = 'https://www.crunchyroll.com';

        $this->get("track?url=$url");

        $this->assertCount(1, TrackingData::all());
    }

    public function test_url_sended_matches_url_tracked(): void
    {
        $url = 'https://www.crunchyroll.com';

        $this->get("track?url=$url");

        $this->assertEquals($url, TrackingData::all()->first()->url);
    }
}
