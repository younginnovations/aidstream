<?php namespace App\Migration\Elements;


/**
 * Class OtherIdentifier
 * @package App\Migration\Elements
 */
class OtherIdentifier
{
    /**
     * @param array $otherIdentifierData
     * @return array
     */
    public function format(array $otherIdentifierData)
    {
        $ownerOrganization = [
            ['reference' => $otherIdentifierData['ownerOrgReference'], 'narrative' => $otherIdentifierData['narratives']]
        ];

        return [
            ['reference' => $otherIdentifierData['iatiOtherInfo']->ref, 'type' => $otherIdentifierData['typeCode']->Code, 'owner_org' => $ownerOrganization]
        ];
    }
}
