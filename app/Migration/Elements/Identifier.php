<?php namespace App\Migration\Elements;


class Identifier
{
    public function format($iatiIdentifierInfo)
    {
        return ['activity_identifier' => $iatiIdentifierInfo->activity_identifier, 'iati_identifier_text' => $iatiIdentifierInfo->text];
    }
}
