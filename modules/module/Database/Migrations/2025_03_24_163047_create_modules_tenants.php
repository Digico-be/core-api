<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('tenant_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('module_id');
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('module_id');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_modules');
    }
};
