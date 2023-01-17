<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Adscolumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->tinyInteger('balcony')->nullable();
            $table->tinyInteger('green_spaces')->nullable();
            $table->tinyInteger('guardian')->nullable();
            $table->tinyInteger('automation')->nullable();
            $table->tinyInteger('sea_view')->nullable();
            $table->tinyInteger('box')->nullable();
            $table->tinyInteger('equipped_kitchen')->nullable();
            $table->tinyInteger('soundproof')->nullable();
            $table->tinyInteger('thermalinsulation')->nullable();
            $table->tinyInteger('playground')->nullable();
            $table->tinyInteger('gym')->nullable();
            $table->tinyInteger('Chimney')->nullable();
            $table->tinyInteger('sportterrains')->nullable();
            $table->tinyInteger('library')->nullable();
            $table->tinyInteger('double_orientation')->nullable();
            $table->tinyInteger('intercom')->nullable();
            $table->tinyInteger('garage')->nullable();
            $table->tinyInteger('double_glazing')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
