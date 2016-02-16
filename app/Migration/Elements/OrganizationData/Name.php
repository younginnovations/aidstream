<?php namespace App\Migration\Elements\OrganizationData;

class Name
{
    public function format($nameNarratives)
    {
        $language  = '';
        $Narrative = [];

        if ($nameNarratives) {
            foreach ($nameNarratives as $eachNarrative) {
                $narrative_text = $eachNarrative->text;

                if ($eachNarrative->xml_lang != "") {
                    $language = getLanguageCodeFor($eachNarrative->xml_lang);
                }

                $Narrative = ['narrative' => $narrative_text, 'language' => $language];
            }

            return $Narrative;
        }

        return ['narrative' => "", 'language' => ""];
    }
}
