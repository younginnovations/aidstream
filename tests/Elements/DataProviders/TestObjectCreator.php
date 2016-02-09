<?php namespace Test\Elements\DataProviders;

use stdClass;

trait TestObjectCreator
{
    /**
     * Returns an object with all the requirements as attributes.
     * @param array $requirements
     * @return stdClass
     */
    protected function createTestObjectWith(array $requirements)
    {
        $testObject = new stdClass();

        foreach ($requirements as $key => $value) {
            $testObject->$key = $value;
        }

        return $testObject;
    }

    /**
     * Returns an array with test valued narratives.
     * @param array $narratives
     * @param array $languages
     * @return array
     */
    protected function getTestNarratives(array $narratives = [], array $languages = [])
    {
        $testNarratives = [];

        if (!$narratives && !$languages) {
            $testNarratives[] = [
                'narrative' => '',
                'language' => ''
            ];

            return $testNarratives;
        }

        $count = (($narrativesCount = count($narratives)) > ($languagesCount = count($languages))) ? $narrativesCount : $languagesCount;

        for ($i = 0; $i < $count; $i ++) {
            $testNarratives[] = [
                'narrative' => (array_key_exists($i, $narratives) && $narratives[$i]) ? $narratives[$i] : '',
                'language'  => (array_key_exists($i, $languages) && $languages[$i]) ? $languages[$i] : ''
            ];
        }

        return $testNarratives;
    }
}
