<?php namespace App\Core\V202\Requests\Activity;

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
            $sectorForm                                       = sprintf('sector.%s', $sectorIndex);
            $rules[sprintf('%s.vocabulary_uri', $sectorForm)] = 'url';
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
            $sectorForm                                              = sprintf('sector.%s', $sectorIndex);
            $messages[sprintf('%s.vocabulary_uri.url', $sectorForm)] = 'Enter valid URL. eg. http://example.com';
            if ($sector['sector_vocabulary'] == 1 || $sector['sector_vocabulary'] == '') {
                $messages[sprintf('%s.sector_code.%s', $sectorForm, 'required')] = 'Sector is required.';
            } elseif ($sector['sector_vocabulary'] == 2) {
                $messages[sprintf('%s.sector_category_code.%s', $sectorForm, 'required')] = 'Sector is required.';
            } else {
                $messages[sprintf('%s.sector_text.required', $sectorForm)] = 'Sector is required.';
            }
            $messages[sprintf('%s.percentage.numeric', $sectorForm)] = 'Percentage should be numeric';
            $messages[sprintf('%s.percentage.max', $sectorForm)]     = 'Percentage should be less than or equal to required';
            $messages                                                = array_merge($messages, $this->getMessagesForNarrative($sector['narrative'], $sectorForm));
        }

        return $messages;
    }
}
