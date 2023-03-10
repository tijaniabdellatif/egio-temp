<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroundGardenLevelToAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->tinyInteger('groundfloor')->nullable();
            $table->tinyInteger('gardenlevel')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn('groundfloor');
            $table->dropColumn('gardenlevel');
        });
    }
}
