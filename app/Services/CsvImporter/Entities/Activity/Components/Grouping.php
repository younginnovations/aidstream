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