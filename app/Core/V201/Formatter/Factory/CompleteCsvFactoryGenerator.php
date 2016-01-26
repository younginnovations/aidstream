<?php namespace App\Core\V201\Formatter\Factory;

use App\Core\V201\Formatter\Factory\Traits\CompleteCsvFactory;


/**
 * Class CompleteCsvFactory
 * @package App\Core\V201\Formatter\Factory
 */
abstract class CompleteCsvFactoryGenerator
{
    use CompleteCsvFactory;

    /**
     * This array holds the names of the Element factory methods.
     * @var array
     */
    protected $elementFactories = [];

    /**
     * Non Element names.
     * @var array
     */
    protected $nonElements = ['transaction', 'result'];

    /**
     * Generate the required Element Factory names.
     * @param array $headers
     * @param array $except
     * @return array
     */
    public function generateElementFactories(array $headers, array $except)
    {
        $this->getFactory($this->generateMetaData($headers, $except));

        return $this->elementFactories;
    }

    /**
     * Generate meta data required for generating Element Factory method names.
     * @param array $headers
     * @param array $except
     * @return array
     */
    protected function generateMetaData(array $headers, array $except)
    {
        $elementMetaData = [];

        foreach (array_diff($headers, $except) as $header) {
            $elementMetaData[] = explode('_', $header);
        }

        return $elementMetaData;
    }

    /**
     * Append the factory method names for all Element factories into the elementFactories array.
     * @param array $elementMetaData
     */
    protected function getFactory(array $elementMetaData)
    {
        array_walk(
            $elementMetaData,
            function ($value, $index) use ($elementMetaData) {
                if ((!in_array($value[1], $this->elementFactories)) && (!in_array($value[1], $this->nonElements))) {
                    $this->elementFactories[] = $value[1];
                }
            }
        );
    }
}
