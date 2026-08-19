<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('item_id')->constrained('items')->cascadeOnDelete();
            $table->string('period', 7); // e.g. '2026-08'
            $table->integer('initial_stock')->default(0);
            $table->integer('total_in')->default(0);
            $table->integer('total_out')->default(0);
            $table->integer('final_stock')->default(0);
            $table->timestamps();
            $table->unique(['item_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
