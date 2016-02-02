<?php namespace Test\Elements;

use App\Migration\Elements\Document;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\DocumentDataProvider;

class DocumentTest extends AidStreamTestCase
{
    use DocumentDataProvider;

    protected $testInput;
    protected $expectedOutput;
    protected $document;

    public function setUp()
    {
        parent::setUp();
        $this->testInput      = [];
        $this->expectedOutput = [];
        $this->document       = new Document();
    }

    /** {@test} */
    public function itShouldFormatDocumentsDataWithSingleUrl()
    {
        $this->testInput      = $this->getTestInputWithSingleUrl();
        $this->expectedOutput = $this->formatDocumentData($this->testInput);

        $this->assertEquals($this->expectedOutput, $this->document->format($this->testInput));
    }

    /** {@test} */
    public function itShouldFormatDocumentDataWithMultipleUrl()
    {
        $this->testInput      = $this->getTestInputWithMultipleUrl();
        $this->expectedOutput = $this->formatDocumentData($this->testInput);

        $this->assertEquals($this->expectedOutput, $this->document->format($this->testInput));
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    protected function formatDocumentData($testInput)
    {
        foreach ($testInput as $url => $data) {
            $testInput[$url]['activities'] = $data['activities'];
        }

        return $testInput;
    }
}
