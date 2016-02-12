<?php namespace Test\Elements;

use App\Migration\Elements\Title;
use \Mockery as m;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\TitleDataProvider;

class TitleTest extends AidStreamTestCase
{
    use TitleDataProvider;

    protected $title;
    protected $testInput = [];
    protected $dataWithoutLanguage;
    protected $expectedOutput;

    public function setUp()
    {
        parent::setUp();
        $this->expectedOutput = [];
        $this->title          = new Title();
    }

    /** {@test} */
    public function itShouldFormatTitleWithOutLanguageIntoJson()
    {
        $this->testInput      = $this->getDataWithOutLanguage();
        $this->expectedOutput = $this->formatInputIntoExpectedOutput($this->testInput);

        $this->assertEquals($this->expectedOutput, $this->title->format($this->testInput));
    }

    /** {@test} */
    public function itShouldFormatTitleWithAllFieldsIntoJson()
    {
        $this->testInput      = $this->getDataWithAllFields();
        $this->expectedOutput = $this->formatInputIntoExpectedOutput($this->testInput);

        $this->assertEquals($this->expectedOutput, $this->title->format($this->testInput));
    }

    /** {@test} */
    public function itShouldFormatTitleWithoutAnyFields()
    {
        $this->testInput      = $this->getDataWithoutAnyFields();
        $this->expectedOutput = $this->formatInputIntoExpectedOutput($this->testInput);

        $this->assertEquals($this->expectedOutput, $this->title->format($this->testInput));
    }

    /** {@test} */
    public function itShouldFormatTitleWithMultipleNarratives()
    {
        $this->testInput      = $this->getDataWithMultipleNarratives();
        $this->expectedOutput = $this->formatInputIntoExpectedOutput($this->testInput);

        $this->assertEquals($this->expectedOutput, $this->title->format($this->testInput));
    }

    protected function formatInputIntoExpectedOutput($testInput)
    {
        $template = getHeaders('ActivityData', 'title');

        foreach ($testInput as $key => $data) {
            foreach ($data['title'] as $index => $title) {
                $object                          = $template[0];
                $object['language']              = $data['lang'] ? $data['lang'][$index] : '';
                $object['narrative']             = $title->text;
                $this->expectedOutput['title'][] = $object;
            }
        }

        return $this->expectedOutput;
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
