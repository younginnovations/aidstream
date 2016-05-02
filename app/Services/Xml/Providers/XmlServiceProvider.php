<?php namespace App\Services\Xml\Providers;


/**
 * Class XmlServiceProvider
 * @package App\Services\Xml\Providers
 */
class XmlServiceProvider
{
    /**
     * @var
     */
    protected $validator;
    /**
     * @var
     */
    protected $generator;

    /**
     * Available Xml Generators according to the Version.
     * @var array
     */
    protected $generators = [
        '2.01' => 'App\Core\V201\Element\Activity\XmlGenerator',
        '2.02' => 'App\Core\V202\Xml\Activity\XmlGenerator'
    ];

    /**
     * Available Xml Validators according to the Version.
     * @var array
     */
    protected $validators = [
        '2.01' => 'App\Core\V201\Element\Activity\XmlService',
        '2.02' => 'App\Core\V202\Xml\Activity\XmlService'
    ];

    /**
     * Initialize an Xml Generator instance.
     * @param $version
     * @return $this
     */
    public function initializeGenerator($version)
    {
        $this->generator = app()->make($this->generators[$version]);

        return $this;
    }

    /**
     * Initialize an Xml Validator instance.
     * @param $version
     * @return $this
     */
    public function initializeValidator($version)
    {
        $this->validator = app()->make($this->validators[$version]);

        return $this;
    }

    /**
     * Generate Xml Files.
     * @param $includedActivities
     * @param $filename
     * @return $this
     */
    public function generateXmlFiles($includedActivities, $filename)
    {
        $this->generator->getMergeXml($includedActivities, $filename);

        return $this;
    }

    /**
     * Save the published files records into the database.
     * @param $filename
     * @param $organizationId
     * @param $includedActivities
     * @return $this
     */
    public function save($filename, $organizationId, $includedActivities)
    {
        $this->generator->savePublishedFiles($filename, $organizationId, $includedActivities);

        return $this;
    }

    /**
     * Validate an Xml file against the schema.
     * @param $activity
     * @param $organizationElement
     * @param $activityElement
     * @return mixed
     */
    public function validate($activity, $organizationElement, $activityElement)
    {
        $organization = $activity->organization;

        return $this->validator->validateActivitySchema($activity, $activity->transactions, $activity->results, $organization->settings, $activityElement, $organizationElement, $organization);
    }

    /**
     * Generate an Activity Xml file.
     * @param $activity
     * @param $organizationElement
     * @param $activityElement
     */
    public function generate($activity, $organizationElement, $activityElement)
    {
        $organization = $activity->organization;

        $this->generator->generateActivityXml($activity, $activity->transactions, $activity->results, $organization->settings, $activityElement, $organizationElement, $organization);
    }
}
