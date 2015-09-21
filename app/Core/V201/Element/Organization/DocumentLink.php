<?php

namespace App\Core\V201\Element\Organization;

use App\Core\Elements\BaseElement;
use App;
use App\Models\Organization\OrganizationData;

class DocumentLink extends BaseElement
{
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
     * @return string
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Organization\MultipleDocumentLinkForm";
    }

    /**
     * @param OrganizationData $organizationData
     * @return array
     */
    public function getXmlData(OrganizationData $organizationData)
    {
        $orgDocumentLinkData = array();
        $document_link = (array) $organizationData->document_link;
        foreach ($document_link as $orgDocumentLink) {
            $orgDocumentLinkData[] = array(
                '@attributes' => array(
                    'format' => $orgDocumentLink['format'],
                    'url' => $orgDocumentLink['url']
                ),
                'title' => array(
                    'narrative' => $this->buildNarrative($orgDocumentLink['title'])
                ),
                'category' => array(
                    '@attributes' => array('code' => $orgDocumentLink['category'][0]['category']),
                ),
                'language' => array(
                    '@attributes' => array('code' => $orgDocumentLink['language'][0]['language']),
                )
            );
        }

        return $orgDocumentLinkData;
    }

    /**
     * @return organization document link repository
     */
    public function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\DocumentLinkRepository');
    }
}