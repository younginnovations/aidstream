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
            $sectorForm = sprintf('sector.%s', $sectorIndex);
            if ($sector['sector_vocabulary'] == 1 || $sector['sector_vocabulary'] == '') {
                $rules[sprintf('%s.sector_code', $sectorForm)] = 'required';
            } elseif ($sector['sector_vocabulary'] == 2) {
                $rules[sprintf('%s.sector_category_code', $sectorForm)] = 'required';
            } else {
                $rules[sprintf('%s.sector_text', $sectorForm)] = 'required';
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
            $sectorForm = sprintf('sector.%s', $sectorIndex);
            if ($sector['sector_vocabulary'] == 1 || $sector['sector_vocabulary'] == '') {
                $messages[sprintf('%s.sector_code.%s', $sectorForm, 'required')] = 'Sector is required.';
            } elseif ($sector['sector_vocabulary'] == 2) {
                $messages[sprintf('%s.sector_category_code.%s', $sectorForm, 'required')] = 'Sector is required.';
            } else {
                $messages[sprintf('%s.sector_text.%s', $sectorForm, 'required')] = 'Sector is required.';
            }
            $messages[sprintf('%s.percentage.%s', $sectorForm, 'numeric')] = 'Percentage should be numeric';
            $messages[sprintf('%s.percentage.%s', $sectorForm, 'max')]     = 'Percentage should be less than or equal to :max';
            $messages                                                      = array_merge($messages, $this->getMessagesForNarrative($sector['narrative'], $sectorForm));
        }

        return $messages;
    }
}
