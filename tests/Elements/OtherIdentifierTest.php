<?php namespace Test\Elements;

use App\Migration\Elements\OtherIdentifier;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\OtherIdentifierDataProvider;

class OtherIdentifierTest extends AidStreamTestCase
{
    use OtherIdentifierDataProvider;

    protected $testInput;
    protected $expectedOutput = [];
    protected $otherIdentifier;

    public function setUp()
    {
        parent::setUp();
        $this->testInput       = $this->getOtherIdentifierData();
        $this->otherIdentifier = new OtherIdentifier();
    }

    /** {@test} */
    public function itShouldFormatOtherIdentifier()
    {
        $this->expectedOutput = $this->formatOtherIdentifier($this->testInput);

        $this->assertEquals($this->expectedOutput, $this->otherIdentifier->format($this->testInput));
    }

    /** {@test} */
    public function itShouldFormatOtherIdentifierWithEmptyNarratives()
    {
        $this->testInput      = $this->getOtherIdentifierDataWithEmptyNarratives();
        $this->expectedOutput = $this->formatOtherIdentifier($this->testInput);

        $this->assertEquals($this->expectedOutput, $this->otherIdentifier->format($this->testInput));
    }

    protected function formatOtherIdentifier($otherIdentifierData)
    {
        $ownerOrganization = [
            ['reference' => $otherIdentifierData['ownerOrgReference'], 'narrative' => $otherIdentifierData['narratives']]
        ];

        return [
            ['reference' => $otherIdentifierData['iatiOtherInfo']->ref, 'type' => $otherIdentifierData['typeCode']->Code, 'owner_org' => $ownerOrganization]
        ];
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
