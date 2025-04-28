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

        // Création de la table de pivot 'assistant_user_tenant'
        Schema::create('assistant_user_tenant', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assistant_id');
            $table->unsignedBigInteger('user_tenant_id');
            $table->timestamps();

            // Indexes pour optimiser les relations
            $table->index('assistant_id');
            $table->index('user_tenant_id');
        });
    }

    public function down(): void
    {
        // Suppression dans l'ordre inverse de création
        Schema::dropIfExists('assistant_user_tenant');
        Schema::dropIfExists('assistants');
    }
};
