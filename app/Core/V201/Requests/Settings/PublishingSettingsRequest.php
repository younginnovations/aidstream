<?php namespace App\Core\V201\Requests\Settings;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Validator;

class PublishingSettingsRequest extends Request
{
    public function __construct()
    {
        Validator::extendImplicit(
            'include_operators',
            function ($attribute, $value, $parameters, $validator) {
                $value = $this->get('publisher_id');

                return preg_match('/^[a-zA-Z0-9\-_]+$/', $value);
            }
        );
    }

    public function rules()
    {
        $rules        = [];
        $ids          = \App\Models\Settings::select('registry_info')->where('organization_id', '<>', session('org_id'))->get()->toArray();
        $publisherIds = [];
        foreach ($ids as $id) {
            $publisherIds[] = getVal($id, ['registry_info', 0, 'publisher_id']);
        }
        $rules['publisher_id'] = sprintf('include_operators|not_in:%s', implode(",", $publisherIds));

        return $rules;

    }

    public function messages()
    {
        $messages                                   = [];
        $messages['publisher_id.include_operators'] = trans('validation.alpha_dash', ['attribute' => trans('setting.publisher_id')]);
        $messages['publisher_id.not_in']            = trans('validation.unique_validation', ['attribute' => trans('setting.publisher_id')]);

        return $messages;
    }
}