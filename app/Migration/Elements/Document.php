<?php namespace App\Migration\Elements;

class Document
{
    public function format(array $docMetaData)
    {
        foreach ($docMetaData as $url => $data) {
            $docMetaData[$url]['activities'] = $data['activities'];
        }

        return $docMetaData;
    }
}