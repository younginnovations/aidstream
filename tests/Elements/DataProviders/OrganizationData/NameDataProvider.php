<?php namespace Test\Elements\DataProviders\OrganizationData;

use Test\Elements\DataProviders\TestObjectCreator;

trait NameDataProvider
{
    use TestObjectCreator;

    public function getNameNarratives()
    {
        return $this->createTestObjectWith(['id' => '1', '@xml_lang' => null, 'text' => 'test', 'name_id' => '12', 'xml_lang' => 'asdf']);
    }
}
