<?php namespace Test\Elements\DataProviders;


trait OtherIdentifierDataProvider
{
    use TestObjectCreator;

    public function getOtherIdentifierData()
    {
        return [
            'ownerOrgReference' => 'Test Reference',
            'narratives'        => $this->getTestNarratives(['testNarrative1', 'testNarrative2'], ['testLang1', 'testLang2']),
            'iatiOtherInfo'     => $this->createTestObjectWith(['ref' => 'Test other info reference', 'type' => '1', 'id' => '11']),
            'typeCode'          => $this->createTestObjectWith(['Code' => 'TestCode'])
        ];
    }

    public function getOtherIdentifierDataWithEmptyNarratives()
    {
        return [
            'ownerOrgReference' => 'Test Reference',
            'narratives'        => $this->getTestNarratives(),
            'iatiOtherInfo'     => $this->createTestObjectWith(['ref' => 'Test other info reference', 'type' => '1', 'id' => '11']),
            'typeCode'          => $this->createTestObjectWith(['Code' => 'TestCode'])
        ];
    }
}
