<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('max_ad_img');
            $table->integer('max_ad_video')->nullable();
            $table->integer('max_ad_audio')->nullable();
            $table->integer('ads_expire_duration')->nullable();
            $table->integer('users_expire_duration')->nullable();
            $table->integer('max_user_ads')->nullable();
            $table->integer('image_max_size')->nullable();
            $table->integer('video_max_size')->nullable();
            $table->integer('audio_max_size')->nullable();
            $table->string('facebook', 100)->nullable();
            $table->string('instagram', 100)->nullable();
            $table->string('twitter', 100)->nullable();
            $table->string('linkedin', 100)->nullable();
            $table->string('youtube', 100)->nullable();
            $table->string('tiktok', 100)->nullable();
            $table->text('seo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
