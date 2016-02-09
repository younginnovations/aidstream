<?php namespace App\Migration\Elements;


class Sector
{
    public function format($vocabCode, $sector_code, $sector_category_code, $sector_text, $percentage, $Narrative, $sectorCode, $sectorCodeId, $sectorNarratives)
    {
        if ($vocabCode == "1") {
            $sector_code = $sectorCode;
        } elseif ($vocabCode == "2") {
            $sector_category_code = $sectorCode;
        } else {                             //fetch the text code as it is
            $sector_text = $sectorCodeId;
        }

        if (empty($sectorNarratives)) {
            $narrative = [['narrative' => "", 'language' => ""]];
        } else {
            $narrative = $Narrative;
        }

        return [
            'sector_vocabulary'    => $vocabCode,
            'sector_code'          => $sector_code,
            'sector_category_code' => $sector_category_code,
            'sector_text'          => $sector_text,
            'percentage'           => $percentage,
            'narrative'            => $narrative
        ];
    }
}
