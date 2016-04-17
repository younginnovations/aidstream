<?php namespace App\Core\V202\Element\Activity;

use App\Core\V201\Element\Activity\DocumentLink as V201DocumentLink;
use App\Models\Activity\Activity;

/**
 * Class DocumentLink
 * @package app\Core\V202\Element\Activity
 */
class DocumentLink extends V201DocumentLink
{
    /**
     * @param $activity
     * @return array
     */
    public function getXmlData(Activity $activity)
    {
        $activityData  = [];
        $documentLinks = (array) $activity->document_link;
        foreach ($documentLinks as $documentLink) {
            $activityData[] = [
                '@attributes'   => [
                    'url'    => $documentLink['url'],
                    'format' => $documentLink['format']
                ],
                'title'         => [
                    'narrative' => $this->buildNarrative($documentLink['title'][0]['narrative'])
                ],
                'category'      => [
                    '@attributes' => [
                        'code' => $documentLink['category'][0]['code']
                    ]
                ],
                'language'      => [
                    '@attributes' => [
                        'code' => $documentLink['language'][0]['language']
                    ]
                ],
                'document-date' => [
                    '@attributes' => [
                        'iso-date' => getVal($documentLink, ['document_date',0,'date'])
                    ]
                ]
            ];
        }

        return $activityData;
    }
}
