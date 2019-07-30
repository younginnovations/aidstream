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
            foreach(getVal($orgDocumentLink, ['category']) as $value){
            $categories[] = [
                '@attributes' => ['code' => getVal($value, ['code'])]
            ];
            
        }
            
            $orgDocumentLinkData[] = [
                '@attributes'       => [
                    'format' => getVal($orgDocumentLink, ['format']),
                    'url'    => getVal($orgDocumentLink, ['url'])
                ],
                'title'             => [
                    'narrative' => $this->buildNarrative(getVal($orgDocumentLink, ['title', 0, 'narrative']))
                ],
                'description'       => [
                    'narrative' => $this->buildNarrative(getVal($orgDocumentLink, ['description', 0, 'narrative']))
                ],
                'category'          => $categories,
                                
                'language'          => [
                    '@attributes' => ['code' => getVal($orgDocumentLink, ['language', 0, 'language'])],
                ],
                'document-date'     => [
                    '@attributes' => ['iso-date' => getVal($orgDocumentLink, ['document_date', 0, 'date'])],
                ],
                'recipient-country' => [
                    '@attributes' => ['code' => getVal($orgDocumentLink, ['recipient_country', 0, 'code'])],
                    'narrative'   => $this->buildNarrative(getVal($orgDocumentLink, ['recipient_country', 0, 'narrative']))
                ]
            ];
        }

        return $orgDocumentLinkData;
    }
}
