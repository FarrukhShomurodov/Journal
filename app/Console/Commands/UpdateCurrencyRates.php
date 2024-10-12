<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-currency-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily update currency';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = now()->format('Y-m-d');
        $response = Http::get("https://cbu.uz/ru/arkhiv-kursov-valyut/json/all/{$date}/");

        if ($response->successful()) {
            $currencies = $response->json();

            foreach ($currencies as $currency) {
                Currency::updateOrCreate(
                    ['code' => $currency['Code']],
                    [
                        'name' => json_encode([
                            'uz' => $currency['CcyNm_UZ'],
                            'ru' => $currency['CcyNm_RU'],
                            'en' => $currency['CcyNm_EN'],
                        ]),
                        'ccy' => $currency['Ccy'],
                        'rate' => $currency['Rate'],
                        'relevance_date' => $date,
                    ]
                );
            }

            $this->info('Currency rates updated successfully!');
        } else {
            $this->error('Failed to fetch currency rates from API');
        }
    }
}
