<?php

namespace App\Listeners;

use App\Events\UpdateAreasAndDistricts;
use App\Models\Area;
use App\Services\NovaPostService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UpdateAreasAndDistrictsListener implements ShouldQueue
{
    use InteractsWithQueue;

    protected $novaPostService;

    /**
     * Create the event listener.
     */
    public function __construct(NovaPostService $novaPostService)
    {
        $this->novaPostService = $novaPostService;
    }

    /**
     * Handle the event.
     */
    public function handle(UpdateAreasAndDistricts $event): void
    {
        $cacheKey = 'nova_post_areas_last_updated';
        $lastUpdated = Cache::get($cacheKey);
        
        // Перевіряємо чи потрібно оновлювати (якщо минуло менше ніж 24 години і не примусове оновлення)
        if (!$event->forceUpdate && $lastUpdated && Carbon::parse($lastUpdated)->diffInHours(now()) < 24) {
            Log::info('Пропуск оновлення областей - дані ще актуальні', [
                'last_updated' => $lastUpdated
            ]);
            return;
        }

        try {
            Log::info('Початок оновлення областей та районів');
            
            $this->novaPostService->updateAreasAndDistricts();
            
            // Зберігаємо час останнього оновлення
            Cache::put($cacheKey, now()->toDateTimeString(), now()->addDays(7));
            
            Log::info('Оновлення областей та районів завершено успішно', [
                'areas_count' => Area::count(),
                'districts_count' => \App\Models\District::count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Помилка оновлення областей та районів', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Повторна спроба через 1 годину у разі помилки
            $this->release(3600);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(UpdateAreasAndDistricts $event, $exception): void
    {
        Log::error('Критична помилка оновлення областей та районів', [
            'error' => $exception->getMessage(),
            'force_update' => $event->forceUpdate
        ]);
    }
}