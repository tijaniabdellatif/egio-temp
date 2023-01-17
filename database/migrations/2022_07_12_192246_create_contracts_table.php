<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users","id");
            $table->foreignId("assigned_user")->constrained("users","id");
            $table->string('comment', 500)->nullable();
            $table->double('price');
            $table->foreignId("plan_id")->constrained("plan_catalogue","id");
            $table->integer('ltc_nbr');
            $table->integer('ads_nbr');
            $table->timestamp('date')->useCurrent();
            $table->integer('duration');
            $table->foreignId("contract_file")->nullable()->constrained("media","id");
            $table->tinyInteger('active')->default(1);
            $table->string('reference')->nullable();
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
        Schema::dropIfExists('contracts');
    }
}
