<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stockin_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_in_id')->constrained('stockins')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('qty');
            $table->decimal('price', 10, 2);
            $table->decimal('total', 12, 2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stockin_items');
    }
};
