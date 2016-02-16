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
        $this->expectedOutput = $this->formatName($nameNarratives);

        $this->assertEquals($this->expectedOutput, $this->name->format($nameNarratives));
    }

    /** {@test} */
    public function itShouldFormatOrganizationDataWithOutName()
    {
        $nameNarratives       = null;
        $this->expectedOutput = $this->formatName($nameNarratives);

        $this->assertEquals($this->expectedOutput, $this->name->format($nameNarratives));
    }

    protected function formatName($nameNarratives)
    {
        $output = [];

        if (!is_null($nameNarratives)) {
            foreach ($nameNarratives as $key => $nameNarrative) {
                $template              = getHeaders('OrganizationData', 'name')[0];
                $template['narrative'] = $nameNarrative->text;

                if ($nameNarrative->xml_lang != '') {
                    $template['language'] = getLanguageCodeFor($nameNarrative->xml_lang);
                }

                $output = $template;
            }
        }

        if (empty($nameNarratives)) {
            $narrative = ['narrative' => "", 'language' => ""];
        } else {
            $narrative = $output;
        }

        return $narrative;
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
