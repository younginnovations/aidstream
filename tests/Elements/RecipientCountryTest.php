<?php namespace Test\Elements;

use App\Migration\Elements\RecipientCountry;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\RecipientCountryDataProvider;

class RecipientCountryTest extends AidStreamTestCase
{
    use RecipientCountryDataProvider;

    protected $expectedOutput = [];
    protected $recipientCountry;

    public function setUp()
    {
        parent::setUp();
        $this->recipientCountry = new RecipientCountry();
    }

    /** {@test} */
    public function itShouldFormatRecipientCountry()
    {
        $countryCode                = $this->getRecipientCountryCode();
        $countryPercentage          = $this->getRecipientCountryPercentage();
        $narrative                  = $this->getTestNarratives(['testNarrative1', 'testNarrative2'], ['testLanguage1', 'testLanguage2']);
        $recipientCountryNarratives = $this->getRecipientCountryNarratives();

        $this->expectedOutput       = $this->formatRecipientCountry($countryCode, $countryPercentage, $narrative, $recipientCountryNarratives);

        $this->assertEquals($this->expectedOutput, $this->recipientCountry->format($countryCode, $countryPercentage, $narrative, $recipientCountryNarratives));
    }

    /** {@test} */
    public function itShouldFormatRecipientCountryWithEmptyNarratives()
    {
        $countryCode                = $this->getRecipientCountryCode();
        $countryPercentage          = $this->getRecipientCountryPercentage();
        $narrative                  = $this->getTestNarratives();
        $recipientCountryNarratives = null;

        $this->expectedOutput       = $this->formatRecipientCountry($countryCode, $countryPercentage, $narrative, $recipientCountryNarratives);

        $this->assertEquals($this->expectedOutput, $this->recipientCountry->format($countryCode, $countryPercentage, $narrative, $recipientCountryNarratives));
    }

    protected function formatRecipientCountry($countryCode, $countryPercentage, $narrative, $recipientCountryNarratives)
    {
        if (empty($recipientCountryNarratives)) {
            $narratives = [['narrative' => "", 'language' => ""]];
        } else {
            $narratives = $narrative;
        }

        return ['country_code' => $countryCode, 'percentage' => $countryPercentage, 'narrative' => $narratives];
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
