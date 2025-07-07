<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id0;
            $table->string('titre');
            $table->text('description');
            $table->text('piece_jointe')->nullable();
            $table->enum('status', ['nouveau', 'en_cours','résolu', 'cloturé'])->default('nouveau');
            $table->enum('priorite', ['basse', 'normale', 'urgente'])->default('basse');
            $table->timestamps(); // créé_at et mis à jour_at

            $table->timestamp('in_progress_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->softDeletes(); // pour deleted_at

            $table->foreignId('user_simple_id')->constrained('user_simples')->onDelete('cascade');
            $table->foreignId('technicien_id')->nullable()->constrained('techniciens')->onDelete('set null');
            $table->foreignId('categorie')->constrained('categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }

};
