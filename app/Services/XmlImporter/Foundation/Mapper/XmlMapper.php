<?php namespace App\Services\XmlImporter\Foundation\Mapper;

use App\Services\XmlImporter\Foundation\Support\Factory\Mapper as MapperFactory;
use App\Services\XmlImporter\Foundation\Support\Helpers\Traits\XmlHelper;

/**
 * Class XmlMapper
 * @package App\Services\XmlImporter\Foundation\Mapper\Version\V2
 */
class XmlMapper
{
    use XmlHelper, MapperFactory;

    /**
     * @var
     */
    protected $activity;

    /**
     * @var array
     */
    protected $iatiActivity = [];

    /**
     * @var array
     */
    protected $transaction = [];

    /**
     * @var
     */
    protected $transactionElement;

    /**
     * @var
     */
    protected $resultElement;

    /**
     * @var array
     */
    protected $result = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $activityElements = [
        'iatiIdentifier',
        'otherIdentifier',
        'reportingOrg',
        'title',
        'description',
        'activityStatus',
        'activityDate',
        'activityScope',
        'contactInfo',
        'participatingOrg',
        'recipientCountry',
        'recipientRegion',
        'sector',
        'collaborationType',
        'defaultFlowType',
        'defaultFinanceType',
        'defaultAidType',
        'defaultTiedStatus',
        'budget',
        'location',
        'plannedDisbursement',
        'countryBudgetItems',
        'documentLink',
        'policyMarker',
        'conditions',
        'legacyData',
        'humanitarianScope',
        'collaborationType',
        'capitalSpend',
        'relatedActivity'
    ];

    protected $version;

    /**
     * Xml constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Assign a version to initialize the XmlMapper components.
     *
     * @param string $version
     * @return $this
     */
    public function assign($version = '2.02')
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Map raw Xml data into AidStream database compatible data for import.
     *
     * @param array $activities
     * @param       $template
     * @return $this|bool
     */
    public function map(array $activities, $template)
    {
        $mappedData = [];
        foreach ($activities as $index => $activity) {
            $this->initComponents();
            $mappedData[$index]                         = $this->activity->map($this->filter($activity, 'iatiActivity'), $template);
            $mappedData[$index]['default_field_values'] = $this->defaultFieldValues($activity, $template);
            $mappedData[$index]['transactions']         = $this->transactionElement->map($this->filter($activity, 'transaction'), $template);
            $mappedData[$index]['result']               = $this->resultElement->map($this->filter($activity, 'result'), $template);
        }
        $this->data = $mappedData;

        return $this;
    }

    /**
     * Returns false if the xml is not activity file.
     * @param $activities
     * @return bool
     */
    public function isValidActivityFile($activities)
    {
        foreach ($activities as $activity) {
            if ($this->name(getVal($activity, ['name'])) != 'iatiActivity') {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the mapped Xml data.
     *
     * @return array
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Store the mapped Xml data in a temporary json file.
     */
    public function keep()
    {

    }

    /**
     * Filter the default field values from the xml data.
     *
     * @param array $activity
     * @param       $template
     * @return mixed
     */
    protected function defaultFieldValues($activity = [], $template)
    {
        $defaultFieldValues[0]                      = $template['default_field_values'];
        $defaultFieldValues[0]['default_currency']  = $this->attributes($activity, 'default-currency');
        $defaultFieldValues[0]['default_language']  = $this->attributes($activity, 'language');
        $defaultFieldValues[0]['default_hierarchy'] = $this->attributes($activity, 'hierarchy');
        $defaultFieldValues[0]['linked_data_uri']   = $this->attributes($activity, 'linked-data-uri');
        $defaultFieldValues[0]['humanitarian']      = $this->attributes($activity, 'humanitarian');

        return $defaultFieldValues;
    }

    /**
     * Filter raw Xml data for a certain element with a specific elementName.
     *
     * @param $xmlData
     * @param $elementName
     */
    protected function filter($xmlData, $elementName)
    {
        foreach ($this->value($xmlData) as $subElement) {
            if ($elementName == 'transaction') {
                $this->filterForTransactions($subElement, $elementName);
            } elseif ($elementName == 'result') {
                $this->filterForResults($subElement, $elementName);
            } elseif ($elementName == 'iatiActivity') {
                $this->filterForActivity($subElement, $elementName);
            }
        }

        return $this->{$elementName};
    }

    /**
     * Filter data for Activity Elements.
     *
     * @param $subElement
     * @param $elementName
     */
    protected function filterForActivity($subElement, $elementName)
    {
        if (in_array($this->name($subElement), $this->activityElements)) {
            $this->{$elementName}[] = $subElement;
        }
    }

    /**
     * Filter data for Transactions Elements.
     *
     * @param $subElement
     * @param $elementName
     */
    protected function filterForTransactions($subElement, $elementName)
    {
        if ($this->name($subElement) == $elementName) {
            $this->{$elementName}[] = $subElement;
        }
    }

    /**
     * @param $subElement
     * @param $elementName
     */
    protected function filterForResults($subElement, $elementName)
    {
        if ($this->name($subElement) == $elementName) {
            $this->{$elementName}[] = $subElement;
        }
    }
}
