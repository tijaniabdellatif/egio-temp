<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypesActions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types_actions', function (Blueprint $table) {
            $table->bigInteger('type_id')->unsigned(); 
            $table->bigInteger('action_id')->unsigned(); 
            //FOREIGN KEY CONSTRAINTS 
            $table->foreign('type_id')->references('id')->on('user_type')->onDelete('cascade'); 
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade'); 
    
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('types_actions');
    }
}
