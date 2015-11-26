<?php namespace App\Core\V201\Requests\Activity;

use Illuminate\Support\Facades\Validator;

/**
 * Class UploadTransaction
 * @package App\Core\V201\Requests\Activity
 */
class UploadTransaction extends ActivityBaseRequest
{
    function __construct()
    {
        Validator::extend(
            'transaction_file',
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
        $rules['transaction'] = 'required|transaction_file';

        return $rules;
    }

    /**
     * prepare error message
     * @return mixed
     */
    public function messages()
    {
        $messages['transaction.transaction_file'] = 'The transaction must be a file of type: csv, xls, xlsx.';

        return $messages;
    }
}
