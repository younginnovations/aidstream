<?php namespace App\Core\V201\Element\Organization;

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
        $orgDocumentLinkData = [];
        $documentLink        = (array) $organizationData->document_link;
        foreach ($documentLink as $orgDocumentLink) {
            $orgDocumentLinkData[] = [
                '@attributes' => [
                    'format' => $orgDocumentLink['format'],
                    'url'    => $orgDocumentLink['url']
                ],
                'title'       => [
                    'narrative' => $this->buildNarrative($orgDocumentLink['narrative'])
                ],
                'category'    => [
                    '@attributes' => ['code' => $orgDocumentLink['category'][0]['code']],
                ],
                'language'    => [
                    '@attributes' => ['code' => $orgDocumentLink['language'][0]['language']],
                ]
            ];
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
