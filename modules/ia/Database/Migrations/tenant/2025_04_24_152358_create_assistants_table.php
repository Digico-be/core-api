<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Création de la table 'assistants'
        Schema::create('assistants', function (Blueprint $table) {
            $table->id();
            $table->string('openai_id')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('module');
            $table->string('model')->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps();
        });

        // Création de la table de relation 'assistant_user_tenant'
        Schema::create('assistant_user_tenant', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assistant_id');  // ID de l'assistant
            $table->unsignedBigInteger('user_tenant_id');  // ID du tenant utilisateur
            $table->timestamps();

            // Indexes pour les relations
            $table->index('assistant_id');
            $table->index('user_tenant_id');
        });
    }

    public function down(): void
    {
        // Suppression des tables dans l'ordre inverse
        Schema::dropIfExists('assistant_user_tenant');
        Schema::dropIfExists('assistants');
    }
};

