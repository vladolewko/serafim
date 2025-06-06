<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (User::count() == 0) {
            
            User::factory()->create();

        }
        Product::factory(10)->create();


        //  // Створюємо тестові замовлення
        // Order::factory(10)->pending()->create();
        // Order::factory(5)->shipped()->create();
        // Order::factory(3)->delivered()->create();
        // Order::factory(15)->cashOnDelivery()->create();
        
        // // Додаємо кілька замовлень з конкретними даними для тестування
        // Order::factory()->create([
        //     'recipient_first_name' => 'Іван',
        //     'recipient_last_name' => 'Петренко',
        //     'recipient_phone' => '380971234567',
        //     'recipient_email' => 'ivan@example.com',
        //     'total_amount' => 1500.00,
        //     'payment_method' => 'cash_on_delivery',
        //     'status' => 'pending'
        // ]);
        
        // Order::factory()->create([
        //     'recipient_first_name' => 'Марія',
        //     'recipient_last_name' => 'Коваленко',
        //     'recipient_phone' => '380501234567',
        //     'recipient_email' => 'maria@example.com',
        //     'total_amount' => 2500.50,
        //     'payment_method' => 'card',
        //     'status' => 'processing'
        // ]);

    }
}
