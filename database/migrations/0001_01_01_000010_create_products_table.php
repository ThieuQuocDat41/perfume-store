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
Schema::create('products', function (Blueprint $table) {
    $table->id();

    $table->string('name');
    $table->string('brand');

    $table->decimal('price_usd', 8, 2);

    $table->string('gender')->nullable();

    $table->integer('stock');

    $table->text('short_description')->nullable();

    $table->text('top_notes')->nullable();
    $table->text('heart_notes')->nullable();
    $table->text('base_notes')->nullable();

    $table->text('images')->nullable(); // JSON string

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
