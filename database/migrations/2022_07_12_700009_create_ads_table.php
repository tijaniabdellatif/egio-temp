<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('title', 250);
            $table->text('description');
            $table->foreignId("catid")->constrained("cats");
            $table->foreignId("parent_project")->nullable()->constrained("ads","id")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("id_user")->constrained("users","id")->cascadeOnDelete()->cascadeOnUpdate();
            $table->double('price', 10, 2);
            $table->string('price_curr', 3);
            $table->string('videoembed', 1000)->nullable();
            $table->double('loclng')->nullable();
            $table->double('loclat')->nullable();
            //$table->string('loczipcode', 25)->nullable();
            $table->foreignId("locdept")->nullable();
            $table->foreignId("loccity")->constrained("cities","id");
            //$table->string('lcocity2', 200)->nullable();
            $table->string('locdept2', 200)->nullable();
            //$table->string('locregion', 50)->nullable();
            $table->string('loccountrycode', 2);
            $table->integer('phone');
            $table->integer('phone2')->nullable();
            $table->integer('wtsp')->nullable();
            $table->integer('email');
            $table->integer('likes')->default(0);
            $table->integer('audio')->nullable();
            // Project type
            $table->foreignId("project_priority")->nullable()->constrained("project_priority","id");
            //Caractéristiques
            $table->integer('standing')->nullable();
            $table->integer('rooms')->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('surface')->nullable();
            $table->integer('surface2')->nullable();
            $table->integer('price_surface')->nullable();
            $table->integer('etage')->nullable();
            $table->integer('etage_total')->nullable();
            $table->string('built_year', 5)->nullable();
            $table->tinyInteger('parking')->nullable();
            $table->tinyInteger('jardin')->nullable();
            $table->integer('jardin_surface')->nullable();
            $table->tinyInteger('piscine')->nullable();
            $table->tinyInteger('meuble')->nullable();
            $table->tinyInteger('terrace')->nullable();
            $table->integer('terrace_surface')->nullable();
            $table->tinyInteger('climatise')->nullable();
            $table->tinyInteger('syndic')->nullable();
            $table->tinyInteger('cave')->nullable();
            $table->tinyInteger('ascenseur')->nullable();
            $table->tinyInteger('securite')->nullable();
            /////
            $table->string('ref', 25)->nullable();
            $table->tinyInteger('is_project')->nullable();
            $table->foreignId("bg_image")->nullable()->constrained("media","id");
            $table->string('vr_link', 300)->nullable();
            $table->timestamp('expiredate')->nullable();
            $table->timestamp('moddate')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('published_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('status', 2)->default('00');

            $table->boolean('expireNotify')->nullable()->default(true);

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
        Schema::dropIfExists('ads');
    }
}
