<?php
namespace App\Core\Elements;

class BaseElement
{
    public function buildNarrative($narratives)
    {
        foreach($narratives as $narrative)
        {
            $narrativeData[] =array(
                '@value' => $narrative['narrative'],
                '@attributes' => array(
                    'xml:lang' => $narrative['language']
                )
            );
        }
        return $narrativeData;
    }

}