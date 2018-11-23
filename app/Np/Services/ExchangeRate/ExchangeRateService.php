<?php namespace App\Np\Services\ExchangeRate;


use App\Http\Controllers\Complete\Traits\HistoryExchangeRates;
use App\Np\Services\Traits\ExchangeRateCalculator;
use App\Models\HistoricalExchangeRate as RateModel;
use App\Models\Settings;

/**
 * Class ExchangeRateService
 * @package App\Np\Services\ExchangeRate
 */
class ExchangeRateService
{
    use HistoryExchangeRates, ExchangeRateCalculator;
    /**
     * @var RateModel
     */
    private $rateModel;
    /**
     * @var Settings
     */
    private $settings;

    /**
     * Divisor when the number format is K (thousand).
     */
    const K_DIVISOR = 1000;

    /**
     * Divisor when the number format is M (million).
     */
    const M_DIVISOR = 1000000;

    /**
     * Divisor when the number format is B (billion).
     */
    const B_DIVISOR = 1000000000;

    /**
     * Divisor when the number format is B (billion).
     */
    const T_DIVISOR = 1000000000000;

    /**
     * ExchangeRateService constructor.
     * @param RateModel $rateModel
     * @param Settings  $settings
     */
    public function __construct(RateModel $rateModel, Settings $settings)
    {
        $this->rateModel = $rateModel;
        $this->settings  = $settings;
    }

    /**
     * Converts the amount present in budget of all activities into USD.
     * Fetches the exchange rate if not available.
     * Calculates the total budget and max budget in an activity into USD.
     *
     * @param $activities
     * @return array
     */
    public function budgetDetails($activities)
    {
        $totalAmount      = 0;
        $totalAmountArray = [];

        foreach ($activities as $activity) {
            $totalAmountInActivity = 0;
            $budget                = $activity->budget;
            $default_currency      = $this->getDefaultCurrency($activity);

            if ($budget) {
                foreach ($budget as $key => $value) {
                    $date     = getVal($value, ['value', 0, 'value_date']);
                    $amount   = getVal($value, ['value', 0, 'amount']);
                    $currency = getVal($value, ['value', 0, 'currency']);

                    if ($amount != "") {
                        $currency = (!$currency) ? $default_currency : $currency;
                        $this->exchangeRate($date);
                        $exchangedAmount       = $this->calculate($date, $currency, $amount);
                        $totalAmountInActivity = $totalAmountInActivity + $exchangedAmount;
                    }
                }
            }

            $totalAmount += $totalAmountInActivity;
            $totalAmountArray[] = $totalAmountInActivity;
        }

        $totalBudgetPlaceValue = $this->numberFormat(strlen(round($totalAmount)));
        $totalBudget           = $this->placeValueAmount($totalAmount, $totalBudgetPlaceValue);
        $maxBudgetInString     = $this->formatAmountIntoWord(max($totalAmountArray));

        return ['totalBudget' => $totalBudget, 'totalBudgetPlaceValue' => $totalBudgetPlaceValue, 'maxBudget' => $maxBudgetInString];
    }

    /**
     * If the field has no currency, then the default currency of activity is used.
     * If the activity has no default currency, then the default currency of settings is used.
     *
     * @param $activity
     * @return string
     */
    public function getDefaultCurrency($activity)
    {
        $defaultCurrency = "USD";

        if ($activityDefaultCurrency = $activity->default_field_values) {
            $defaultCurrency = getVal($activityDefaultCurrency, [0, 'default_currency']);
        }

        if ($defaultCurrency == "") {
            $settings = $this->settings->where('organization_id', session('org_id'))->first();
            (($settingsDefaultCurrency = $settings->default_currency) == "") ?: $defaultCurrency = $settingsDefaultCurrency;
        }

        return $defaultCurrency;
    }

    /**
     * Checks if the exchange rate is already available in database.
     * If exchange rate is not available then the exchange rate is fetched using api.
     * If the date is in future, the exchange rate of present date is fetched.
     *
     * @param $date
     * @return $this
     */
    protected function exchangeRate($date)
    {
        $allDates      = $this->rateModel->select('date')->get()->toArray();
        $newDate       = array_values(array_diff([$date], array_flatten($allDates)));
        $exchangeRates = [];

        if (!empty($newDate)) {
            if (($date = getVal($newDate, [0], date('Y-m-d'))) < date('Y-m-d')) {
                $exchangeRates[] = $this->clean(json_decode($this->curl($date), true), $date);
                $this->storeExchangeRates($exchangeRates);
            } else {
                $this->storePresentDateRate($allDates);
            }
        }

        return $this;
    }

    /**
     * Stores the exchange rate of Present date.
     *
     * @param $allDates
     * @return bool
     */
    protected function storePresentDateRate($allDates)
    {
        if (!in_array(date('Y-m-d'), array_flatten($allDates))) {
            $exchangeRate = $this->clean(json_decode($this->curl(date('Y-m-d')), true), date('Y-m-d'));

            $this->storeExchangeRates([$exchangeRate]);
        }

        return false;
    }

    /**
     * Returns the amount with numberFormat.
     *
     * @param $amount
     * @return string
     */
    protected function formatAmountIntoWord($amount)
    {
        $numberFormat = $this->numberFormat(strlen(round($amount)));

        return sprintf('$%s%s', $this->placeValueAmount($amount, $numberFormat), $numberFormat);
    }

    /**
     * Returns place value formatted number.
     *
     * @param $amount
     * @param $numberFormat
     * @return string
     */
    protected function placeValueAmount($amount, $numberFormat)
    {
        $divisor = ($numberFormat) ? constant(sprintf('self::%s_DIVISOR', $numberFormat)) : 1;
        $amount  = round(($amount / $divisor), 1);

        return $amount;
    }

    /**
     * Returns numberFormat based on the count of digit of the amount.
     *
     * @param $count
     * @return string
     */
    protected function numberFormat($count)
    {
        if ($count > 3 && $count <= 6) {
            return "K";
        }

        if ($count > 6 && $count <= 9) {
            return "M";
        }

        if ($count > 9 && $count <= 12) {
            return "B";
        }

        if ($count > 9 && $count <= 12) {
            return "T";
        }
    }
}
