<?php namespace App\Http\Controllers\Complete\Traits;

use App\Models\Activity\Transaction;
use App\Models\HistoricalExchangeRate;

/**
 * Class HistoryExchangeRates
 * @package App\Http\Controllers\Complete\Activity\Traits
 */
trait HistoryExchangeRates
{
    /**
     * Starts checking and creating New Exchange Rates
     *
     * @param $activity
     * @return bool|\Exception
     */
    protected function exchangeRates($activity)
    {
        try {
            $transactionModel = app()->make(Transaction::class);

            $transactions = $transactionModel->where('activity_id', $activity->id)->get()->toArray();

            $dates = $this->getDates($activity->toArray(), $transactions);

            $this->getExchangeRatesForNewDates($dates);

            return true;
        } catch (\Exception $exception) {
            return $exception;
        }

    }

    /**
     * Provides Dates for Published Data
     *
     * @param $activity
     * @param $transactions
     * @return array
     */
    protected function getDates(array $activity, array $transactions)
    {
        $dates = [];

        if (!is_array($activity)) {
            $activity = (array) $activity;
        }

        foreach (getVal($activity, ['budget'], []) as $budget) {
            $dates[] = getVal($budget, ['value', 0, 'value_date'], '');
        }

        foreach ($transactions as $transaction) {
            $dates[] = getVal($transaction, ['transaction', 'value', 0, 'date'], '');
        }

        return $dates;
    }

    /**
     * Provides dates that are not in Exchange Rate History table
     *
     * @param $dates
     * @return HistoryExchangeRates
     */
    protected function getExchangeRatesForNewDates(array $dates)
    {
        $exchangeRatesModel = app()->make(HistoricalExchangeRate::class);
        $allDates           = $exchangeRatesModel->select('date')->get()->toArray();

        $newDates = array_values(array_diff($dates, array_flatten($allDates)));

        return $this->newExchangeRates($newDates);
    }

    /**
     * Provides new exchange rates
     *
     * @param $newDates
     * @return HistoryExchangeRates
     */
    protected function newExchangeRates(array $newDates)
    {
        $exchangeRates = [];
        foreach ($newDates as $index => $newDate) {
            if ($newDate < date('Y-m-d')) {
                $exchangeRates[] = $this->clean(json_decode($this->curl($newDate), true), $newDate);
            }
        }

        return $this->storeExchangeRates($exchangeRates);
    }

    /**
     * Calls API for new dates
     *
     * @param $date
     * @return mixed
     */
    protected function curl($date)
    {
        $ch = curl_init('http://apilayer.net/api/historical' . '?access_key=' . env('EXCHANGE_RATE_API') . '&date=' . $date . '&format=1');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }


    /**
     * Provides only needed data from API response
     *
     * @param $json
     * @param $date
     * @return array
     * @throws \Exception
     */
    protected function clean($json, $date)
    {

        $rates = [];

        if (!$json) {
            $json = (array) $json;
        }

        if ($json['success']) {
            foreach (getVal($json, ['quotes'], []) as $key => $value) {
                $toCurrency = str_replace('USD', '', $key);

                if ($toCurrency !== '') {
                    $rates[$date][$toCurrency] = $value;
                }
            }
        } else {
            if ($json['error']['code'] == '104') {
                throw new \Exception("User has reached or exceeded his exchangeRates API request.");
            }
            $this->getExchangeRatesForNewDates((array) date('Y-m-d', strtotime($date . ' -1 day')));
        }

        return $rates;
    }

    /**
     * Transforms Exchange Rates to suitable form
     *
     * @param $exchangeRate
     * @return array
     */
    protected function transformExchangeRates($exchangeRate)
    {
        return [
            'date'           => array_first(
                array_keys($exchangeRate),
                function () {
                    return true;
                }
            ),
            'exchange_rates' => array_first(
                array_values($exchangeRate),
                function () {
                    return true;
                }
            )
        ];
    }

    /**
     * Stores Exchange Rates of new dates to the table
     *
     * @param $newExchangeRates
     * @return $this
     */
    protected function storeExchangeRates($newExchangeRates)
    {
        $exchangeRatesModel = app()->make(HistoricalExchangeRate::class);
        foreach ($newExchangeRates as $index => $rates) {
            if (!empty($rates)) {
                $exchangeRatesModel->create($this->transformExchangeRates($rates));
            }
        }

        return $this;
    }
}