<?php

namespace App\Console\Commands;

use App\Services\Bybit\BybitOrderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OrderProcessing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bybit:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запуск обработки ордера';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            BybitOrderService::orderProcessing();
        } catch (\Exception $e) {
            Log::critical('Критическая ошибка: ' . $e->getMessage());
        }
    }
}
