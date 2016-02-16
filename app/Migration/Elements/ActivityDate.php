<?php namespace App\Migration\Elements;


class ActivityDate
{
    public function format($dateNarratives, $isoDate, $ActivityDateTypeCode)
    {
        $language  = "";
        $Narrative = [];

        if (!empty($dateNarratives)) {
            foreach ($dateNarratives as $eachNarrative) {
                $narrative_text = $eachNarrative->text;

                if ($eachNarrative->xml_lang != "") {
                    $language = getLanguageCodeFor($eachNarrative->xml_lang);
                }

                $Narrative[] = ['narrative' => $narrative_text, 'language' => $language];
            }

            return ['date' => $isoDate, 'type' => $ActivityDateTypeCode, 'narrative' => $Narrative];
        }

        return ['date' => $isoDate, 'type' => $ActivityDateTypeCode, 'narrative' => [['narrative' => "", 'language' => ""]]];
    }
}
