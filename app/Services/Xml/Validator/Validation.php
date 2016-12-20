<?php namespace App\Services\Xml\Validator;

use App\Services\Xml\Validator\Traits\RegistersValidationRules;
use Illuminate\Validation\Factory;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Validation
 * @package App\Services\Xml\Validator
 */
class Validation extends Factory
{
    use RegistersValidationRules;

    /**
     * @var
     */
    protected $validator;

    /**
     * Element URIs.
     *
     * @var array
     */
    protected $elementLinks = [
        'Other Identifier'           => 'activity.other-identifier.index',
        'Title'                      => 'activity.title.index',
        'Description'                => 'activity.description.index',
        'Activity Status'            => 'activity.activity-status.index',
        'Activity Date'              => 'activity.activity-date.index',
        'Contact Info'               => 'activity.contact-info.index',
        'Activity Scope'             => 'activity.activity-scope.index',
        'Participating Organization' => 'activity.participating-organization.index',
        'Recipient Country'          => 'activity.recipient-country.index',
        'Recipient Region'           => 'activity.recipient-region.index',
        'Location'                   => 'activity.location.index',
        'Sector'                     => 'activity.sector.index',
        'Country Budget Items'       => 'activity.country-budget-items.index',
        'Humanitarian Scope'         => 'activity.humanitarian-scope.index',
        'Policy Marker'              => 'activity.policy-marker.index',
        'Collaboration Type'         => 'activity.collaboration_type.index',
        'Default Flow Type'          => 'activity.default-flow-type.index',
        'Default Finance Type'       => 'activity.default-finance-type.index',
        'Default Aid Type'           => 'activity.default-aid-type.index',
        'Default Tied Status'        => 'activity.default-tied-status.index',
        'Budget'                     => 'activity.budget.index',
        'Planned Disbursement'       => 'activity.planned-disbursement.index',
        'Capital Spend'              => 'activity.capital-spend.index',
        'Related Activity'           => 'activity.related-activity.index',
        'Legacy Data'                => 'activity.legacy-data.index',
        'Conditions'                 => 'activity.condition.index',
        'Document Links'             => 'activity.document-link.edit',
        'Transaction'                => 'activity.transaction.edit',
        'Results'                    => 'activity.result.edit',
    ];

    /**
     * Validation constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct($translator);
        $this->registerValidationRules();
    }

    /**
     * Initialize the validator object.
     *
     * @param $activity
     * @param $rules
     * @param $messages
     * @return $this
     */
    public function initialize($activity, $rules, $messages)
    {
        $this->validator = $this->make($activity, $rules, $messages);

        return $this;
    }

    /**
     * Run the validator and check if it passes.
     *
     * @return $this
     */
    public function passes()
    {
        $this->validator->passes();

        return $this;
    }

    /**
     * Get the unique validation errors.
     *
     * @param      $activityId
     * @param bool $shouldBeUnique
     * @return array
     */
    public function withErrors($activityId, $shouldBeUnique = false)
    {
        $errors = [];

        foreach ($this->errors() as $index => $error) {
            $element                  = $this->parseErrors($index);
            $errors[$element][$index] = getVal($error, [0], '');
        }

        $errors = $this->embedLinks($activityId, $errors, $shouldBeUnique);

        return $errors;
    }

    /**
     * Parse the errors from the validator.
     *
     * @param $index
     * @return string
     */
    protected function parseErrors($index)
    {
        $element = getVal(explode('.', $index), [0], '');

        return ucwords(((str_replace('_', ' ', $element))));
    }

    /**
     * Get the Validator error messages.
     *
     * @return mixed
     */
    protected function errors()
    {
        return $this->validator->errors()->getMessages();
    }

    /**
     * Returns rules for narrative.
     *
     * @param $elementNarrative
     * @param $elementName
     * @return array
     */
    public function getRulesForNarrative($elementNarrative, $elementName)
    {
        $rules                                          = [];
        $rules[sprintf('%s.narrative', $elementName)][] = 'unique_lang';
        $rules[sprintf('%s.narrative', $elementName)][] = 'unique_default_lang';

        foreach ($elementNarrative as $narrativeIndex => $narrative) {
            $rules[sprintf('%s.narrative.%s.narrative', $elementName, $narrativeIndex)][] = 'required_with_language';
        }

        return $rules;
    }

    /**
     * Returns messages for narrative.
     *
     * @param $elementNarrative
     * @param $elementName
     * @return array
     */
    public function getMessagesForNarrative($elementNarrative, $elementName)
    {
        $messages                                                    = [];
        $messages[sprintf('%s.narrative.unique_lang', $elementName)] = trans('validation.unique', ['attribute' => trans('elementForm.language')]);

        foreach ($elementNarrative as $narrativeIndex => $narrative) {
            $messages[sprintf('%s.narrative.%s.narrative.required_with_language', $elementName, $narrativeIndex)] = trans(
                'validation.required_with',
                [
                    'attribute' => trans('elementForm.narrative'),
                    'values'    => trans('elementForm.language')
                ]
            );
        }

        return $messages;
    }

