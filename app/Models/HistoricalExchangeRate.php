<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HistoricalExchangeRate
 * @package App\Models
 */
class HistoricalExchangeRate extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'historical_exchange_rates';

    /**
     * Fillable property for mass assignment.
     * @var array
     */
    protected $fillable = ['date', 'exchange_rates'];

    /**
     * Columns that are casted into json.
     * @var array
     */
    protected $casts = ['exchange_rates' => 'json'];
}
