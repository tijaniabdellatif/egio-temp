<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsCatalogueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options_catalogue', function (Blueprint $table) {
            $table->id();
            $table->string('designation', 100);
            $table->double('price', 8, 2);
            $table->foreignId("type_id")->constrained("option_type","id");

            $table->integer('duration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('options_catalogue');
    }
}
