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

            // Statut du ticket
            $table->enum('status', ['nouveau', 'en_cours', 'résolu', 'cloturé'])->default('nouveau');

            // Priorité
            $table->enum('priorite', ['basse', 'normale', 'urgente']);

            // Timestamps de base
            $table->timestamps(); // created_at et updated_at

            // Timestamps spécifiques à l'évolution du ticket
            $table->timestamp('in_progress_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            // Suppression douce
            $table->softDeletes(); // deleted_at

            // Références
            $table->foreignId('user_simple_id')
                  ->constrained('user_simples')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();

            // Si un technicien est supprimé, ne supprime pas le ticket
            $table->foreignId('technicien_id')
                  ->nullable()
                  ->constrained('techniciens')
                  ->nullOnDelete()
                  ->cascadeOnUpdate();

            // Si la catégorie est supprimée, ne supprime pas les tickets
            $table->foreignId('categorie_id')
                  ->nullable()
                  ->constrained('categories')
                  ->nullOnDelete()
                  ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
