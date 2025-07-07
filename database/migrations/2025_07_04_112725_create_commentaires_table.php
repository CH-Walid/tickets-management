<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->foreignId('technicien_id')->constrained('techniciens')->onDelete('cascade');
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['technicien_id', 'ticket_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('commentaires');
    }
};

