<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateLogRegistriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_registries', function (Blueprint $table) {
            $table->id();
            $table->integer('uid');
            $table->string('action');
            $table->text('description');
            $table->string("date")->default(Carbon::now()->toDateTimeString());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_registries');
    }
}
