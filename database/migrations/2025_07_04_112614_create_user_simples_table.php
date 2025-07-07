<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_simples', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();// Clé étrangère vers users


            // Contrainte de clé étrangère
            $table->foreign('id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();

            // you did forget that user have a foreign key with services table!!!!
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_simples');
    }
};
