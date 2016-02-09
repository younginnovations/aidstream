<?php namespace Test\Elements;

use App\Migration\Elements\Sector;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\SectorDataProvider;

class SectorTest extends AidStreamTestCase
{
    use SectorDataProvider;

    protected $expectedOutput = [];
    protected $sectorCode = '';
    protected $sectorCategoryCode = '';
    protected $sectorText = '';
    protected $sector_code = '999';
    protected $sectorCodeId = '12';
    protected $sectorNarratives;
    protected $sector;

    public function setUp()
    {
        parent::setUp();
        $this->sectorNarratives = $this->getSectorNarratives();
        $this->sector           = new Sector();
    }

    /** {@test} */
    public function itShouldFormatSectorWithVocabularyCodeOne()
    {
        $vocabularyCode = "1";
        $percentage     = $this->getSectorPercentage();
        $narrative      = $this->getTestNarratives(['testNarrative1', 'testNarrative2'], ['testLanguage1', 'testLanguage2']);

        $this->expectedOutput = $this->formatSector($vocabularyCode, $this->sectorCode, $this->sectorCategoryCode, $this->sectorText, $percentage, $narrative, $this->sector_code, $this->sectorCodeId, $this->sectorNarratives);

        $this->assertEquals(
            $this->expectedOutput,
            $this->sector->format($vocabularyCode, $this->sectorCode, $this->sectorCategoryCode, $this->sectorText, $percentage, $narrative, $this->sector_code, $this->sectorCodeId, $this->sectorNarratives)
        );
    }

    /** {@test} */
    public function itShouldFormatSectorWithVocabularyCodeTwo()
    {
        $vocabularyCode       = "2";
        $percentage           = $this->getSectorPercentage();
        $narrative            = $this->getTestNarratives(['testNarrative1', 'testNarrative2'], ['testLanguage1', 'testLanguage2']);

        $this->expectedOutput = $this->formatSector($vocabularyCode, $this->sectorCode, $this->sectorCategoryCode, $this->sectorText, $percentage, $narrative, $this->sector_code, $this->sectorCodeId, $this->sectorNarratives);

        $this->assertEquals(
            $this->expectedOutput,
            $this->sector->format($vocabularyCode, $this->sectorCode, $this->sectorCategoryCode, $this->sectorText, $percentage, $narrative, $this->sector_code, $this->sectorCodeId, $this->sectorNarratives)
        );
    }

    /** {@test} */
    public function itShouldFormatSectorWithVocabularyCodeOtherThanOneAndTwo()
    {
        $vocabularyCode       = "a";
        $percentage           = $this->getSectorPercentage();
        $narrative            = $this->getTestNarratives(['testNarrative1', 'testNarrative2'], ['testLanguage1', 'testLanguage2']);

        $this->expectedOutput = $this->formatSector($vocabularyCode, $this->sectorCode, $this->sectorCategoryCode, $this->sectorText, $percentage, $narrative, $this->sector_code, $this->sectorCodeId, $this->sectorNarratives);

        $this->assertEquals(
            $this->expectedOutput,
            $this->sector->format($vocabularyCode, $this->sectorCode, $this->sectorCategoryCode, $this->sectorText, $percentage, $narrative, $this->sector_code, $this->sectorCodeId, $this->sectorNarratives)
        );
    }

    /** {@test} */
    public function itShouldFormatSectorWithEmptyNarratives()
    {
        $vocabularyCode       = "2";
        $percentage           = $this->getSectorPercentage();
        $narrative            = $this->getTestNarratives();

        $this->expectedOutput = $this->formatSector($vocabularyCode, $this->sectorCode, $this->sectorCategoryCode, $this->sectorText, $percentage, $narrative, $this->sector_code, $this->sectorCodeId, $this->sectorNarratives);

        $this->assertEquals(
            $this->expectedOutput,
            $this->sector->format($vocabularyCode, $this->sectorCode, $this->sectorCategoryCode, $this->sectorText, $percentage, $narrative, $this->sector_code, $this->sectorCodeId, $this->sectorNarratives)
        );
    }

    protected function formatSector($vocabularyCode, $sectorCode, $sectorCategoryCode, $sectorText, $percentage, $Narrative, $sector_code, $sectorCodeId, $sectorNarratives)
    {
        if ($vocabularyCode == "1") {
            $sectorCode = $sector_code;
        } elseif ($vocabularyCode == "2") {
            $sectorCategoryCode = $sector_code;
        } else {                             //fetch the text code as it is
            $sectorText = $sectorCodeId;
        }

        if (empty($sectorNarratives)) {
            $narrative = [['narrative' => "", 'language' => ""]];
        } else {
            $narrative = $Narrative;
        }

        return [
            'sector_vocabulary'    => $vocabularyCode,
            'sector_code'          => $sectorCode,
            'sector_category_code' => $sectorCategoryCode,
            'sector_text'          => $sectorText,
            'percentage'           => $percentage,
            'narrative'            => $narrative
        ];
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
