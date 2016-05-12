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
        $documentLinks = $activity->documentLinks()->get();

        foreach ($documentLinks as $documentLink) {
            $documentLink   = $documentLink->document_link;
            $activityData[] = [
                '@attributes'   => [
                    'url'    => $documentLink['url'],
                    'format' => $documentLink['format']
                ],
                'title'         => [
                    'narrative' => $this->buildNarrative(getVal($documentLink, ['title', 0, 'narrative'], []))
                ],
                'category'      => [
                    '@attributes' => [
                        'code' => getVal($documentLink, ['category', 0, 'code'])
                    ]
                ],
                'language'      => [
                    '@attributes' => [
                        'code' => getVal($documentLink, ['language', 0, 'language'])
                    ]
                ],
                'document-date' => [
                    '@attributes' => [
                        'iso-date' => getVal($documentLink, ['document_date', 0, 'date'])
                    ]
                ]
            ];
        }

        return $activityData;
    }
}
