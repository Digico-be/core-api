<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->string('openai_id')->unique();
            $table->unsignedBigInteger('assistant_id');
            $table->string('module')->nullable();
            $table->timestamps();

            // Clé étrangère pour lier aux assistants
            $table->foreign('assistant_id')
                ->references('id')->on('assistants')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('threads');
    }
};
