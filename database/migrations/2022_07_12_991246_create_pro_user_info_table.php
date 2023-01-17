<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProUserInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_user_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->string('address', 300)->nullable();
            $table->string('loclng', 20)->nullable();
            $table->string('loclat', 20)->nullable();
            $table->foreignId("city")->nullable()->constrained("cities");
            $table->string('locdept', 50)->nullable();
            $table->string('locregion', 50)->nullable();
            $table->string('loccountrycode', 20)->nullable();
            $table->integer('video')->nullable();
            $table->integer('audio')->nullable();
            $table->integer('image')->nullable();
            $table->string('company', 50);
            $table->string('website', 70)->nullable();
            $table->foreignId("probannerimg")->nullable()->constrained("media");
            $table->text('longdesc')->nullable();
            $table->text('videoembed')->nullable();
            $table->text('metatitle')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pro_user_info');
    }
}
