<?php namespace Test\Elements;

use App\Migration\Elements\Description;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\DescriptionDataProvider;

class DescriptionTest extends AidStreamTestCase
{
    use DescriptionDataProvider;

    protected $testInput;
    protected $expectedOutput = [];
    protected $description;

    public function setUp()
    {
        parent::setUp();
        $this->testInput = $this->getTestDescriptionData();
        $this->description = new Description();
    }

    /** {@test} */
    public function itShouldFormatDescription()
    {
        $this->expectedOutput = $this->formatDescription($this->testInput);

        $this->assertEquals($this->expectedOutput, $this->description->format($this->testInput));
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    protected function formatDescription($testInput)
    {
        return ['type' => $testInput['code'], 'narrative' => $testInput['narrative']];
    }
}
