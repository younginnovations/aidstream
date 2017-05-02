<?php namespace App\Services\CsvImporter\Entities\Activity\Components;

/**
 * Class Grouping
 * @package App\Services\CsvImporter\Entities\Activity\Components
 */
class Grouping
{

    /**
     * Stores grouped period rows
     * @var array
     */
    protected $grouped = [];

    /**
     * Stores raw datas to be grouped
     * @var array
     */
    protected $fields;

    /**
     * Period keys
     * @var array
     */
    protected $keys;

    protected $indicatorKeys = [
        "measure" => "",
        "ascending" => "",
        "indicator_title" => "",
        "indicator_title_language" => "",
        "indicator_description" => "",
        "indicator_description_language" => "",
        "reference_vocabulary" => "",
        "reference_code" => "",
        "reference_uri" => "",
        "baseline_year" => "",
        "baseline_value" => "",
        "baseline_comment" => "",
        "baseline_comment_language" => "",
        "period_start" => "",
        "period_end" => "",
        "target_value" => "",
        "target_location_ref" => "",
        "target_dimension_name" => "",
        "target_dimension_value" => "",
        "target_comment" => "",
        "target_comment_language" => "",
        "actual_value" => "",
        "actual_location_ref" => "",
        "actual_dimension_name" => "",
        "actual_dimension_value" => "",
        "actual_comment" => "",
        "actual_comment_language" => ""
    ];

    /**
     * Grouping constructor.
     * @param array $fields
     * @param array $keys
     */
    public function __construct(array $fields, array $keys)
    {
        $this->fields = $fields;
        $this->keys   = $keys;
    }

    /**
     * Group rows into single Activities.
     */
    public function groupValues()
    {
        foreach($this->indicatorKeys as $index => $value){
            if(!array_key_exists($value, $this->fields)){
                return $this->indicatorKeys;
            }
        }
        $index = - 1;

        foreach ($this->fields[$this->keys[0]] as $i => $row) {

            if (!$this->isSameEntity($index, $i)) {

                $index ++;

            }

            if ($index >= 0) {
                $this->setValue($index, $i);
            }
        }

        return $this->grouped;
    }

    /**
     * Check if the next row is new row or not.
     * @param $i
     * @return bool
     */
    protected function isSameEntity($index, $i)
    {
        if ((is_null($this->fields[$this->keys[0]][$i]) || $this->fields[$this->keys[0]][$i] == '')
            && (is_null($this->fields[$this->keys[1]][$i]) || $this->fields[$this->keys[1]][$i] == '')
        ) {

            return true;
        }

        return false;
    }

    /**
     * Set the provided value to the provided key/index.
     * @param $index
     * @param $i
     */
    protected function setValue($index, $i)
    {
        foreach ($this->fields as $row => $value) {
            if (array_key_exists($row, array_flip($this->keys))) {
                $this->grouped[$index][$row][] = $value[$i];
            }
        }
    }
}
