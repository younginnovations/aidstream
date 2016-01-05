<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class ActivityBaseRequest
 * common validation rules and messages
 * @package App\Core\V201\Requests\Activity
 */
class ActivityBaseRequest extends Request
{
    function __construct()
    {
        Validator::extendImplicit(
            'unique_lang',
            function ($attribute, $value, $parameters, $validator) {
                $languages = [];
                $messages  = [];
                $check     = true;
                foreach ($value as $narrativeIndex => $narrative) {
                    $language                                                         = $narrative['language'];
                    $messages[sprintf('%s.%s.language', $attribute, $narrativeIndex)] = 'Languages should be unique.';
                    if (in_array($language, $languages)) {
                        $check = false;
                    }
                    $languages[] = $language;
                }
                $check ?: $validator->messages()->merge($messages);

                return true;
            }
        );
    }

    /**
     * returns rules for narrative
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForNarrative($formFields, $formBase)
    {
        $rules                                     = [];
        $rules[sprintf('%s.narrative', $formBase)] = 'unique_lang';
        foreach ($formFields as $narrativeIndex => $narrative) {
            $rules[sprintf('%s.narrative.%s.narrative', $formBase, $narrativeIndex)] = 'required';
        }

        return $rules;
    }

    /**
     * returns messages for narrative
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForNarrative($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $narrativeIndex => $narrative) {
            $messages[sprintf('%s.narrative.%s.narrative.required', $formBase, $narrativeIndex)] = 'Narrative text is required';
        }

        return $messages;
    }
}
