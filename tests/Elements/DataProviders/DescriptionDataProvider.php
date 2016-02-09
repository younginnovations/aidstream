<?php namespace Test\Elements\DataProviders;


trait DescriptionDataProvider
{
    use TestObjectCreator;

    protected function getTestDescriptionData()
    {
        $type      = $this->createTestObjectWith(['Code' => 1]);
        $typeCode  = $type->Code;
        $narrative = $this->getTestNarratives(['testNarrative1', 'testNarrative2'], ['testLanguage1', 'testLanguage2']);

        return ['code' => $typeCode, 'narrative' => $narrative];
    }
}
