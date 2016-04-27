<?php namespace App\Core\Elements;

/**
 * Class BaseElement
 * @package App\Core\Elements
 */
class BaseElement
{
    /**
     * Build narratives for Elements.
     * @param $narratives
     * @return array
     */
    public function buildNarrative($narratives)
    {
        $narrativeData = [];
        foreach ($narratives as $narrative) {
            $narrativeData[] = [
                '@value'      => getVal($narrative, ['narrative']),
                '@attributes' => [
                    'xml:lang' => getVal($narrative, ['language'])
                ]
            ];
        }

        return $narrativeData;
    }
}
