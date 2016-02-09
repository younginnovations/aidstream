<?php namespace App\Migration\Elements;


class ActivityDate
{
    public function format($isoDate, $ActivityDateTypeCode, $narrative)
    {
        return ['date' => $isoDate, 'type' => $ActivityDateTypeCode, 'narrative' => $narrative];
    }
}
