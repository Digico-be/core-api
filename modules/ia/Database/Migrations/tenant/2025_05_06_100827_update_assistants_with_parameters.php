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
        Schema::table('assistants', function (Blueprint $table) {
            $table->enum('type', ['general', 'specialized'])->default('general');
            $table->string('module')->nullable()->change(); // devient nullable pour les assistants généraux

            $table->float('temperature')->default(0.7);
            $table->integer('max_tokens_output')->nullable();
            $table->json('rules')->nullable();

            $table->string('persona')->nullable();
            $table->json('suggested_prompts')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assistants', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'temperature',
                'max_tokens_output',
                'rules',
                'persona',
                'suggested_prompts',
            ]);
            $table->string('module')->change();
        });
    }
};
