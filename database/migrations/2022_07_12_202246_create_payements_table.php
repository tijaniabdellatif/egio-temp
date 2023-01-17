<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payements', function (Blueprint $table) {
            $table->id();
            $table->foreignId("contract_id")->constrained("contracts","id");
            $table->double('amount');
            $table->timestamp('date')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('due_date')->default('0000-00-00 00:00:00');
            $table->foreignId("attach_file")->constrained("media","id");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payements');
    }
}
