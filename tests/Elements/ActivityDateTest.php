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
        $dateNarratives       = [$this->createTestObjectWith(['id' => '1', '@xml_lang' => '12', 'text' => 'test', 'activity_date_id' => '21', 'xml_lang' => '12'])];

        $this->expectedOutput = $this->formatActivityDate($dateNarratives, $isoDate, $activityDateTypeCode);

        $this->assertEquals($this->expectedOutput, $this->activityDate->format($dateNarratives, $isoDate, $activityDateTypeCode));
    }

    /** {@test} */
    public function itShouldFormatActivityDateWithEmptyNarratives()
    {
        $isoDate              = $this->getIsoDate();
        $activityDateTypeCode = $this->getActivityDateTypeCode();
        $dateNarratives       = [$this->createTestObjectWith(['id' => '1', '@xml_lang' => '12', 'text' => 'test', 'activity_date_id' => '21', 'xml_lang' => '12'])];

        $this->expectedOutput = $this->formatActivityDate($dateNarratives, $isoDate, $activityDateTypeCode);

        $this->assertEquals($this->expectedOutput, $this->activityDate->format($dateNarratives, $isoDate, $activityDateTypeCode));
    }

    protected function formatActivityDate($dateNarratives, $isoDate, $activityDateTypeCode)
    {
        $template         = getHeaders('ActivityData', 'activityDate')[0];
        $language         = '';
        $n                = [];
        $template['date'] = $isoDate;
        $template['type'] = $activityDateTypeCode;

        if (!empty($dateNarratives)) {
            foreach ($dateNarratives as $narrative) {
                if ($narrative->xml_lang != '') {
                    $language = getLanguageCodeFor($narrative->xml_lang);
                }

                $n[] = ['narrative' => $narrative->text, 'language' => $language];
            }
            $template['narrative'] = $n;

            return $template;
        }

        $template['narrative'] = [['narrative' => "", 'language' => ""]];

        return $template;
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
