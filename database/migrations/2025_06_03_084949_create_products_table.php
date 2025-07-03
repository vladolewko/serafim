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
            $table->string('keycrm_id')->nullable();
            $table->string('name')->unique(); // Назва комплекту +
            $table->text('description')->nullable(); // Опис комплекту +
            $table->integer('price'); // Ціна комплекту +
            $table->integer('books_quantity'); // Кількість книг у комплекті +
            $table->decimal('weight', 8, 2); // Вага комплекту +
            $table->integer('length'); // Довжина комплекту +
            $table->integer('width'); // Ширина комплекту +
            $table->integer('height'); // Висота комплекту +
            $table->json('content'); // Вміст комплекту +
            $table->json('for_whom'); // Для кого призначено комплект +
            $table->string('appointment'); // призначення комплекту +
            $table->enum('applying',  \App\Enums\ProductApplyingEnum::values())->default('citizen');
            $table->timestamps();

            $table->index('keycrm_id'); // Індекс для швидкого пошуку

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
