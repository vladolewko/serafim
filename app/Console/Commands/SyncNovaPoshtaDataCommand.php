<?php

namespace App\Console\Commands;

use App\Models\NovaPoshtaSettlement;
use App\Models\NovaPoshtaWarehouse;
use App\Services\NovaPostService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncNovaPoshtaDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'novaposhta:sync {--settlements : Синхронізувати тільки населені пункти} {--warehouses : Синхронізувати тільки відділення} {--force : Примусово оновити всі записи';    /**
     * The console command description.
     */
    protected $description = 'Синхронізація населених пунктів та відділень з API Нової Пошти';

    private NovaPostService $novaPostService;
    private int $settlementsProcessed = 0;
    private int $warehousesProcessed = 0;
    private int $settlementsUpdated = 0;
    private int $warehousesUpdated = 0;
    private int $settlementsCreated = 0;
    private int $warehousesCreated = 0;

    public function __construct(NovaPostService $novaPostService)
    {
        parent::__construct();
        $this->novaPostService = $novaPostService;
    }

    public function handle(): int
    {
        // Збільшуємо ліміт пам'яті та часу виконання
        ini_set('memory_limit', '1G');
        ini_set('max_execution_time', 7200); // 2 години

        $this->info('🚀 Початок синхронізації даних Нової Пошти...');

        try {
            // Визначаємо що синхронізувати
            $syncSettlements = !$this->option('warehouses');
            $syncWarehouses = !$this->option('settlements');

            if ($syncSettlements) {
                $this->syncSettlements();
            }

            if ($syncWarehouses) {
                $this->syncWarehouses();
            }

            $this->displaySummary();
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Помилка при синхронізації: ' . $e->getMessage());
            Log::error('Nova Poshta sync error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return Command::FAILURE;
        }
    }

    private function syncSettlements(): void
    {
        $this->info('📍 Синхронізація населених пунктів...');

        try {
            $allSettlements = [];
            $page = 1;
            $pageSize = 500; // Розмір сторінки

            // Отримуємо всі сторінки
            do {
                $this->info("📄 Завантаження сторінки {$page}...");

                $response = $this->novaPostService->makeRequest('AddressGeneral', 'getSettlements', [
                    'Warehouse' => "1",
                    'Page' => $page,
                    'Limit' => $pageSize
                ]);

                $settlements = $response['data'] ?? [];
                $totalCount = $response['info']['totalCount'] ?? 0;

                if (empty($settlements)) {
                    break;
                }

                $allSettlements = array_merge($allSettlements, $settlements);

                $this->info("✅ Завантажено " . count($settlements) . " записів зі сторінки {$page}");
                $this->info("📊 Загалом завантажено: " . count($allSettlements) . " з {$totalCount}");

                $page++;

                // Невелика затримка між запитами, щоб не перевантажити API
                usleep(100000); // 100ms

            } while (count($settlements) === $pageSize); // Продовжуємо, поки отримуємо повну сторінку

            if (empty($allSettlements)) {
                $this->warn('⚠️ Не отримано населених пунктів з API');
                return;
            }

            $this->info("🎉 Завантажено загалом {" . count($allSettlements) . "} населених пунктів з API");

            $progressBar = $this->output->createProgressBar(count($allSettlements));
            $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %memory:6s% -- %message%');

            // Обробляємо населені пункти батчами
            $batchSize = 100;
            $chunks = array_chunk($allSettlements, $batchSize);

            foreach ($chunks as $chunkIndex => $chunk) {
                DB::transaction(function () use ($chunk, $progressBar) {
                    foreach ($chunk as $settlementData) {
                        $this->processSettlement($settlementData);
                        $progressBar->setMessage("Обробка: {$settlementData['Description']}");
                        $progressBar->advance();
                    }
                });

                // Очищаємо пам'ять після кожного батчу
                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }

                // Показуємо прогрес обробки батчів
                $this->info("\n🔄 Оброблено батч " . ($chunkIndex + 1) . " з " . count($chunks));
            }

            $progressBar->finish();
            $this->newLine();
            $this->info('✅ Синхронізація населених пунктів завершена успішно!');

        } catch (\Exception $e) {
            $this->error('❌ Помилка при синхронізації населених пунктів: ' . $e->getMessage());
            throw $e;
        }
    }

    private function processSettlement(array $data): void
    {
        try {
            $settlement = NovaPoshtaSettlement::where('ref', $data['Ref'])->first();

            $settlementData = [
                'ref' => $data['Ref'],
                'description' => $data['Description'] ?? '',
                'description_ru' => $data['DescriptionRu'] ?? null,
                'settlement_type' => $data['SettlementType'] ?? '',
                'settlement_type_description' => $data['SettlementTypeDescription'] ?? '',
                'area_description' => $data['AreaDescription'] ?? '',
                'area_description_ru' => $data['AreaDescriptionRu'] ?? null,
                'region_description' => $data['RegionDescription'] ?? '',
                'region_description_ru' => $data['RegionDescriptionRu'] ?? null,
                'delivery' => $this->toBool($data['Delivery'] ?? false),
                'is_city_available' => $this->toBool($data['IsCityAvailable'] ?? false),
                'conglomerates' => !empty($data['Conglomerates']) ? (int)$data['Conglomerates'] : null,
                'is_active' => true,
            ];

            if ($settlement) {
                if ($this->option('force') || $this->shouldUpdateSettlement($settlement, $settlementData)) {
                    $settlement->update($settlementData);
                    $this->settlementsUpdated++;
                }
            } else {
                NovaPoshtaSettlement::create($settlementData);
                $this->settlementsCreated++;
            }

            $this->settlementsProcessed++;

        } catch (\Exception $e) {
            $this->warn("⚠️ Помилка обробки населеного пункту {$data['Ref']}: {$e->getMessage()}");
            Log::warning('Settlement processing error', [
                'ref' => $data['Ref'],
                'error' => $e->getMessage(),
                'data' => $data
            ]);
        }
    }

    private function syncWarehouses(): void
    {
        $this->info('🏢 Синхронізація відділень...');

        try {
            $page = 1;
            $pageSize = 500; // Розмір сторінки
            $totalProcessed = 0;
            $totalCount = 0;

            // Отримуємо та обробляємо сторінки по одній для економії пам'яті
            $warehouses = [];
            do {
                $this->info("📄 Завантаження сторінки {$page}...");

                // Моніторинг пам'яті
                $memoryUsage = memory_get_usage(true) / 1024 / 1024; // MB
                $this->info("💾 Використання пам'яті: " . round($memoryUsage, 2) . " MB");

                try {
                    $response = $this->novaPostService->makeRequest('Address', 'getWarehouses', [
                        'Page' => $page,
                        'Limit' => $pageSize
                    ]);

                    $warehouses = $response['data'] ?? [];
                    $totalCount = $response['info']['totalCount'] ?? 0;

                } catch (\Exception $e) {
                    if (strpos($e->getMessage(), 'many requests') !== false) {
                        $this->warn("⏸️ Досягнуто ліміт запитів. Чекаємо 30 секунд...");
                        sleep(30);
                        continue; // Повторюємо той же запит
                    }
                    throw $e;
                }

                if (empty($warehouses)) {
                    break;
                }

                $warehousesCount = count($warehouses);
                $this->info("✅ Завантажено {$warehousesCount} записів зі сторінки {$page}");
                $totalProcessed += $warehousesCount;
                $this->info("📊 Загалом завантажено: {$totalProcessed} з {$totalCount}");

                // Обробляємо поточну сторінку одразу
                $this->processWarehousePage($warehouses, $page, ceil($totalCount / $pageSize));

                $page++;

                // Збільшена затримка між запитами + прогресивна затримка
                $delay = min(2000000, 500000 + ($page * 100000)); // від 500ms до 2s
                usleep($delay);

                // Примусове очищення пам'яті
                unset($response);
                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }

            } while ($warehousesCount === $pageSize);

            if ($totalProcessed === 0) {
                $this->warn('⚠️ Не отримано відділень з API');
                return;
            }

            $this->info("🎉 Завантажено та оброблено загалом {$totalProcessed} відділень з API");

            // Оновлюємо лічильники відділень у населених пунктах
            $this->updateSettlementWarehouseCounts();

            $this->info('✅ Синхронізація відділень завершена успішно!');

        } catch (\Exception $e) {
            $this->error('❌ Помилка при синхронізації відділень: ' . $e->getMessage());
            throw $e;
        }
    }

    private function processWarehousePage(array $warehouses, int $currentPage, int $totalPages): void
    {
        $progressBar = $this->output->createProgressBar(count($warehouses));
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %memory:6s% -- %message%');
        $progressBar->setMessage("Сторінка {$currentPage}/{$totalPages}");

        // Обробляємо відділення батчами
        $batchSize = 50; // Менший розмір для відділень
        $chunks = array_chunk($warehouses, $batchSize);

        foreach ($chunks as $chunkIndex => $chunk) {
            DB::transaction(function () use ($chunk, $progressBar) {
                foreach ($chunk as $warehouseData) {
                    $this->processWarehouse($warehouseData);
                    $progressBar->setMessage("Обробка: {$warehouseData['Description']}");
                    $progressBar->advance();
                }
            });

            // Очищаємо пам'ять після кожного батчу
            if (function_exists('gc_collect_cycles')) {
                gc_collect_cycles();
            }
        }

        $progressBar->finish();
        $this->newLine();
    }

    private function processWarehouse(array $data): void
    {
        try {
            $warehouse = NovaPoshtaWarehouse::where('ref', $data['Ref'])->first();

            $warehouseData = [
                'ref' => $data['Ref'],
                'description' => $data['Description'] ?? '',
                'description_ru' => $data['DescriptionRu'] ?? null,
                'short_address' => $data['ShortAddress'] ?? '',
                'short_address_ru' => $data['ShortAddressRu'] ?? null,
                'phone' => $data['Phone'] ?? null,
                'type_of_warehouse' => $data['TypeOfWarehouse'] ?? null,
                'warehouse_type' => $data['WarehouseType'] ?? null,
                'category_of_warehouse' => $data['CategoryOfWarehouse'] ?? null,
                'total_max_weight_allowed' => !empty($data['TotalMaxWeightAllowed']) ? (float)$data['TotalMaxWeightAllowed'] : null,
                'max_volume_allowed' => !empty($data['MaxVolumeAllowed']) ? (float)$data['MaxVolumeAllowed'] : null,
                'place_max_weight_allowed' => !empty($data['PlaceMaxWeightAllowed']) ? (int)$data['PlaceMaxWeightAllowed'] : null,
                'dimensions_allowed' => !empty($data['DimensionsAllowed']) ? json_encode($data['DimensionsAllowed']) : null,
                'settlement_ref' => $data['SettlementRef'] ?? '',
                'city_ref' => $data['CityRef'] ?? '',
                'city_description' => $data['CityDescription'] ?? '',
                'city_description_ru' => $data['CityDescriptionRu'] ?? null,
                'longitude' => !empty($data['Longitude']) ? (float)$data['Longitude'] : null,
                'latitude' => !empty($data['Latitude']) ? (float)$data['Latitude'] : null,
                'post_finance' => !empty($data['PostFinance']) ? json_encode($data['PostFinance']) : null,
                'bicycle_parking' => !empty($data['BicycleParking']) ? json_encode($data['BicycleParking']) : null,
                'payment_access' => !empty($data['PaymentAccess']) ? json_encode($data['PaymentAccess']) : null,
                'pos_terminal' => !empty($data['POSTerminal']) ? json_encode($data['POSTerminal']) : null,
                'international_shipping' => !empty($data['InternationalShipping']) ? json_encode($data['InternationalShipping']) : null,
                'self_service_workplaces_count' => !empty($data['SelfServiceWorkplacesCount']) ? json_encode($data['SelfServiceWorkplacesCount']) : null,
                'total_max_weight_allowed_details' => !empty($data['TotalMaxWeightAllowedDetails']) ? json_encode($data['TotalMaxWeightAllowedDetails']) : null,
                'work_in_mobile_awis' => $data['WorkInMobileAwis'] ?? null,
                'direct_direction' => !empty($data['DirectDirection']) ? json_encode($data['DirectDirection']) : null,
                'return_direction' => !empty($data['ReturnDirection']) ? json_encode($data['ReturnDirection']) : null,
                'reception' => !empty($data['Reception']) ? json_encode($data['Reception']) : null,
                'delivery' => !empty($data['Delivery']) ? json_encode($data['Delivery']) : null,
                'schedule' => !empty($data['Schedule']) ? json_encode($data['Schedule']) : null,
                'district_code' => $data['DistrictCode'] ?? null,
                'warehouse_status' => $data['WarehouseStatus'] ?? null,
                'warehouse_status_date' => $data['WarehouseStatusDate'] ?? null,
                'warehouse_illiquid_status' => $data['WarehouseIlluquidStatus'] ?? null,
                'warehouse_illiquid_status_date' => $data['WarehouseIlluquidStatusDate'] ?? null,
                'generator_enabled' => !empty($data['GeneratorEnabled']) ? (int)$data['GeneratorEnabled'] : null,
                'mail_only' => !empty($data['MailOnly']) ? (int)$data['MailOnly'] : null,
                'copy_work_hours' => !empty($data['CopyWorkHours']) ? json_encode($data['CopyWorkHours']) : null,
                'services_filter' => !empty($data['ServicesFilter']) ? json_encode($data['ServicesFilter']) : null,
                'type_of_restrictions' => !empty($data['TypeOfRestrictions']) ? json_encode($data['TypeOfRestrictions']) : null,
                'is_active' => true,
            ];

            if ($warehouse) {
                if ($this->option('force') || $this->shouldUpdateWarehouse($warehouse, $warehouseData)) {
                    $warehouse->update($warehouseData);
                    $this->warehousesUpdated++;
                }
            } else {
                NovaPoshtaWarehouse::create($warehouseData);
                $this->warehousesCreated++;
            }

            $this->warehousesProcessed++;

        } catch (\Exception $e) {
            $this->warn("⚠️ Помилка обробки відділення {$data['Ref']}: {$e->getMessage()}");
            Log::warning('Warehouse processing error', [
                'ref' => $data['Ref'],
                'error' => $e->getMessage(),
                'data' => $data
            ]);
        }
    }

    private function updateSettlementWarehouseCounts(): void
    {
        $this->info('📊 Оновлення лічильників відділень у населених пунктах...');

        DB::statement('
            UPDATE nova_poshta_settlements
            SET api_warehouses_count = (
                SELECT COUNT(*)
                FROM nova_poshta_warehouses
                WHERE nova_poshta_warehouses.settlement_ref = nova_poshta_settlements.ref
                AND nova_poshta_warehouses.is_active = 1
            )
        ');

        $this->info('✅ Лічильники оновлено');
    }

    private function shouldUpdateSettlement(NovaPoshtaSettlement $settlement, array $newData): bool
    {
        // Перевіряємо чи змінились ключові поля
        $keyFields = ['description', 'delivery', 'is_city_available'];

        foreach ($keyFields as $field) {
            if ($settlement->$field != $newData[$field]) {
                return true;
            }
        }

        return false;
    }

    private function shouldUpdateWarehouse(NovaPoshtaWarehouse $warehouse, array $newData): bool
    {
        // Перевіряємо чи змінились ключові поля
        $keyFields = ['description', 'short_address', 'phone', 'type_of_warehouse', 'category_of_warehouse'];

        foreach ($keyFields as $field) {
            if ($warehouse->$field != $newData[$field]) {
                return true;
            }
        }

        return false;
    }

    private function toBool($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            return in_array(strtolower($value), ['true', '1', 'yes', 'on']);
        }

        return (bool)$value;
    }

    private function displaySummary(): void
    {
        $this->newLine();
        $this->info('📈 Підсумки синхронізації:');
        $this->line("├── Населені пункти:");
        $this->line("│   ├── Оброблено: {$this->settlementsProcessed}");
        $this->line("│   ├── Створено: {$this->settlementsCreated}");
        $this->line("│   └── Оновлено: {$this->settlementsUpdated}");
        $this->line("└── Відділення:");
        $this->line("    ├── Оброблено: {$this->warehousesProcessed}");
        $this->line("    ├── Створено: {$this->warehousesCreated}");
        $this->line("    └── Оновлено: {$this->warehousesUpdated}");
        $this->newLine();
        $this->info('✅ Синхронізація завершена успішно!');
    }
}
