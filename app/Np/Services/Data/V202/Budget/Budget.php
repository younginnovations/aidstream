<?php namespace App\Np\Services\Data\V202\Budget;

use App\Np\Services\Data\Contract\MapperInterface;

/**
 * Class Budget
 * @package App\Np\Services\Data\Budget
 */
class Budget implements MapperInterface
{
    /**
     * Code for budget type
     */
    const BUDGET_TYPE = 1;

    /**
     * Code for budget status
     */
    const BUDGET_STATUS = 2;

    /**
     * Raw data holder for Budget entity.
     *
     * @var array
     */
    protected $rawData = [];

    /**
     * Contains mapped data.
     *
     * @var array
     */
    protected $mappedData = [];

    /**
     * @var int
     */
    protected $index = 0;

    /**
     * Budget constructor.
     *
     * @param array $rawData
     */
    public function __construct(array $rawData)
    {
        $this->rawData = $rawData;
    }

    /**
     * Map the raw data to element template.
     *
     * @return array
     */
    public function map()
    {
        foreach ($this->rawData as $key => $value) {
            if(is_array($value)){
                foreach ($value as $index => $field) {
                    $this->mappedData['budget'][$index]['budget_type']             = self::BUDGET_TYPE;
                    $this->mappedData['budget'][$index]['status']                  = self::BUDGET_STATUS;
                    $this->mappedData['budget'][$index]['period_start'][0]['date'] = $value[$index]['startDate'];
                    $this->mappedData['budget'][$index]['period_end'][0]['date']   = $value[$index]['endDate'];
                    $this->mappedData['budget'][$index]['value'][0]['amount']      = $value[$index]['amount'];
                    $this->mappedData['budget'][$index]['value'][0]['currency']    = $value[$index]['currency'];
                    $this->mappedData['budget'][$index]['value'][0]['value_date']  = Date('Y-m-d');
                }
            }
        }

        return $this->mappedData;
    }

    /**
     * Map database data into frontend compatible format.
     *
     * @return mixed
     */
    public function reverseMap()
    {
        foreach (getVal($this->rawData, ['budget'], []) as $index => $field) {
            $this->mappedData['budget'][$index]['startDate'] = getVal($field, ['period_start', 0, 'date'], '');
            $this->mappedData['budget'][$index]['endDate']   = getVal($field, ['period_end', 0, 'date'], '');
            $this->mappedData['budget'][$index]['amount']    = getVal($field, ['value', 0, 'amount'], '');
            $this->mappedData['budget'][$index]['currency']  = getVal($field, ['value', 0, 'currency'], '');
        }

        return $this->mappedData;
    }
}
