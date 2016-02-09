<?php namespace App\Migration\Elements;


class OtherIdentifier
{
    public function format(array $otherIdentifierData)
    {
        $ownerOrganization = ['reference' => $otherIdentifierData['ownerOrgReference'], 'narrative' => $otherIdentifierData['narratives']];

        return ['reference' => $otherIdentifierData['iatiOtherInfo']->ref, 'type' => $otherIdentifierData['typeCode']->Code, 'owner_org' => $ownerOrganization];
    }
}
