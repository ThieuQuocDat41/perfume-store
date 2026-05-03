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
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('recipient_name')->nullable();
                $table->string('recipient_phone')->nullable();
                $table->text('recipient_address')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $cols = [];
                if (Schema::hasColumn('orders', 'recipient_name')) $cols[] = 'recipient_name';
                if (Schema::hasColumn('orders', 'recipient_phone')) $cols[] = 'recipient_phone';
                if (Schema::hasColumn('orders', 'recipient_address')) $cols[] = 'recipient_address';
                if (!empty($cols)) {
                    $table->dropColumn($cols);
                }
            });
        }
    }
};
