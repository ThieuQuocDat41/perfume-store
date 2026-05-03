<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('sub_image_1')->nullable();
                $table->string('sub_image_2')->nullable();
                $table->json('tags')->nullable();
            });

            // Ensure existing products have a stock value
            DB::table('products')->whereNull('stock')->update(['stock' => 20]);
        }

        if (Schema::hasTable('cart_items')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->string('volume')->nullable();
            });
        }

        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->string('volume')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                // dropColumn will throw if columns do not exist on some drivers; check columns if needed
                $columns = [];
                if (Schema::hasColumn('products', 'sub_image_1')) $columns[] = 'sub_image_1';
                if (Schema::hasColumn('products', 'sub_image_2')) $columns[] = 'sub_image_2';
                if (Schema::hasColumn('products', 'tags')) $columns[] = 'tags';
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasTable('cart_items')) {
            Schema::table('cart_items', function (Blueprint $table) {
                if (Schema::hasColumn('cart_items', 'volume')) {
                    $table->dropColumn('volume');
                }
            });
        }

        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                if (Schema::hasColumn('order_items', 'volume')) {
                    $table->dropColumn('volume');
                }
            });
        }
    }
};
