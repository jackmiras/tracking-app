<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_data', function (Blueprint $table) {
            $table->id();
            $table->timestamp('datetime');
            $table->string('ip_address');
            $table->string('location');
            $table->string('os');
            $table->string('device');
            $table->string('referer')->nullable();
            $table->string('url');
            $table->string('language')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_data');
    }
};
