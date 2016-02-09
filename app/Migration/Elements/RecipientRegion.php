<?php namespace App\Migration\Elements;


class RecipientRegion
{
    public function format($regionCode, $regionVocabularyCode, $regionPercentage, $Narrative, $recipientCountryNarratives)
    {
        if (empty($recipientCountryNarratives)) {
            $narrative = [['narrative' => "", 'language' => ""]];
        } else {
            $narrative = $Narrative;
        }

        return [
            'region_code'       => $regionCode,
            'region_vocabulary' => $regionVocabularyCode,
            'percentage'        => $regionPercentage,
            'narrative'         => $narrative
        ];
    }
}
