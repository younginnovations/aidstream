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
        foreach ($testInput as $key => $data) {
            $this->expectedOutput['id'] = $key;

            foreach ($data['title'] as $index => $title) {
                $this->expectedOutput['title'][] = ['language' => $data['lang'] ? $data['lang'][$index] : '', 'narrative' => $title->text];
            }
        }

//        $this->expectedOutput['title'] = array_key_exists('title', $this->expectedOutput) ? json_encode($this->expectedOutput['title']) : [];

        return $this->expectedOutput;
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
