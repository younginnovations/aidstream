<?php namespace App\Lite\Services\Traits;


/**
 * Class ExchangeRateCalculator
 * @package App\Lite\Services\Traits
 */
trait ExchangeRateCalculator
{
    /**
     * Convert the given amount into the exchange rate of provided rate.
     * If the currency is USD, then the provided amount in returned.
     *
     * @param        $date
     * @param        $fromCurrency
     * @param        $amount
     * @return float|int
     */
    public function calculate($date, $fromCurrency, $amount)
    {
        $rates = $this->getExchangeRates($date);

        if ($fromCurrency != "USD") {
            return $this->calculateInUSD($rates[$fromCurrency], $amount);
        }

        return $amount;
    }

    /**
     * Returns the exchange rate of provided date.
     * If the date is future, then the exchange rate of present date is returned.
     *
     * @param $date
     * @return null
     */
    public function getExchangeRates($date)
    {
        if ($date > date('Y-m-d')) {
            $date = date('Y-m-d');
        }
        $rates = $this->rateModel->where('date', $date)->first();

        return ($rates) ? $rates->exchange_rates : null;
    }

    /**
     * Converts the given amount in base of rate.
     *
     * @param $rate
     * @param $amount
     * @return float|int
     */
    public function calculateInUSD($rate, $amount)
    {
        return $amount / $rate;
    }
}
