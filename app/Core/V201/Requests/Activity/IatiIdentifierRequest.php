<?php namespace App\Core\V201\Requests\Activity;

use App\Core\V201\Repositories\Activity\IatiIdentifierRepository;

/**
 * Class IatiIdentifierRequest
 * @package App\Core\V201\Requests\Activity
 */
class IatiIdentifierRequest extends ActivityBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     * @param IatiIdentifierRepository $iatiIdentifierRepository
     * @return array
     */
    public function rules(IatiIdentifierRepository $iatiIdentifierRepository)
    {
        $activityIdentifiers = [];
        $activityId          = $this->request->get('id');
        $identifiers         = ($activityId) ? $iatiIdentifierRepository->getActivityIdentifiersExceptId($activityId) : $iatiIdentifierRepository->getActivityIdentifiers();

        foreach ($identifiers as $identifier) {
            $activityIdentifiers[] = $identifier->activity_identifier;
        }

        $activityIdentifier            = implode(',', $activityIdentifiers);
        $rules                         = [];
        $rules['activity_identifier']  = 'required|not_in:' . $activityIdentifier;
        $rules['iati_identifier_text'] = 'required';

        return $rules;
    }

    /**
     * prepare error message
     */
    public function messages()
    {
        $messages                               = [];
        $messages['activity_identifier.not_in'] = 'The selected activity identifier is invalid and must be unique.';

        return $messages;
    }
}
