<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_media', function (Blueprint $table) {
            $table->bigInteger('ad_id')->unsigned(); 
            $table->bigInteger('media_id')->unsigned(); 
            $table->integer('order');
            //FOREIGN KEY CONSTRAINTS 
            $table->foreign('ad_id')->references('id')->on('ads')->onDelete('cascade'); 
            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade'); 
            //SETTING THE PRIMARY KEYS 
            $table->string('informations');
            $table->primary(['ad_id','media_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_media');
    }
}
