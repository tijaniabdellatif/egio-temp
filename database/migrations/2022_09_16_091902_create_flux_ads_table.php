<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFluxAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flux_ads', function (Blueprint $table) {
            $table->id();
            $table->string('link',500);
            $table->foreignId("id_user")->constrained("users","id")->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('fields');
            $table->boolean('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flux_ads');
    }
}
