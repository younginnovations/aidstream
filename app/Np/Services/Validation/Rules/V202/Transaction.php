<?php namespace App\Np\Services\Validation\Rules\V202;

use App\Core\V201\Traits\GetCodes;

/**
 * Class Transaction
 * @package App\Np\Services\Validation\Rules\V202
 */
class Transaction
{
    use GetCodes;

    /**
     * @var mixed
     */
    protected $transaction;

    /**
     * Transaction constructor.
     */
    public function __construct()
    {
        $this->transaction = key(request()->except(['_token', 'ids']));
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            $this->transaction . '.*.date'      => 'required|date',
            $this->transaction . '.*.amount'    => 'required|numeric',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            $this->transaction . '.*.date.required'      => trans('validation.required', ['attribute' => trans('lite/elementForm.date')]),
            $this->transaction . '.*.date.date'          => trans('validation.date', ['attribute' => trans('lite/elementForm.date')]),
            $this->transaction . '.*.amount.required'    => trans('validation.required', ['attribute' => trans('lite/elementForm.amount')]),
            $this->transaction . '.*.amount.numeric'    => trans('validation.numeric', ['attribute' => trans('lite/elementForm.amount')]),
        ];
    }
}
