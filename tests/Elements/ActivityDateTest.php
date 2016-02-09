<?php namespace Test\Elements;

use App\Migration\Elements\ActivityDate;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\ActivityDateDataProvider;

class ActivityDateTest extends AidStreamTestCase
{
    use ActivityDateDataProvider;

    protected $expectedOutput;
    protected $activityDate;


    public function setUp()
    {
        parent::setUp();
        $this->activityDate = new ActivityDate();
    }

    /** {@test} */
    public function itShouldFormatActivityDate()
    {
        $isoDate              = $this->getIsoDate();
        $activityDateTypeCode = $this->getActivityDateTypeCode();
        $narrative            = $this->getTestNarratives(['testNarrative1', 'testNarrative2', 'sasdf'], ['testLanguage1', 'testLanguage2']);

        $this->expectedOutput = $this->formatActivityDate($isoDate, $activityDateTypeCode, $narrative);

        $this->assertEquals($this->expectedOutput, $this->activityDate->format($isoDate, $activityDateTypeCode, $narrative));
    }

    /** {@test} */
    public function itShouldFormatActivityDateWithEmptyNarratives()
    {
        $isoDate              = $this->getIsoDate();
        $activityDateTypeCode = $this->getActivityDateTypeCode();
        $narrative            = $this->getTestNarratives();

        $this->expectedOutput = $this->formatActivityDate($isoDate, $activityDateTypeCode, $narrative);

        $this->assertEquals($this->expectedOutput, $this->activityDate->format($isoDate, $activityDateTypeCode, $narrative));
    }

    protected function formatActivityDate($isoDate, $activityDateTypeCode, $narrative)
    {
        return ['date' => $isoDate, 'type' => $activityDateTypeCode, 'narrative' => $narrative];
    }

    public function tearDown()
    {

    }
}
