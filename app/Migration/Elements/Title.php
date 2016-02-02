<?php namespace App\Migration\Elements;

class Title
{
    public function format(array $titleMetaData)
    {
        $titleJson = [];
        foreach ($titleMetaData as $activityId => $activityTitleData) {
            $titleJson['id']    = $activityId;

            if ($activityTitleData['title']) {
                foreach ($activityTitleData['title'] as $index => $title) {
                  $titleJson['title'][] = ['language' => $activityTitleData['lang'] ? $activityTitleData['lang'][$index] : '', 'narrative' => $title->text];
                }
            }
        }
        $titleJson['title'] = array_key_exists('title', $titleJson) ? $titleJson['title'] : [];

        return $titleJson;
    }
}