    /**
     * Returns rules for narrative if narrative is required.
     *
     * @param $elementNarrative
     * @param $elementName
     * @return array
     */
    public function getRulesForRequiredNarrative($elementNarrative, $elementName)
    {
        $rules                                          = [];
        $rules[sprintf('%s.narrative', $elementName)][] = 'unique_lang';
        $rules[sprintf('%s.narrative', $elementName)][] = 'unique_default_lang';

        foreach ($elementNarrative as $narrativeIndex => $narrative) {
            if (boolval($narrative['language'])) {
                $rules[sprintf('%s.narrative.%s.narrative', $elementName, $narrativeIndex)] = 'required_with:' . sprintf(
                        '%s.narrative.%s.language',
                        $elementName,
                        $narrativeIndex
                    );
            } else {
                $rules[sprintf('%s.narrative.%s.narrative', $elementName, $narrativeIndex)] = 'required';
            }
        }

        return $rules;
    }

    /**
     * Get message for narrative.
     *
     * @param $elementNarrative
     * @param $elementName
     * @return array
     */
    public function getMessagesForRequiredNarrative($elementNarrative, $elementName)
    {
        $messages                                                    = [];
        $messages[sprintf('%s.narrative.unique_lang', $elementName)] = trans('validation.unique', ['attribute' => trans('elementForm.language')]);

        foreach ($elementNarrative as $narrativeIndex => $narrative) {
            if (boolval($narrative['language'])) {
                $messages[sprintf(
                    '%s.narrative.%s.narrative.required_with',
                    $elementName,
                    $narrativeIndex
                )] = trans('validation.required_with', ['attribute' => trans('elementForm.narrative'), 'values' => trans('elementForm.language')]);
            } else {
                $messages[sprintf(
                    '%s.narrative.%s.narrative.required',
                    $elementName,
                    $narrativeIndex
                )] = trans('validation.required', ['attribute' => trans('elementForm.narrative')]);
            }
        }

        return $messages;
    }

    /**
     * Get rules for transaction's sector element.
     *
     * @param $sector
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForTransactionSectorNarrative($sector, $formFields, $formBase)
    {
        $rules                                       = [];
        $rules[sprintf('%s.narrative', $formBase)][] = 'unique_lang';
        $rules[sprintf('%s.narrative', $formBase)][] = 'unique_default_lang';
        foreach ($formFields as $narrativeIndex => $narrative) {
            $rules[sprintf('%s.narrative.%s.narrative', $formBase, $narrativeIndex)][] = 'required_with_language';
            if ($narrative['narrative'] != "") {
                $rules[sprintf('%s.sector_vocabulary', $formBase)] = 'required_with:' . sprintf('%s.narrative.%s.narrative', $formBase, $narrativeIndex);
                if ($sector['sector_vocabulary'] == 1 || $sector['sector_vocabulary'] == 2) {
                    if ($sector['sector_vocabulary'] == 1) {
                        $rules[sprintf('%s.sector_code', $formBase)] = 'required_with:' . sprintf('%s.narrative.%s.narrative', $formBase, $narrativeIndex);
                    }
                    if ($sector['sector_vocabulary'] == 2) {
                        $rules[sprintf('%s.sector_category_code', $formBase)] = 'required_with:' . sprintf('%s.narrative.%s.narrative', $formBase, $narrativeIndex);
                    }
                } else {
                    $rules[sprintf('%s.sector_text', $formBase)] = 'required_with:' . sprintf('%s.narrative.%s.narrative', $formBase, $narrativeIndex);
                }
            }
        }

        return $rules;
    }

    /**
     * Get messages for transaction's sector element.
     *
     * @param $sector
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForTransactionSectorNarrative($sector, $formFields, $formBase)
    {
        $messages                                                 = [];
        $messages[sprintf('%s.narrative.unique_lang', $formBase)] = trans('validation.unique', ['attribute' => trans('elementForm.language')]);
        foreach ($formFields as $narrativeIndex => $narrative) {
            $messages[sprintf(
                '%s.narrative.%s.narrative.required_with_language',
                $formBase,
                $narrativeIndex
            )] = trans('validation.required_with', ['attribute' => trans('elementForm.narrative'), 'values' => trans('elementForm.language')]);

            if ($narrative['narrative'] != "") {
                $messages[sprintf('%s.sector_vocabulary.required_with', $formBase)] = trans(
                    'validation.required_with',
                    ['attribute' => trans('elementForm.sector_vocabulary'), 'values' => trans('elementForm.narrative')]
                );
                if ($sector['sector_vocabulary'] == 1 || $sector['sector_vocabulary'] == 2) {
                    if ($sector['sector_vocabulary'] == 1) {
                        $messages[sprintf('%s.sector_code.required_with', $formBase)] = trans(
                            'validation.required_with',
                            ['attribute' => trans('elementForm.sector_code'), 'values' => trans('elementForm.narrative')]
                        );
                    }
                    if ($sector['sector_vocabulary'] == 2) {
                        $messages[sprintf('%s.sector_category_code.required_with', $formBase)] = trans(
                            'validation.required_with',
                            ['attribute' => trans('elementForm.sector_code'), 'values' => trans('elementForm.narrative')]
                        );
                    }
                } else {
                    $messages[sprintf('%s.sector_text.required_with', $formBase)] = trans(
                        'elementForm.required_with',
                        ['attribute' => trans('elementForm.sector_code'), 'values' => trans('elementForm.narrative')]
                    );
                }
            }
        }

        return $messages;
    }

    /**
     * Returns rules for narrative.
     *
     * @param      $formFields
     * @param      $formBase
     * @return array
     */
    public function getRulesForResultNarrative($formFields, $formBase)
    {
        $rules                                       = [];
        $rules[sprintf('%s.narrative', $formBase)][] = 'unique_lang';
        $rules[sprintf('%s.narrative', $formBase)][] = 'unique_default_lang';
        foreach ($formFields as $narrativeIndex => $narrative) {
            $rules[sprintf('%s.narrative.%s.narrative', $formBase, $narrativeIndex)][] = 'required';
        }

        return $rules;
    }

