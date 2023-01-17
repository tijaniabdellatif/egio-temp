<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cats', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->nullable();
            $table->foreignId("parent_cat")->nullable()->constrained("cats","id")->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('type', 10);
            $table->string('status', 2);
            $table->boolean('is_project')->default(false);
            $table->json('fields')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cats');
    }
}
