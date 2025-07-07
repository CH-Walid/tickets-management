<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->text('img')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            // always avoiding deleting the default laravel columns
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->string('password');
            $table->enum('role', ['user', 'admin', 'chef', 'tech'])->default('user');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}

