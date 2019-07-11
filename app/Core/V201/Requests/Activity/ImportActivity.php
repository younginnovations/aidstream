<?php namespace App\Core\V201\Requests\Activity;

use Illuminate\Support\Facades\Validator;

/**
 * Class ImportActivity
 * @package App\Core\V201\Requests\Activity
 */
class ImportActivity extends ActivityBaseRequest
{
    function __construct()
    {
        Validator::extend(
            'activity_file',
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
        $rules             = [];
        $rules['activity'] = 'required|activity_file';

        return $rules;
    }

    /**
     * prepare error message
     * @return mixed
     */
    public function messages()
    {
        $messages['activity.required']      = trans('validation.required', ['attribute' => trans('elementForm.activity_file')]);
        $messages['activity.activity_file'] = trans('validation.mimes', ['attribute' => trans('global.activity'), 'values' => 'csv']);

        return $messages;
    }
}
