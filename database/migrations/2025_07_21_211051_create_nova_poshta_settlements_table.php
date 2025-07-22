<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('nova_poshta_settlements');
        Schema::create('nova_poshta_settlements', function (Blueprint $table) {
            $table->id();
            $table->string('ref')->unique()->index();
            $table->string('description');
            $table->string('description_ru')->nullable();
            $table->string('settlement_type');
            $table->string('settlement_type_description');
            $table->string('area_description');
            $table->string('area_description_ru')->nullable();
            $table->string('region_description');
            $table->string('region_description_ru')->nullable();
            $table->boolean('delivery');
            $table->boolean('is_city_available');
            $table->integer('conglomerates')->nullable();
            $table->integer('api_warehouses_count')->default(0); // Додано для відстеження
            $table->boolean('is_active')->default(true);
            $table->timestamps();



            // Індекси для пошуку
            $table->index(['description', 'is_active']);
            $table->index(['region_description', 'area_description'], 'np_region_area_idx');
            $table->boolean('warehouse')->default(false);
            $table->index(['warehouse', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nova_poshta_settlements');
    }
};
