<?php namespace Test\Elements;

use App\Migration\Elements\Identifier;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\IdentifierDataProvider;

class IdentifierTest extends AidStreamTestCase
{
    use IdentifierDataProvider;

    protected $testInput = null;
    protected $expectedOutput = [];
    protected $identifier;

    public function setUp()
    {
        parent::setUp();
        $this->testInput  = $this->getIdentifierData();
        $this->identifier = new Identifier();
    }

    /** {@test} */
    public function itShouldFormatIdentifier()
    {
        $this->expectedOutput = $this->formatIdentifier($this->testInput);

        $this->assertEquals($this->expectedOutput, $this->identifier->format($this->testInput));
    }

    protected function formatIdentifier($testObject)
    {
        return [
            'activity_identifier'  => $testObject->activity_identifier,
            'iati_identifier_text' => $testObject->text
        ];
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
