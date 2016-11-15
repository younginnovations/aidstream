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
                $mimes    = ['application/excel', 'application/vnd.ms-excel', 'application/msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'];
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
        $messages['activity.required']      = 'The Activity file is required';
        $messages['activity.activity_file'] = 'The activity must be a file of type: csv.';

        return $messages;
    }
}
