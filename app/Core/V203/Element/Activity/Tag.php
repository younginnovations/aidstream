<?php namespace App\Core\V203\Element\Activity;

use App\Core\Elements\BaseElement;
use App\Models\Activity\Activity;

/**
 * Class Tag
 * return tag form and tag repository
 * @package app\Core\V20\Eleme2nt\Activity
 */
class Tag extends BaseElement
{

    /**
     * @return tag form
     */
    public function getForm()
    {
        return 'App\Core\V203\Forms\Activity\Tags';
    }

    /**
     * @return tag repository
     */
    public function getRepository()
    {
        return App('App\Core\V203\Repositories\Activity\Tag');
    }

    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData = [];
        $tags      = (array) $activity->tag;
        foreach ($tags as $tag) {
            $vocabulary = $tag['tag_vocabulary'];
            $tagValue   = $tag['tag_code'];
            $activityData[] = [
                '@attributes' => [
                    'code'           => $tagValue,
                    'vocabulary'     => $vocabulary,
                    'vocabulary-uri' => getVal($tag, ['vocabulary_uri'])
                ],
                'narrative'   => $this->buildNarrative($tag['narrative'])
            ];
        }

        return $activityData;
    }
}
