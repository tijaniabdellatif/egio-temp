<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNearbyPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nearby_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_ad")->constrained("ads","id");
            $table->integer('distance')->nullable();
            $table->double('lng', 10, 8)->nullable();
            $table->double('lat', 10, 8)->nullable();
            $table->string('title', 100);
            $table->foreignId("id_place_type")->nullable()->constrained("places_type","id");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nearby_places');
    }
}
