<?php namespace Test\Elements\DataProviders;


trait IdentifierDataProvider
{
    use TestObjectCreator;

    public function getIdentifierData()
    {
        return $this->createTestObjectWith(['activity_identifier' => 'Test Identifer', 'text' => 'Test Text']);
    }
}
