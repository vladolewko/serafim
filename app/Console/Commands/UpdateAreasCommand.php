<?php

namespace App\Console\Commands;

use App\Events\UpdateAreasAndDistricts;
use Illuminate\Console\Command;

class UpdateAreasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nova-post:update-areas {--force : Force update even if data is recent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Nova Post areas and districts from API';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Запуск оновлення областей та районів Nova Post...');
        
        $forceUpdate = $this->option('force');
        
        if ($forceUpdate) {
            $this->warn('Примусове оновлення активовано');
        }
        
        // Диспатчимо івент
        UpdateAreasAndDistricts::dispatch($forceUpdate);
        
        $this->info('Команда оновлення відправлена в чергу');
        
        return Command::SUCCESS;
    }
}