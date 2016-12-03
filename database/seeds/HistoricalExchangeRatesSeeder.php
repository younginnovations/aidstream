<?php

use Illuminate\Database\Seeder;

class HistoricalExchangeRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dates = $this->read(storage_path('exchangeRates.json'));
        $budgetValueDates = $this->read(storage_path('budgetExchangeRates.json'));

        $this->seed($dates);

        $this->seed($budgetValueDates);
    }

    protected function read($filename)
    {
        return json_decode(file_get_contents($filename), true);
    }

    protected function seed($dates)
    {
        $dbModel = app()->make(\App\Models\HistoricalExchangeRate::class);

        foreach ($dates as $index => $value) {
            if ($value) {
                $date = array_first(array_keys($value), function () {return true;});
                $exchangeRates = array_first(array_values($value), function () {return true;});

                $exchangeRate = $dbModel->newInstance(['date' => $date, 'exchange_rates' => $exchangeRates]);

                $exchangeRate->save();
            }
        }
    }
}
