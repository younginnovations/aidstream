<?php namespace Test\Elements;

use App\Migration\Elements\RecipientRegion;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\RecipientRegionDataProvider;

class RecipientRegionTest extends AidStreamTestCase
{
    use RecipientRegionDataProvider;

    protected $expectedOutput = [];
    protected $recipientRegion;

    public function setUp()
    {
        parent::setUp();
        $this->recipientRegion = new RecipientRegion();

    }

    /** {@test} */
    public function itShouldFormatRecipientRegion()
    {
        $regionCode                 = $this->getRegionCode();
        $regionVocabularyCode       = $this->getRegionVocabularyCode();
        $regionPercentage           = $this->getRegionPercentage();
        $narratives                 = $this->getTestNarratives(['testNarrative1', 'testNarrative2'], ['testLanguage1']);
        $recipientCountryNarratives = $this->getRecipientCountryNarratives();

        $this->expectedOutput       = $this->formatRecipientRegion($regionCode, $regionVocabularyCode, $regionPercentage, $narratives, $recipientCountryNarratives);

        $this->assertEquals($this->expectedOutput, $this->recipientRegion->format($regionCode, $regionVocabularyCode, $regionPercentage, $narratives, $recipientCountryNarratives));
    }

    /** {@test} */
    public function itShouldFormatRecipientRegionWithEmptyNarratives()
    {
        $regionCode                 = $this->getRegionCode();
        $regionVocabularyCode       = $this->getRegionVocabularyCode();
        $regionPercentage           = $this->getRegionPercentage();
        $narratives                 = $this->getTestNarratives();
        $recipientCountryNarratives = null;

        $this->expectedOutput       = $this->formatRecipientRegion($regionCode, $regionVocabularyCode, $regionPercentage, $narratives, $recipientCountryNarratives);

        $this->assertEquals($this->expectedOutput, $this->recipientRegion->format($regionCode, $regionVocabularyCode, $regionPercentage, $narratives, $recipientCountryNarratives));
    }

    protected function formatRecipientRegion($regionCode, $regionVocabularyCode, $regionPercentage, $Narratives, $recipientCountryNarratives)
    {
        if (empty($recipientCountryNarratives)) {
            $narrative = [['narrative' => "", 'language' => ""]];
        } else {
            $narrative = $Narratives;
        }

        return [
            'region_code'       => $regionCode,
            'region_vocabulary' => $regionVocabularyCode,
            'percentage'        => $regionPercentage,
            'narrative'         => $narrative
        ];
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
