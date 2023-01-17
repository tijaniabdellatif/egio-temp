<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MetaDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_descriptions', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->string('type',20)->nullable();
            $table->boolean('city');
            $table->boolean('cat');
            $table->boolean('neighborhood');
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
        //
    }
}
