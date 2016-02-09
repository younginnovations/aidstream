<?php namespace App\Migration\Elements\OrganizationData;

class Name
{
    public function format($Narrative, $nameNarratives)
    {
        if (empty($nameNarratives)) {
            $narrative = [['narrative' => "", 'language' => ""]];
        } else {
            $narrative = $Narrative;
        }

        return $narrative;
    }
}
