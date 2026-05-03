<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('voucher_code')->nullable();
                $table->unsignedTinyInteger('discount_percent')->nullable();
                $table->decimal('original_total_price', 8, 2)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $cols = [];
                if (Schema::hasColumn('orders', 'voucher_code')) $cols[] = 'voucher_code';
                if (Schema::hasColumn('orders', 'discount_percent')) $cols[] = 'discount_percent';
                if (Schema::hasColumn('orders', 'original_total_price')) $cols[] = 'original_total_price';
                if (!empty($cols)) $table->dropColumn($cols);
            });
        }
    }
};
