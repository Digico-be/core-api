<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();                                 // PK auto‑incrément
            $table->string('openai_id')->unique();        // id OpenAI (string)
            $table->unsignedBigInteger('thread_id');      // FK threads.id
            $table->enum('role', ['user', 'assistant']);
            $table->longText('raw_text')->nullable();     // texte brut
            $table->json('attachments')->nullable();      // [{file_openai_id,…}]
            $table->timestamps();

            $table->foreign('thread_id')
                ->references('id')->on('threads')
                ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('messages');
    }
};

