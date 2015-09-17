<?php

namespace App\Core\V201\Element\Organization;

use App\Core\Elements\BaseElement;
use App;

class DocumentLink extends BaseElement
{
    protected $narratives = [];

    public function setNarrative($narrative)
    {
        $this->narratives[] = $narrative;

        return $this;
    }

    public function getForm()
    {
        return "App\Core\V201\Forms\Organization\MultipleDocumentLinkForm";
    }

    public function getXmlData($org)
    {
        $orgDocumentLinkData = array();
        foreach ($org->document_link as $orgDocumentLink) {
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

    public function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\DocumentLinkRepository');
    }
}