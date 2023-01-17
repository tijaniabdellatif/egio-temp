<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->double('lat', 10, 8)->nullable();
            $table->double('lng', 10, 8)->nullable();
            $table->multiPolygon('coordinates')->nullable();
            $table->point('point')->nullable();
            $table->foreignId("province_id")->constrained("provinces","id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('cities');
    }
}
