<?php namespace App\Core\V203\Requests\Activity;

use App\Core\V201\Requests\Activity\Sector as V201Sector;

/**
 * Class Sector
 * @package App\Core\V202\Requests\Activity
 */
class Sector extends V201Sector
{

    /**
     * returns rules for sector
     * @param $formFields
     * @return array|mixed
     */
    public function getSectorsRules($formFields)
    {
        $rules = [];
        foreach ($formFields as $sectorIndex => $sector) {
            $sectorForm                                          = sprintf('sector.%s', $sectorIndex);
            $rules[sprintf('%s.vocabulary_uri', $sectorForm)]    = 'url';
            $rules[sprintf('%s.sector_vocabulary', $sectorForm)] = 'required';

            if ($sector['sector_vocabulary'] == 1 || $sector['sector_vocabulary'] == 2) {
                if ($sector['sector_vocabulary'] == 1) {
                    $rules[sprintf('%s.sector_code', $sectorForm)] = 'required_with:' . $sectorForm . '.sector_vocabulary';
                }
                if ($sector['sector_code'] != "") {
                    $rules[sprintf('%s.sector_vocabulary', $sectorForm)] = 'required_with:' . $sectorForm . '.sector_code';
                }
                if ($sector['sector_vocabulary'] == 2) {
                    $rules[sprintf('%s.sector_category_code', $sectorForm)] = 'required_with:' . $sectorForm . '.sector_vocabulary';
                }
                if ($sector['sector_category_code'] != "") {
                    $rules[sprintf('%s.sector_vocabulary', $sectorForm)] = 'required_with:' . $sectorForm . '.sector_category_code';
                }
            } else {
                if ($sector['sector_vocabulary'] != "") {
                    $rules[sprintf('%s.sector_text', $sectorForm)] = 'required_with:' . $sectorForm . '.sector_vocabulary';
                }

                if ($sector['sector_text'] != "") {
                    $rules[sprintf('%s.sector_vocabulary', $sectorForm)] = 'required_with:' . $sectorForm . '.sector_text';
                }

                if ($sector['sector_vocabulary'] == "99" || $sector['sector_vocabulary'] == "98") {
                    $rules[sprintf('%s.vocabulary_uri', $sectorForm)] = 'url|required_with:' . $sectorForm . '.sector_vocabulary';
                }
            }

            $rules[sprintf('%s.percentage', $sectorForm)] = 'numeric|max:100';
            if (count($formFields) > 1) {
                $rules[sprintf('%s.percentage', $sectorForm)] = 'required|numeric|max:100';
            }
            $rules = array_merge($rules, $this->getRulesForNarrative($sector['narrative'], $sectorForm));
        }

        $totalPercentage = $this->getRulesForPercentage($this->get('sector'));

        $indexes = [];

        foreach ($totalPercentage as $index => $value) {
            if (is_numeric($index) && $value != 100) {
                $indexes[] = $index;
            }
        }

        $fields = [];

        foreach ($totalPercentage as $i => $percentage) {
            foreach ($indexes as $index) {
                if ($index == $percentage) {
                    $fields[] = $i;
                }
            }
        }

        foreach ($fields as $field) {
            $rules[$field] = 'required|sum|numeric|max:100';
        }

        return $rules;
    }

    /**
     * returns messages for sector
     * @param $formFields
     * @return array|mixed
     */
    public function getSectorsMessages($formFields)
    {
        $messages = [];

        foreach ($formFields as $sectorIndex => $sector) {
            $sectorForm                                                      = sprintf('sector.%s', $sectorIndex);
            $messages[sprintf('%s.vocabulary_uri.url', $sectorForm)]         = trans('validation.url');
            $messages[sprintf('%s.sector_vocabulary.required', $sectorForm)] = trans('validation.required', ['attribute' => trans('elementForm.sector_vocabulary')]);

            if ($sector['sector_vocabulary'] == 1 || $sector['sector_vocabulary'] == 2) {
                if ($sector['sector_vocabulary'] == 1) {
                    $messages[sprintf('%s.sector_code.%s', $sectorForm, 'required_with')] = trans(
                        'validation.required_with',
                        ['attribute' => trans('elementForm.sector_code'), 'values' => trans('elementForm.sector_vocabulary')]
                    );
                }
                if ($sector['sector_code'] != "") {
                    $messages[sprintf('%s.sector_vocabulary.%s', $sectorForm, 'required_with')] = trans(
                        'validation.required_with',
                        [
                            'attribute' => trans('elementForm.sector_vocabulary'),
                            'values'    => trans('elementForm.sector_code')
                        ]
                    );
                }
                if ($sector['sector_vocabulary'] == 2) {
                    $messages[sprintf('%s.sector_category_code.%s', $sectorForm, 'required_with')] = trans(
                        'validation.required_with',
                        [
                            'attribute' => trans('elementForm.sector_code'),
                            'values'    => trans('elementForm.sector_vocabulary')
                        ]
                    );
                }
                if ($sector['sector_category_code'] != "") {
                    $messages[sprintf('%s.sector_vocabulary.%s', $sectorForm, 'required_with')] = trans(
                        'validation.required_with',
                        [
                            'attribute' => trans('elementForm.sector_vocabulary'),
                            'values'    => trans('elementForm.sector_code')
                        ]
                    );
                }
            } else {
                if ($sector['sector_vocabulary'] != "") {
                    $messages[sprintf('%s.sector_text.%s', $sectorForm, 'required_with')] = trans(
                        'validation.required_with',
                        ['attribute' => trans('elementForm.sector_code'), 'values' => trans('elementForm.sector_vocabulary')]
                    );
                }

                if ($sector['sector_text'] != "") {
                    $messages[sprintf('%s.sector_vocabulary.%s', $sectorForm, 'required_with')] = trans(
                        'validation.required_with',
                        [
                            'attribute' => trans('elementForm.sector_vocabulary'),
                            'values'    => trans('elementForm.sector_code')
                        ]
                    );
                }

                if ($sector['sector_vocabulary'] == "99" || $sector['sector_vocabulary'] == "98") {
                    $messages[sprintf('%s.vocabulary_uri.%s', $sectorForm, 'required_with')] = trans(
                        'validation.required_with',
                        [
                            'attribute' => trans('elementForm.vocabulary_uri'),
                            'values'    => trans('elementForm.sector_vocabulary')
                        ]
                    );
                }
            }

            $messages[sprintf('%s.percentage.numeric', $sectorForm)]  = trans('validation.numeric', ['attribute' => trans('elementForm.percentage')]);
            $messages[sprintf('%s.percentage.max', $sectorForm)]      = trans('validation.max.numeric', ['attribute' => trans('elementForm.percentage'), 'max' => 100]);
            $messages[sprintf('%s.percentage.required', $sectorForm)] = trans('validation.required', ['attribute' => trans('elementForm.percentage')]);
            $messages[sprintf('%s.percentage.sum', $sectorForm)]      = trans('validation.sum', ['attribute' => trans('elementForm.percentage')]);
            $messages                                                 = array_merge($messages, $this->getMessagesForNarrative($sector['narrative'], $sectorForm));
        }

        return $messages;
    }
}
