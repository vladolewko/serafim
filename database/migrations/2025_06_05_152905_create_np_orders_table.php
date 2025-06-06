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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // Основна інформація про замовлення
            $table->unsignedBigInteger('product_id')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['cash_on_delivery', 'card', 'bank_transfer'])->default('cash_on_delivery');
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            
            // Інформація про отримувача
            $table->string('recipient_first_name');
            $table->string('recipient_last_name');
            $table->string('recipient_middle_name')->nullable();
            $table->string('recipient_phone');
            $table->string('recipient_email')->nullable();
            
            // Адреса доставки
            $table->string('recipient_city_ref', 36);
            $table->string('recipient_warehouse_ref', 36);
            
            // Характеристики відправлення
            $table->decimal('weight', 8, 2)->default(1.00);
            $table->integer('seats_amount')->default(1);
            $table->text('description')->nullable();
            
            // ТТН інформація
            $table->string('ttn_number')->nullable();
            $table->string('ttn_ref', 36)->nullable();
            $table->decimal('shipping_cost', 8, 2)->nullable();
            
            // Додаткові поля
            $table->text('notes')->nullable();
            $table->timestamp('shipped_at')->nullable();
            
            $table->timestamps();
            
            // Індекси
            $table->index(['status', 'created_at']);
            $table->index('ttn_number');
            $table->index('recipient_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};