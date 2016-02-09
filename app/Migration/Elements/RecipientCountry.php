<?php namespace App\Migration\Elements;


class RecipientCountry
{
    public function format($countryCode, $countryPercentage, $Narrative, $recipientCountryNarratives)
    {
        if (empty($recipientCountryNarratives)) {
            $narrative = [['narrative' => "", 'language' => ""]];
        } else {
            $narrative = $Narrative;
        }

        return ['country_code' => $countryCode, 'percentage' => $countryPercentage, 'narrative' => $narrative];
    }
}
