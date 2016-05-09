<?php namespace App\Core\V201\Element\Activity;

use App\Core\Elements\BaseElement;
use App\Models\Activity\Activity;

/**
 * Class DocumentLink
 * @package app\Core\V201\Element\Activity
 */
class DocumentLink extends BaseElement
{
    /**
     * @return  Document Link form
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\DocumentLinks';
    }

    /**
     * @return Document Link repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\DocumentLink');
    }

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
                '@attributes' => [
                    'url'    => $documentLink['url'],
                    'format' => $documentLink['format']
                ],
                'title'       => [
                    'narrative' => $this->buildNarrative($documentLink['title'][0]['narrative'])
                ],
                'category'    => [
                    '@attributes' => [
                        'code' => isset($documentLink['category'][0]['code']) ? $documentLink['category'][0]['code'] : ''
                    ]
                ],
                'language'    => [
                    '@attributes' => [
                        'code' => isset($documentLink['language'][0]['language']) ? $documentLink['language'][0]['language'] : ''
                    ]
                ]
            ];
        }

        return $activityData;
    }
}
