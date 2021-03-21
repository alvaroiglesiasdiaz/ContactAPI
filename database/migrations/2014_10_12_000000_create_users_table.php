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
            $table->string('name_user');
            $table->string('email_user')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password_user');
            $table->rememberToken();
            $table->timestamps();
            $table->string('api_token',1000);

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
