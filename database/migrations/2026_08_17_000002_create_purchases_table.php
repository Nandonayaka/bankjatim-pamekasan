<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('item_id')->constrained('items')->cascadeOnDelete();
            $table->date('transaction_date');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2); // harga satuan kulaan
            $table->decimal('total_amount', 15, 2); // qty * unit_price
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
