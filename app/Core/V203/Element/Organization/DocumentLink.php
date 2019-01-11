<?php namespace App\Core\V203\Element\Organization;

use App;
use App\Core\V201\Element\Organization\DocumentLink as V201DocumentLink;
use App\Models\Organization\OrganizationData;

/**
 * Class DocumentLink
 * @package App\Core\V202\Element\Organization
 */
class DocumentLink extends V201DocumentLink
{
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
                'description'       => [
                    'narrative' => $this->buildNarrative($orgDocumentLink['description'][0]['narrative'])
                ],
                'category'          => [
                    '@attributes' => ['code' => $orgDocumentLink['category'][0]['code']],
                ],
                'language'          => [
                    '@attributes' => ['code' => $orgDocumentLink['language'][0]['language']],
                ],
                'document-date'     => [
                    '@attributes' => ['iso-date' => $orgDocumentLink['document_date'][0]['date']],
                ],
                'recipient-country' => [
                    '@attributes' => ['code' => $orgDocumentLink['recipient_country'][0]['code']],
                    'narrative'   => $this->buildNarrative($orgDocumentLink['recipient_country'][0]['narrative'])
                ]
            ];
        }

        return $orgDocumentLinkData;
    }
}
