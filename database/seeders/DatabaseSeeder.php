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
        Product::factory()->create([

            'name' => 'комплект громадянина5',
            'description' => 'Цей набір створено ветераном, який особисто пройшов через свавілля системи і переміг. У ньому — не суха теорія, а перевірені інструкції, які допомогли відстояти свої права в реальних судах.',
            'price' => 600,
            'for_whom' => ['Військовослужбовці ЗСУ', 'Військовослужбовці ТРО', 'добровольці'],
            'content' => ['Покрокові інструкції щодо оформлення статусу УБД, інвалідності, компенсацій.', 'Як подати позов до державних структур та виграти справу.', 'Шаблони документів', 'Пояснення термінів', 'Посилання на актуальні закони'],
            'weight' => 2,
            'books_quantity' => 5,
            'dimension' => '20 на 20 на 20',
            'appointment' => 'інформування щодо юридичного захисту',
        ]);


    }
}
