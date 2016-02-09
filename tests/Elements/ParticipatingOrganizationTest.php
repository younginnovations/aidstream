<?php namespace Test\Elements;

use App\Migration\Elements\ParticipatingOrganization;
use Test\AidStreamTestCase;
use Test\Elements\DataProviders\ParticipatingOrganizationDataProvider;

class ParticipatingOrganizationTest extends AidStreamTestCase
{
    use ParticipatingOrganizationDataProvider;

    protected $participatingOrg;
    protected $expectedOutput = [];
    protected $participatingOrgNarratives;
    protected $organizationRoleCode;
    protected $identifier;
    protected $organizationTypeCode;

    public function setUp()
    {
        parent::setUp();
        $this->participatingOrgNarratives = $this->getParticipatingOrganizationNarratives();
        $this->organizationRoleCode       = $this->getParticipatingOrganizationRoleCode();
        $this->identifier                 = $this->getParticipatingOrganizationIdentifier();
        $this->organizationTypeCode       = $this->getParticipatingOrganizationTypeCode();
        $this->participatingOrg           = new ParticipatingOrganization();
    }

    /** {@test} */
    public function itShouldFormatParticipatingOrganization()
    {
        $narratives = $this->getTestNarratives(['testNarrative1', 'testNarrative2'], ['testLanguage1', 'testLanguage2']);

        $this->expectedOutput = $this->formatParticipatingOrganization($this->participatingOrgNarratives, $this->organizationRoleCode, $this->identifier, $this->organizationTypeCode, $narratives);

        $this->assertEquals(
            $this->expectedOutput,
            $this->participatingOrg->format($this->participatingOrgNarratives, $this->organizationRoleCode, $this->identifier, $this->organizationTypeCode, $narratives)
        );
    }

    /** {@test} */
    public function itShouldFormatParticipatingOrganizationWithEmptyNarratives()
    {
        $this->participatingOrgNarratives = [];
        $narratives                       = $this->getTestNarratives();

        $this->expectedOutput = $this->formatParticipatingOrganization($this->participatingOrgNarratives, $this->organizationRoleCode, $this->identifier, $this->organizationTypeCode, $narratives);

        $this->assertEquals(
            $this->expectedOutput,
            $this->participatingOrg->format($this->participatingOrgNarratives, $this->organizationRoleCode, $this->identifier, $this->organizationTypeCode, $narratives)
        );
    }

    public function formatParticipatingOrganization($participatingOrgNarratives, $organizationRoleCode, $identifier, $organizationTypeCode, $narratives)
    {
        if (empty($participatingOrgNarratives)) {  // format incase of no narrative
            $narrative = [['narrative' => "", 'language' => ""]];
        } else {
            $narrative = $narratives;
        }

        return ['organization_role' => $organizationRoleCode, 'identifier' => $identifier, 'organization_type' => $organizationTypeCode, 'narrative' => $narrative];
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
