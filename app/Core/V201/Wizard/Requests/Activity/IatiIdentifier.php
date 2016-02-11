<?php namespace App\Core\V201\Wizard\Requests\Activity;

use App\Core\V201\Repositories\Activity\IatiIdentifierRepository;
use App\Http\Requests\Request;

class IatiIdentifier extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        $iatiIdentifierRepository = app(IatiIdentifierRepository::class);
        $rules                    = [];
        $activityIdentifiers      = [];
        $identifiers              = $iatiIdentifierRepository->getActivityIdentifiers();

        foreach ($identifiers as $identifier) {
            $activityIdentifiers[] = $identifier->activity_identifier;
        }

        $activityIdentifier           = implode(',', $activityIdentifiers);
        $rules['activity_identifier'] = 'required|not_in:' . $activityIdentifier;

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
