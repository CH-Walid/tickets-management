<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentairesTable extends Migration
{
    public function up()
    {
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();

            // ✅ Champ texte pour le contenu du commentaire
            $table->text('contenu');

            // 🔗 Liens vers technicien et ticket
            $table->foreignId('technicien_id')
                  ->constrained('techniciens')
                  ->onDelete('cascade')
                  ->cascadeOnUpdate();

            $table->foreignId('ticket_id')
                  ->constrained('tickets')
                  ->onDelete('cascade')
                  ->cascadeOnUpdate();

            // ⚠️ Contraintes uniques : un seul commentaire par technicien par ticket
            $table->unique(['technicien_id', 'ticket_id']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commentaires');
    }
}
