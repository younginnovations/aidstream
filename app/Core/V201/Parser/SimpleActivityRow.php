<?php namespace App\Core\V201\Parser;

    /**
     * Class SimpleActivityRow
     * @package App\Core\V201\Parser
     */
/**
 * Class SimpleActivityRow
 * @package App\Core\V201\Parser
 */
class SimpleActivityRow
{
    /**
     * @var ActivityCsvFieldChecker
     */
    protected $activityCsvFieldChecker;
    /**
     * @var ActivityDataParser
     */
    protected $activityDataParser;

    /**
     * @param ActivityCsvFieldChecker $activityCsvFieldChecker
     * @param ActivityDataParser      $activityDataParser
     */
    public function __construct(ActivityCsvFieldChecker $activityCsvFieldChecker, ActivityDataParser $activityDataParser)
    {
        $this->activityCsvFieldChecker = $activityCsvFieldChecker;
        $this->activityDataParser      = $activityDataParser;
    }

    /**
     * return activity rows with validation messages
     * @param $row
     * @return mixed
     */
    public function getVerifiedRow($row)
    {
        $checker = $this->activityCsvFieldChecker->init($row);
        $checker->checkIdentifier();
        $checker->checkTitle();
        $checker->checkDescription();
        $checker->checkStatus();
        $checker->checkDate();
        $checker->checkParticipatingOrg();
        $checker->checkRecipientCountryOrRegion();
        $checker->checkSector();

        $errors             = $checker->getErrors();
        $activity['data']   = $row;
        $activity['errors'] = $errors;

        return $activity;
    }

    /**
     * prepare activity data and save
     * @param array $activity
     * @return static
     */
    public function save(array $activity)
    {
        $parser = $this->activityDataParser->init($activity);
        $parser->setIdentifier();
        $parser->setTitle();
        $parser->setDescription();
        $parser->setStatus();
        $parser->setDate();
        $parser->setParticipatingOrg();
        $parser->setRecipientCountry();
        $parser->setRecipientRegion();
        $parser->setSector();

        return $parser->save();
    }
}
