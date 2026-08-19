<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('inventory', 'stock_date')) {
            Schema::table('inventory', function (Blueprint $table) {
                $table->date('stock_date')->nullable()->after('item_id');
            });
        }

        // Fill existing stock_date from period
        DB::statement("UPDATE inventory SET stock_date = STR_TO_DATE(CONCAT(period, '-01'), '%Y-%m-%d') WHERE stock_date IS NULL");

        try {
            Schema::table('inventory', function (Blueprint $table) {
                $table->unique(['item_id', 'stock_date']);
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('inventory', function (Blueprint $table) {
                $table->dropUnique(['item_id', 'period']);
            });
        } catch (\Exception $e) {}
    }

    public function down(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            try {
                $table->dropUnique(['item_id', 'stock_date']);
            } catch (\Exception $e) {}
            if (Schema::hasColumn('inventory', 'stock_date')) {
                $table->dropColumn('stock_date');
            }
            $table->unique(['item_id', 'period']);
        });
    }
};
