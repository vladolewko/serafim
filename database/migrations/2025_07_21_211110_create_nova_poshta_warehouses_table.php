<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

//            Schema::dropIfExists('nova_poshta_warehouses');

        Schema::create('nova_poshta_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('ref')->unique()->index();
            $table->string('description');
            $table->string('description_ru')->nullable();
            $table->string('short_address');
            $table->string('short_address_ru')->nullable();
            $table->string('phone')->nullable();
            $table->string('type_of_warehouse')->nullable();
            $table->string('warehouse_type')->nullable();
            $table->string('category_of_warehouse')->nullable();
            $table->decimal('total_max_weight_allowed', 8, 2)->nullable();
            $table->decimal('max_volume_allowed', 8, 2)->nullable();
            $table->integer('place_max_weight_allowed')->nullable();
            $table->json('dimensions_allowed')->nullable(); // для габаритів
            $table->string('settlement_ref');
            $table->string('city_ref');
            $table->string('city_description');
            $table->string('city_description_ru')->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->json('post_finance')->nullable();
            $table->json('bicycle_parking')->nullable();
            $table->json('payment_access')->nullable();
            $table->json('pos_terminal')->nullable();
            $table->json('international_shipping')->nullable();
            $table->json('self_service_workplaces_count')->nullable();
            $table->json('total_max_weight_allowed_details')->nullable();
            $table->string('work_in_mobile_awis')->nullable();
            $table->json('direct_direction')->nullable();
            $table->json('return_direction')->nullable();
            $table->json('reception')->nullable();
            $table->json('delivery')->nullable();
            $table->json('schedule')->nullable();
            $table->string('district_code')->nullable();
            $table->string('warehouse_status')->nullable();
            $table->string('warehouse_status_date')->nullable();
            $table->string('warehouse_illiquid_status')->nullable();
            $table->string('warehouse_illiquid_status_date')->nullable();
            $table->integer('generator_enabled')->nullable();
            $table->integer('mail_only')->nullable();
            $table->json('copy_work_hours')->nullable();
            $table->json('services_filter')->nullable();
            $table->json('type_of_restrictions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Зовнішні ключі
            $table->foreign('settlement_ref')->references('ref')->on('nova_poshta_settlements')->onDelete('cascade')->onUpdate('cascade');

            // Індекси
            $table->index(['settlement_ref', 'is_active'], 'np_settlement_active_idx');
            $table->index(['type_of_warehouse', 'category_of_warehouse'], 'np_type_category_idx');
            $table->index(['city_description', 'is_active'], 'np_city_active_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nova_poshta_warehouses');
    }
};
