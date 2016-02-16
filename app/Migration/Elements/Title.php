<?php namespace App\Migration\Elements;

/**
 * Class Title
 * @package App\Migration\Elements
 */
class Title
{
    /**
     * @param array $titleMetaData
     * @return array|null
     */
    public function format(array $titleMetaData)
    {
        $titleJson = [];

        foreach ($titleMetaData as $activityId => $activityTitleData) {
            if ($activityTitleData['title']) {
                foreach ($activityTitleData['title'] as $index => $title) {
                  $titleJson[] = ['language' => $activityTitleData['lang'] ? $activityTitleData['lang'][$index] : '', 'narrative' => $title->text];
                }
            } else {
                $titleJson = null;
            }
        }

        return $titleJson;
    }
}