    /**
     * Returns rules for period start form.
     *
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForPeriodStart($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $periodStartKey => $periodStartVal) {
            $rules[$formBase . '.period_start.' . $periodStartKey . '.date'] = 'required|date';
        }

        return $rules;
    }

    /**
     * Returns messages for period start form.
     *
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForPeriodStart($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $periodStartKey => $periodStartVal) {
            $messages[$formBase . '.period_start.' . $periodStartKey . '.date.required'] = trans('validation.required', trans('elementForm.period_start'));
            $messages[$formBase . '.period_end.' . $periodStartKey . '.date.date']       = trans('validation.date', trans('elementForm.period_start'));
        }

        return $messages;
    }

    /**
     * Returns rules for period end form.
     *
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForPeriodEnd($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $periodEndKey => $periodEndVal) {
            $rules[$formBase . '.period_end.' . $periodEndKey . '.date'][] = 'required';
            $rules[$formBase . '.period_end.' . $periodEndKey . '.date'][] = 'date';
            $rules[$formBase . '.period_end.' . $periodEndKey . '.date'][] = sprintf(
                'after:%s',
                $formBase . '.period_start.' . $periodEndKey . '.date'
            );
        }

        return $rules;
    }

    /**
     * Returns messages for period end form.
     *
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForPeriodEnd($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $periodEndKey => $periodEndVal) {
            $messages[$formBase . '.period_end.' . $periodEndKey . '.date.required'] = trans('validation.required', ['attribute' => trans('elementForm.period_end')]);
            $messages[$formBase . '.period_end.' . $periodEndKey . '.date.date']     = trans('validation.date', ['attribute' => trans('elementForm.period_end')]);
            $messages[$formBase . '.period_end.' . $periodEndKey . '.date.after']    = trans(
                'validation.after',
                ['attribute' => trans('elementForm.period_end'), 'date' => trans('elementForm.period_start')]
            );
        }

        return $messages;
    }

    /**
     * Get distinct errors from a list of errors.
     * @param $errors
     * @return mixed
     */
    protected function getDistinctErrors($errors)
    {
        return array_unique($errors);
    }

    /**
     * Embed Links to the respective elements for the error messages.
     *
     * @param       $activityId
     * @param array $errors
     * @param bool  $shouldBeUnique
     * @return array
     */
    protected function embedLinks($activityId, array $errors, $shouldBeUnique = false)
    {
        $links = [];

        foreach ($errors as $element => $error) {
            $index = 0;
            if (!in_array($element, ['Transaction', 'Results', 'Document Links'])) {
                if ($shouldBeUnique) {
                    $error = $this->getDistinctErrors($error);
                }

                foreach ($error as $errorText) {
                    $link                               = route($this->elementLinks[$element], [$activityId]);
                    $links[$element][$index]['link']    = $link;
                    $links[$element][$index]['message'] = $errorText;
                    $index ++;
                }
            } else {
                $links[$element] = $this->getErrors($element, $error, $activityId);

            }
        }

        return $links;
    }

    /**
     * Get the errors in the uploaded Xml File.
     *
     * @param $element
     * @param $elementErrors
     * @param $activityId
     * @return array
     */
    protected function getErrors($element, $elementErrors, $activityId)
    {
        $errors = [];

        $index = 0;
        foreach ($elementErrors as $elementIndex => $error) {
            $elementName               = strtolower($this->parseErrors($elementIndex));
            $errorIndex                = (int) getVal(explode('.', $elementIndex), [1]);
            $id                        = getVal($this->validator->getData(), [$elementName, $errorIndex, 'id']);
            $errors[$index]['link']    = route($this->elementLinks[$element], [$activityId, $id]);
            $errors[$index]['message'] = $error;
            $index ++;
        }

        return $errors;
    }
}
