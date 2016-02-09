<?php namespace Test\Elements\DataProviders;

trait RecipientRegionDataProvider
{
    use TestObjectCreator;

    public function getRegionCode()
    {
        return $this->createTestObjectWith(['Code' => '123'])->Code;
    }

    public function getRegionVocabularyCode()
    {
        return $this->createTestObjectWith(['Code' => '321'])->Code;
    }

    public function getRegionPercentage()
    {
        return $this->createTestObjectWith(['percentage' => '80'])->percentage;
    }

    public function getRecipientCountryNarratives()
    {
        return $this->createTestObjectWith(['id' => '1', '@xml_lang' => '', 'text' => 'testText', 'recipient_region_id' => '45', 'xml_lang' => '']);
    }
}