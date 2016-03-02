<?php namespace App\Core\V201\Element\Organization;

use App\Core\Elements\BaseElement;
use App;
use App\Models\Organization\OrganizationData;

/**
 * Class DocumentLink
 * @package App\Core\V201\Element\Organization
 */
class DocumentLink extends BaseElement
{
    /**
     * @var array
     */
    protected $narratives = [];

    /**
     * @param $narrative
     * @return $this
     */
    public function setNarrative($narrative)
    {
        $this->narratives[] = $narrative;

        return $this;
    }

    /**
     * return document link form path
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Organization\MultipleDocumentLinkForm';
    }

    /**
     * @param OrganizationData $organizationData
     * @return array
     */
    public function getXmlData(OrganizationData $organizationData)
    {
        $orgDocumentLinkData = [];
        $documentLink        = (array) $organizationData->document_link;
        foreach ($documentLink as $orgDocumentLink) {
            $orgDocumentLinkData[] = [
                '@attributes'       => [
                    'format' => $orgDocumentLink['format'],
                    'url'    => $orgDocumentLink['url']
                ],
                'title'             => [
                    'narrative' => $this->buildNarrative($orgDocumentLink['narrative'])
                ],
                'category'          => [
                    '@attributes' => ['code' => $orgDocumentLink['category'][0]['code']],
                ],
                'language'          => [
                    '@attributes' => ['code' => $orgDocumentLink['language'][0]['language']],
                ],
                'recipient-country' => [
                    '@attributes' => ['code' => ($orgDocumentLink['recipient_country']) ? $orgDocumentLink['recipient_country'][0]['code'] : ''],
                    'narrative'   => ($orgDocumentLink['recipient_country']) ? $this->buildNarrative($orgDocumentLink['recipient_country'][0]['narrative']) : $this->buildNarrative([])
                ]
            ];
        }

        return $orgDocumentLinkData;
    }

    /**
     * return organization document link repository
     */
    public function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\DocumentLinkRepository');
    }
}
