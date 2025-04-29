<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajoute thread_openai_id à file_messages.
     */
    public function up(): void
    {
        Schema::table('file_messages', function (Blueprint $table) {
            // uuid nullable, placé juste après message_openai_id
            $table->uuid('thread_openai_id')
                ->nullable()
                ->after('message_openai_id');

            // facultatif mais pratique pour les recherches par thread
            $table->index('thread_openai_id');
        });
    }

    /**
     * Rollback : retire la colonne si on fait un down().
     */
    public function down(): void
    {
        Schema::table('file_messages', function (Blueprint $table) {
            $table->dropIndex(['thread_openai_id']);   // supprime l’index
            $table->dropColumn('thread_openai_id');    // supprime la colonne
        });
    }
};
