<?php namespace Test\Elements\DataProviders;


trait ActivityDateDataProvider
{
    use TestObjectCreator;

    public function getIsoDate()
    {
        return $this->createTestObjectWith(['iso_date' => date('Y-m-d')])->iso_date;
    }

    public function getActivityDateTypeCode()
    {
        return $this->createTestObjectWith(['Code' => '1'])->Code;
    }
}
