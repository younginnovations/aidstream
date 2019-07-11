<?php namespace App\Core\V201\Requests\Activity\Result;

use Illuminate\Support\Facades\Validator;
use App\Core\V201\Requests\Activity\Result;

/**
 * Class ImportActivity
 * @package App\Core\V201\Requests\Activity
 */
class ImportResult extends Result
{
    function __construct()
    {
        Validator::extend(
            'result_file',
            function ($attribute, $value, $parameters, $validator) {
                $mimes    = ['application/excel', 'application/vnd.ms-excel', 'application/msexcel','text/csv'];
                $fileMime = $value->getClientMimeType();
                
                return in_array($fileMime, $mimes);
            }
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules           = [];
        $rules['result'] = 'required|result_file';

        return $rules;
    }

    /**
     * prepare error message
     * @return mixed
     */
    public function messages()
    {
        $messages['result.required']    = trans('validation.required', ['attribute' => 'Result File']);
        $messages['result.result_file'] = trans('validation.mimes', ['attribute' => 'result', 'values' => 'csv']);

        return $messages;
    }
}
