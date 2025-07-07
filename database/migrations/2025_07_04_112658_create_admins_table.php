<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
             $table->unsignedBigInteger('id')->primary();

            // avoid using onDelete("cascade"); couse you can miss write cascase!!
            $table->foreign('id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admins');
    }
};

