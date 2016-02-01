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
                '@value'      => isset($narrative['narrative']) ? $narrative['narrative'] : '',
                '@attributes' => [
                    'xml:lang' => $narrative['language']
                ]
            ];
        }

        return $narrativeData;
    }
}
