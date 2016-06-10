<?php namespace App\Core\V201\Parser;

use Maatwebsite\Excel\Readers\LaravelExcelReader;

/**
 * Class SimpleActivityDemo
 * @package App\Core\V201\Parser
 */
class SimpleActivityDemo
{
    /**
     * @var int
     */
    protected $headerCount = 17;
    /**
     * @var SimpleActivityDemoRow
     */
    protected $simpleActivityDemoRow;

    /**
     * @param SimpleActivityDemoRow $simpleActivityDemoRow
     */
    public function __construct(SimpleActivityDemoRow $simpleActivityDemoRow)
    {
        $this->simpleActivityDemoRow = $simpleActivityDemoRow;
    }

    /**
     * Checks if $csvData has SimpleActivityDemo template
     * @param array $firstRow
     * @return SimpleActivityDemo|bool
     */
    public function getTemplate(array $firstRow)
    {
        if ((count($firstRow) == $this->headerCount)) {
            return $this;
        }

        return false;
    }

    /**
     * return imported activity with validation messages
     * @param LaravelExcelReader $csvData
     * @return array
     */
    public function getVerifiedActivities(LaravelExcelReader $csvData)
    {
        $csvData    = $csvData->toArray();
        $activities = [];
        foreach ($csvData as $row) {
            $activities[] = $this->simpleActivityDemoRow->getVerifiedRow($row);
        }

        $activities['duplicate_identifiers'] = $this->getDuplicateIdentifiers($csvData);

        return $activities;
    }

    /**
     * return duplicate identifiers
     * @param $csvData
     * @return array
     */
    protected function getDuplicateIdentifiers($csvData)
    {
        $identifierList       = [];
        $duplicateIdentifiers = [];
        foreach ($csvData as $row) {
            $identifier = $row['activity_identifier'];
            !in_array($identifier, $identifierList) ?: $duplicateIdentifiers[] = $identifier;
            $identifierList[] = $identifier;
        }

        return array_unique($duplicateIdentifiers);
    }

    /**
     * save selected activities
     * @param array $activities
     * @return array
     */
    public function save(array $activities)
    {
        $importedActivities = [];
        foreach ($activities as $activity) {
            $importedActivities[] = $this->simpleActivityDemoRow->save(json_decode($activity, true));
        }

        return $importedActivities;
    }
}
