<?php namespace App\Core\V201\Requests\Activity;

/**
 * Class Sector
 * @package App\Core\V201\Requests\Activity
 */
class Sector extends ActivityBaseRequest
{

    public function rules()
    {
        return $this->getSectorsRules($this->get('sector'));
    }

    /**
     * prepare the error message
     * @return array
     */
    public function messages()
    {
        return $this->getSectorsMessages($this->get('sector'));
    }

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
            }

            $rules[sprintf('%s.percentage', $sectorForm)] = 'numeric|max:100';
            $rules                                        = array_merge($rules, $this->getRulesForNarrative($sector['narrative'], $sectorForm));
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
            $messages[sprintf('%s.sector_vocabulary.required', $sectorForm)] = 'Sector vocabulary is required';

            if ($sector['sector_vocabulary'] == 1 || $sector['sector_vocabulary'] == 2) {
                if ($sector['sector_vocabulary'] == 1) {
                    $messages[sprintf('%s.sector_code.%s', $sectorForm, 'required_with')] = 'Sector is required with Sector vocabulary.';
                }
                if ($sector['sector_code'] != "") {
                    $messages[sprintf('%s.sector_vocabulary.%s', $sectorForm, 'required_with')] = 'Sector vocabulary is required with Sector.';
                }
                if ($sector['sector_vocabulary'] == 2) {
                    $messages[sprintf('%s.sector_category_code.%s', $sectorForm, 'required_with')] = 'Sector is required with Sector vocabulary.';
                }
                if ($sector['sector_category_code'] != "") {
                    $messages[sprintf('%s.sector_vocabulary.%s', $sectorForm, 'required_with')] = 'Sector vocabulary is required with Sector.';
                }
            } else {
                if ($sector['sector_vocabulary'] != "") {
                    $messages[sprintf('%s.sector_text.%s', $sectorForm, 'required_with')] = 'Sector is required with Sector vocabulary.';
                }

                if ($sector['sector_text'] != "") {
                    $messages[sprintf('%s.sector_vocabulary.%s', $sectorForm, 'required_with')] = 'Sector vocabulary is required with Sector.';
                }
            }

            $messages[sprintf('%s.percentage.numeric', $sectorForm)] = 'Percentage should be numeric';
            $messages[sprintf('%s.percentage.max', $sectorForm)]     = 'Percentage should be less than or equal to :max';
            $messages                                                = array_merge($messages, $this->getMessagesForNarrative($sector['narrative'], $sectorForm));
        }

        return $messages;
    }
}
