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
            $table->string('name')->unique(); // Назва комплекту +
            $table->text('description')->nullable(); // Опис комплекту +
            $table->integer('price'); // Ціна комплекту +
            $table->integer('books_quantity'); // Кількість книг у комплекті +
            $table->decimal('weight', 8, 2); // Вага комплекту +
            $table->string('dimension'); // Розміри комплекту +
            $table->json('content'); // Вміст комплекту +
            $table->json('for_whom'); // Для кого призначено комплект +
            $table->string('appointment'); // призначення комплекту +
            $table->enum('applying',  \App\Enums\ProductApplyingEnum::values())->default('citizen');
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
