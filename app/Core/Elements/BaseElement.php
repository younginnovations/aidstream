<?php
namespace App\Core\Elements;

class BaseElement
{
    public function buildNarrative($narratives)
    {
        $narrativeData = [];
        foreach($narratives as $narrative)
        {
            $narrativeData[] = [
                '@value' => $narrative['narrative'],
                '@attributes' => [
                    'xml:lang' => $narrative['language']
                ]
            ];
        }
        return $narrativeData;
    }

}