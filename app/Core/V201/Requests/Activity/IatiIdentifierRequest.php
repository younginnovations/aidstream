<?php namespace App\Core\V201\Requests\Activity;

use App\Core\V201\Repositories\Activity\IatiIdentifierRepository;

/**
 * Class IatiIdentifierRequest
 * @package App\Core\V201\Requests\Activity
 */
class IatiIdentifierRequest extends ActivityBaseRequest
{
    /**
     * @var IatiIdentifierRepository
     */
    protected $iatiIdentifierRepository;

    /**
     * IatiIdentifierRequest constructor.
     * @param IatiIdentifierRepository $iatiIdentifierRepository
     */
    public function __construct(IatiIdentifierRepository $iatiIdentifierRepository)
    {
        parent::__construct();
        $this->iatiIdentifierRepository = $iatiIdentifierRepository;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $activityIdentifiers = [];
        $activityId          = $this->get('id');
        $identifiers         = ($activityId) ? $this->iatiIdentifierRepository->getActivityIdentifiersForOrganizationExcept(
            $activityId
        ) : $this->iatiIdentifierRepository->getIdentifiersForOrganization();

        foreach ($identifiers as $identifier) {
            $activityIdentifiers[] = $identifier->activity_identifier;
        }

        $activityIdentifier            = implode(',', $activityIdentifiers);
        $rules                         = [];
        $rules['activity_identifier']  = 'required|exclude_operators|not_in:' . $activityIdentifier;
        $rules['iati_identifier_text'] = 'required';

        return $rules;
    }

    /**
     * prepare error message
     */
    public function messages()
    {
        $messages                                 = [];
        $messages['activity_identifier.not_in']   = trans('validation.code_list', ['attribute' => trans('elementForm.activity_identifier')]);
        $messages['activity_identifier.required'] = trans('validation.required', ['attribute' => trans('elementForm.activity_identifier')]);

        return $messages;
    }
}
