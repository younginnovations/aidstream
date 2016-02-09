<?php namespace Test\Elements\DataProviders;


trait SectorDataProvider
{
    use TestObjectCreator;

    public function getSectorPercentage()
    {
        return $this->createTestObjectWith(['percentage' => '100'])->percentage;
    }

    public function getSectorNarratives()
    {
        return $this->createTestObjectWith(['id' => '1', '@xml_lang' => '', 'text' => 'test', 'sector_id' => '12', 'xml_lang' => '']);
    }
}
