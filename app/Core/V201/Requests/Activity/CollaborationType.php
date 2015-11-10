<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;

/**
 * Class CollaborationType
 * @package App\Core\V201\Requests\Activity
 */
class CollaborationType extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * get the validation rules that apply to the activity request.
     * @return array
     */
    public function rules()
    {
        $rules['collaboration_type'] = 'required';

        return $rules;
    }

}
