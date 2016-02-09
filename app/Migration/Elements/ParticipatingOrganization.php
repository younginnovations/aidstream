<?php namespace App\Migration\Elements;


class ParticipatingOrganization
{
    public function format($ParticipatingOrgNarratives, $OrgRoleCode, $Identifier, $OrgTypeCode, $Narrative)
    {
        if (empty($ParticipatingOrgNarratives)) {  // format incase of no narrative
            $narrative = [['narrative' => "", 'language' => ""]];
        } else {
            $narrative = $Narrative;
        }

        return ['organization_role' => $OrgRoleCode, 'identifier' => $Identifier, 'organization_type' => $OrgTypeCode, 'narrative' => $narrative];
    }
}
