<?php namespace Test\Elements\DataProviders;


trait RecipientCountryDataProvider
{
    use TestObjectCreator;

    public function getRecipientCountryCode()
    {
        return $this->createTestObjectWith(['Code' => 'TestCode'])->Code;
    }

    public function getRecipientCountryPercentage()
    {
        return $this->createTestObjectWith(
            [
                'id'          => '1',
                '@code'       => '33',
                '@percentage' => '100',
                '@xml_lang'   => '12',
                'text'        => 'TestCountry',
                'activity_id' => '1',
                'code'        => '23',
                'percentage'  => '100'
            ])->percentage;
    }

    public function getRecipientCountryNarratives()
    {
        return $this->createTestObjectWith(['id' => '12', '@xml_lang' => '33', 'text' => 'TestCountry', 'recipient_country_id' => '7', 'xml_lang' => '4']);
    }
}
