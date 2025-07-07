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
        Schema::create('techniciens', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->uuid('code_unique')->default(\Illuminate\Support\Str::uuid());
            $table->unsignedBigInteger('service_id');

            // Contrainte de clé étrangère
            $table->foreign('id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('service_id')->references('id')->on('services')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('techniciens');
    }
};
