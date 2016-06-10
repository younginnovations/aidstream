<?php namespace App\Core\V201\Parser;

use Maatwebsite\Excel\Readers\LaravelExcelReader;

/**
 * Class SimpleActivity
 * @package App\Core\V201\Parser
 */
class SimpleActivity
{
    /**
     * @var int
     */
    protected $headerCount = 16;
    /**
     * @var SimpleActivityRow
     */
    protected $simpleActivityRow;

    /**
     * @param SimpleActivityRow $simpleActivityRow
     */
    public function __construct(SimpleActivityRow $simpleActivityRow)
    {
        $this->simpleActivityRow = $simpleActivityRow;
    }

    /**
     * Checks if $csvData has SimpleActivity template
     * @param array $firstRow
     * @return SimpleActivity|bool
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
            $activities[] = $this->simpleActivityRow->getVerifiedRow($row);
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
            $importedActivities[] = $this->simpleActivityRow->save(json_decode($activity, true));
        }

        return $importedActivities;
    }
}
