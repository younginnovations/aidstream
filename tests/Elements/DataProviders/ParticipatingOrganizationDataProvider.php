<?php namespace Test\Elements\DataProviders;


trait ParticipatingOrganizationDataProvider
{
    use TestObjectCreator;

    public function getParticipatingOrganizationNarratives()
    {
        return $this->createTestObjectWith(['id' => '1', '@xml_lang' => '123', 'text' => 'Test Text', 'participating_org_id' => '333', 'xml_lang' => '321']);
    }

    public function getParticipatingOrganizationRoleCode()
    {
        return $this->createTestObjectWith(['Code' => '12'])->Code;
    }

    public function getParticipatingOrganizationIdentifier()
    {
        return $this->createTestObjectWith(['role' => '1', 'type' => '1', 'ref' => 'test', 'activity_id' => '1'])->ref;
    }

    public function getParticipatingOrganizationTypeCode()
    {
        return $this->createTestObjectWith(['Code' => '1'])->Code;
    }
}
