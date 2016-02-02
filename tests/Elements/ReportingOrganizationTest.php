<?php namespace Test\Elements;

use App\Migration\Elements\ReportingOrganization;
use Test\AidStreamTestCase;

class ReportingOrganizationTest extends AidStreamTestCase
{
    protected $reportingOrganization;
    protected $testInput;
    protected $expectedOutput;

    public function setUp()
    {
        parent::setUp();
        $this->testInput             = [];
        $this->reportingOrganization = new ReportingOrganization();
    }

    /** {@test} */
    public function itShouldFormatReportingOrganization()
    {
        $this->testInput      = $this->getTestInput();
        $this->expectedOutput = $this->formatReportingOrganization($this->testInput);

        $this->assertEquals($this->expectedOutput, $this->reportingOrganization->format($this->testInput));
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    protected function getTestInput()
    {
        return [
            'reporting_organization_identifier' => 'testIdentifier',
            'reporting_organization_type'       => 1,
            'narrative'                         => [
                [
                    'narrative' => 'test narrative',
                    'language'  => 'testLanguage'
                ]
            ]
        ];
    }

    protected function formatReportingOrganization($testInput)
    {
        return $testInput;
    }
}
