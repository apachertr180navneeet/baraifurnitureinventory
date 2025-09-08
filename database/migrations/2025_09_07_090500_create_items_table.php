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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            // Item fields
            $table->string('name');
            $table->string('code')->unique();
            $table->unsignedBigInteger('category_id'); // Foreign key
            $table->integer('qty')->default(0);
            $table->string('image')->nullable();
            $table->text('price')->default(0);

            // Common fields
            $table->boolean('status')->default(1); // 1 = Active, 0 = Inactive
            $table->timestamps();
            $table->softDeletes(); // ✅ adds deleted_at column

            // Foreign key constraint
            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->onDelete('cascade')   // delete items if category deleted
                ->onUpdate('cascade'); // update items if category id changes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
