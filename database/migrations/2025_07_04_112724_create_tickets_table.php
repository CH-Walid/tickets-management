<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description');
            $table->text('piece_jointe')->nullable();
            $table->enum('status', ['nouveau', 'en_cours','résolu', 'cloturé'])->default('nouveau');
            $table->enum('priorite', ['basse', 'normale', 'urgente']);
            $table->timestamps(); // créé_at et mis à jour_at

            $table->timestamp('in_progress_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->softDeletes(); // pour deleted_at

            $table->foreignId('user_simple_id')->constrained('user_simples')->cascadeOnDelete()->cascadeOnUpdate();
            // if the technicien asigned to a ticket got deleted do not delete the ticket
            $table->foreignId('technicien_id')->nullable()->constrained('techniciens')->nullOnDelete()->cascadeOnUpdate();
            // if the category is deleted do not delete tickets from the same category
            $table->foreignId('categorie')->nullable()->constrained('categories')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    #TO DO:
    // ADD a trigger so when a tichnicien got deleted and he/she is asigned to some tickets, if the tickets is in processing status, set it as open(nouveau)!!!

    public function down()
    {
        Schema::dropIfExists('tickets');
    }

};
