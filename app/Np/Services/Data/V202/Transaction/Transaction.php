<?php namespace App\Np\Services\Data\V202\Transaction;

use App\Np\Services\Data\Contract\MapperInterface;

/**
 * Class Transaction
 * @package App\Np\Services\Data\Transaction
 */
class Transaction implements MapperInterface
{

    /**
     * Raw data holder for Transaction entity.
     *
     * @var array
     */
    protected $rawData = [];

    /**
     * Transaction constructor.
     * @param array $rawData
     */
    public function __construct(array $rawData)
    {
        $this->rawData  = $rawData;
        $this->template = $this->template($this->loadTemplate());
    }

    /**
     * Template for transaction.
     *
     * @var array|string
     */
    protected $template = [];

    /**
     * Map the raw data to element template.
     *
     * @return array
     */
    public function map()
    {
        $mappedData = [];

        foreach ($this->rawData as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $index => $field) {
                    if ($id = getVal($field, ['id'], null)) {
                        $mappedData[$index]['id'] = $id;
                    }

                    $mappedData[$index]['transaction'] = $this->template;
                    $mappedData[$index]['activity_id']                                                          = getVal($this->rawData, ['activity_id'], null);
                    $mappedData[$index]['transaction']['reference']                                             = getVal($field, ['reference'], null);
                    $mappedData[$index]['transaction']['transaction_type'][0]['transaction_type_code']          = getVal($this->rawData, ['type'], null);
                    $mappedData[$index]['transaction']['transaction_date'][0]['date']                           = getVal($field, ['date'], null);
                    $mappedData[$index]['transaction']['value'][0]['amount']                                    = getVal($field, ['amount'], null);
                    $mappedData[$index]['transaction']['value'][0]['currency']                                  = getVal($field, ['currency'], null);
                    $mappedData[$index]['transaction']['value'][0]['date']                                      = getVal($field, ['date'], null);
                    $mappedData[$index]['transaction']['description'][0]['narrative'][0]['narrative']           = getVal($field, ['description'], null);
                    $mappedData[$index]['transaction']['provider_organization'][0]['narrative'][0]['narrative'] = getVal($field, ['organisation'], null);
                }
            }
        }

        return $mappedData;
    }

    /**
     * Map database data into frontend compatible format.
     *
     * @return mixed
     */
    public function reverseMap()
    {
        $mappedData = [];

        foreach ($this->rawData as $index => $field) {
            $mappedData[$index]['id']           = getVal($field, ['id'], '');
            $mappedData[$index]['reference']    = getVal($field, ['transaction', 'reference'], '');
            $mappedData[$index]['date']         = getVal($field, ['transaction', 'transaction_date', 0, 'date'], '');
            $mappedData[$index]['amount']       = getVal($field, ['transaction', 'value', 0, 'amount'], '');
            $mappedData[$index]['currency']     = getVal($field, ['transaction', 'value', 0, 'currency'], '');
            $mappedData[$index]['description']  = getVal($field, ['transaction', 'description', 0, 'narrative', 0, 'narrative'], '');
            $mappedData[$index]['organisation'] = getVal($field, ['transaction', 'provider_organization', 0, 'narrative', 0, 'narrative'], '');
        }

        return $mappedData;
    }

    /**
     * Provides V202 template
     *
     * @return string
     */
    protected function loadTemplate()
    {
        return file_get_contents(app_path('Services/XmlImporter/Foundation/Support/Templates/V202.json'));
    }

    protected function template($loadTemplate)
    {
        return getVal(json_decode($loadTemplate, true), ['transaction'], []);
    }
}
