<?php namespace App\Core\V201\Requests;

use App\Http\Requests\Request;
use App\Services\RegistrationAgencies;
use Illuminate\Support\Facades\Validator;
use App\Core\V201\Traits\GetCodes;

/**
 * Class RegisterOrganization
 * @package App\Core\V201\Requests
 */
class RegisterOrganization extends Request
{
    use GetCodes;

    /**
     * RegisterOrganization constructor.
     * @param RegistrationAgencies $regAgencies
     */
    public function __construct(RegistrationAgencies $regAgencies)
    {
        Validator::extend(
            'code_list',
            function ($attribute, $value, $parameters, $validator) {
                $listName = $parameters[1];
                $listType = $parameters[0];
                $codeList = $this->getCodes($listName, $listType);

                return in_array($value, $codeList);
            }
        );

        Validator::extend(
            'unique_org_identifier',
            function ($attribute, $value, $parameters, $validator) {
                $table    = 'organizations';
                $column   = 'reporting_org';
                $jsonPath = '{0,reporting_organization_identifier}';
                $builder  = \DB::table($table)->whereRaw(sprintf("%s #>> '{%s}' = ?", $column, str_replace('.', ',', $jsonPath)), [$value]);
                $count    = $builder->count();

                return $count === 0;
            }
        );
    }

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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $regAgencies = implode(',', array_keys(json_decode($this->get('agencies'), true)));
        $rules       = [];

        $rules['organization_name']                = 'required';
        $rules['organization_name_abbr']           = 'required|unique:organizations,user_identifier';
        $rules['organization_type']                = 'required|code_list:Organization,OrganizationType';
        $rules['organization_address']             = 'required';
        $rules['country']                          = 'required|code_list:Organization,Country';
        $rules['organization_registration_agency'] = 'required|in:' . $regAgencies;
        $rules['registration_number']              = 'required|alpha_num';
        $rules['organization_identifier']          = 'required|unique_org_identifier';

        return $rules;
    }

    /**
     * Get the Validation Error message
     * @return array
     */
    public function messages()
    {
        $messages = [];

        $messages['organization_name.required']                    = 'Organization Name is required.';
        $messages['organization_name_abbr.required']               = 'Organization Name Abbreviation is required.';
        $messages['organization_name_abbr.unique']                 = 'Organization Name Abbreviation has already been taken.';
        $messages['organization_type.required']                    = 'Organization Type is required.';
        $messages['organization_type.code_list']                   = 'Organization Type is not valid.';
        $messages['organization_address.required']                 = 'Address is required.';
        $messages['country.required']                              = 'Country is required.';
        $messages['country.code_list']                             = 'Country is not valid.';
        $messages['organization_registration_agency.required']     = 'Organisation Registration Agency is required.';
        $messages['organization_registration_agency.reg_agency']   = 'Organisation Registration Agency is not valid.';
        $messages['registration_number.required']                  = 'Registration Number is required.';
        $messages['registration_number.alpha_num']                 = 'Registration Number may only contain letters and numbers.';
        $messages['organization_identifier.required']              = 'IATI Organizational Identifier is required.';
        $messages['organization_identifier.unique_org_identifier'] = 'IATI Organizational Identifier is has already been taken.';

        return $messages;
    }

}
