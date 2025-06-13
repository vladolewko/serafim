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
            $table->string('order_reference')->unique(); // Унікальний номер замовлення
            $table->enum('status', ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->enum('payment_type', ['cash', 'card']);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');

            // Дані клієнта
            $table->string('customer_name');
            $table->string('customer_surname');
            $table->string('customer_phone');
            $table->string('customer_email');

            // Дані доставки Nova Post
            $table->string('settlement_ref');
            $table->string('warehouse_ref');
            $table->string('counterparty_ref')->nullable();
            $table->string('contact_person_ref')->nullable();

            // Дані товару та вартості
            $table->json('cart_data'); // Зберігаємо дані кошика
            $table->decimal('product_total', 10, 2);
            $table->decimal('delivery_cost', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2);

            // Дані для WayForPay
            $table->json('wayforpay_data')->nullable();

            // ТТН дані
            $table->string('ttn_number')->nullable();
            $table->json('ttn_response')->nullable();

            // Додаткові поля
            $table->timestamp('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Індекси
            $table->index('order_reference');
            $table->index('status');
            $table->index('payment_status');
            $table->index('customer_phone');
            $table->index('customer_email');
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
