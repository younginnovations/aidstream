<?php namespace Test\Elements\OrganizationData;

use App\Migration\Elements\OrganizationData\Name;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\OrganizationData\NameDataProvider;

class NameTest extends AidStreamTestCase
{
    use NameDataProvider;

    protected $expectedOutput = [];
    protected $name;

    public function setUp()
    {
        parent::setUp();
        $this->name = new Name();
    }

    /** {@test} */
    public function itShouldFormatOrganizationDataName()
    {
        $nameNarratives       = $this->getNameNarratives();
        $narratives           = $this->getTestNarratives(['testNarrative1', 'testNarrative2'], ['testLanguage']);

        $this->expectedOutput = $this->formatName($narratives, $nameNarratives);

        $this->assertEquals($this->expectedOutput, $this->name->format($narratives, $nameNarratives));
    }

    /** {@test} */
    public function itShouldFormatOrganizationDataWithOutName()
    {
        $nameNarratives       = null;
        $narratives           = $this->getTestNarratives(['testNarrative1', 'testNarrative2'], ['testLanguage']);

        $this->expectedOutput = $this->formatName($narratives, $nameNarratives);

        $this->assertEquals($this->expectedOutput, $this->name->format($narratives, $nameNarratives));
    }

    protected function formatName($Narrative, $nameNarratives)
    {
        if (empty($nameNarratives)) {
            $narrative = [['narrative' => "", 'language' => ""]];
        } else {
            $narrative = $Narrative;
        }

        return $narrative;
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
