<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestWayForPayCallback extends Command
{
    protected $signature = 'test:wayforpay-callback';
    protected $description = 'Test WayForPay callback endpoint';

    public function handle()
    {
        $url = config('services.wayforpay.url') . '/api/test/callback';

        $this->info("Testing callback URL: {$url}");

        // GET тест
        try {
            $response = Http::get($url);
            $this->info("GET Response: " . $response->status());
            $this->info("GET Body: " . $response->body());
        } catch (\Exception $e) {
            $this->error("GET Error: " . $e->getMessage());
        }

        // POST тест
        try {
            $testData = [
                'orderReference' => 'TEST_' . time(),
                'transactionStatus' => 'Approved',
                'amount' => '100.00',
                'currency' => 'UAH'
            ];

            $response = Http::post($url, $testData);
            $this->info("POST Response: " . $response->status());
            $this->info("POST Body: " . $response->body());
        } catch (\Exception $e) {
            $this->error("POST Error: " . $e->getMessage());
        }

        return 0;
    }
}
