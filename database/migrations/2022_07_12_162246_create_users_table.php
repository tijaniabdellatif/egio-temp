<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->string('username', 25);
            $table->string('email', 100);
            $table->string('phone', 20)->nullable();
            $table->foreignId('usertype')->constrained('user_type','id');
            $table->date('birthdate')->nullable();
            $table->timestamp('expiredate')->nullable();
            $table->string('password', 100);
            $table->foreignId('authtype')->nullable()->constrained('auth_type');
            $table->string('status', 2)->default('00');
            $table->string('api_token', 100)->nullable();
            $table->rememberToken();
            $table->integer("assigned_user")->nullable();
            $table->integer("assigned_ced")->nullable();
            $table->integer('coins')->default(0);
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
        Schema::dropIfExists('users');
    }
}
