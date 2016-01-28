<?php namespace App\Core\V201\Formatter;

use App\Core\V201\Formatter\Factory\CompleteCsvFactoryGenerator;
use App\Core\V201\Formatter\Factory\Traits\RelationDataPacker;
use App\Core\V201\Formatter\Factory\Traits\RelationDataProvider;
use App\Models\Activity\Activity;
use BadMethodCallException;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Psr\Log\LoggerInterface;

/**
 * Class CompleteCsvDataFormatter
 * @package App\Core\V201\Formatter
 */
class CompleteCsvDataFormatter extends CompleteCsvFactoryGenerator
{
    use RelationDataPacker, RelationDataProvider;

    /**
     * This array holds all the data required by Complete Csv.
     * @var array
     */
    protected $data = [];

    /**
     * This array holds the keys, not required by the Csv generation procedure.
     * @var array
     */
    protected $except = [];

    /**
     * Headers required by Complete Csv.
     * @var array
     */
    protected $headers = [];

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Headers and Keys (header => key) for Default Field Values of an Activity.
     * @var array
     */
    protected $defaultFieldValues = [
        'Activity_xml_lang'         => 'default_language',
        'Activity_default_currency' => 'default_currency',
        'Activity_linked_data_uri'  => 'linked_data_uri',
        'Activity_hierarchy'        => 'default_hierarchy'
    ];

    /**
     * Path for the Csv template file for V201.
     */
    CONST TEMPLATE_PATH = 'Core/V201/Template/Csv/complete.csv';

    /**
     * CompleteCsvDataFormatter constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->getHeaders();
        $this->logger = $logger;
    }

    /**
     * Format data for Complete Csv generation.
     * @param Collection $activities
     * @return array
     */
    public function format(Collection $activities)
    {
        try {
            if ($activities->isEmpty()) {
                return false;
            }

            $this->prepareCsv($activities);

            $elementFactories = $this->generateElementFactories(
                array_keys(
                    array_first(
                        $this->data,
                        function ($index, $value) {
                            return $value;
                        }
                    )
                ),
                $this->except
            );

            return $this->fillActivityElementData($elementFactories, $activities)
                        ->fillTransactionData($activities)
                        ->fillResultData($activities);
        } catch (BadMethodCallException $exception) {
            $this->logger
                ->error(
                    sprintf('Error: BadMethodCallException - %s', $exception->getMessage()),
                    ['trace' => $exception->getTraceAsString()]
                );
        } catch (Exception $exception) {
            $this->logger
                ->error(
                    sprintf('Csv not generated due to %s', $exception->getMessage()),
                    ['trace' => $exception->getTraceAsString()]
                );
        }

        return null;
    }

    /**
     * Prepare Csv with default values and necessary headers.
     * @param $activities
     */
    protected function prepareCsv($activities)
    {
        foreach ($activities as $activity) {
            $this->data[$activity->id] = $this->headers;

            array_walk(
                $this->headers,
                function ($value, $index) use ($activity) {
                    $this->data[$activity->id][$index] = '';
                }
            );

            $this->setDefaultValues($activity);
        }
    }

    /**
     * Set the Default Values for an Activity.
     * @param $activity
     */
    protected function setDefaultValues(Activity $activity)
    {
        $this->setDefaultFieldValues($activity);

        $this->data[$activity->id]['Activity_last_updated_datetime'] = $activity->updated_at;
        $this->data[$activity->id]['Activity_iatiidentifier_text']   = $activity->identifier['iati_identifier_text'];

        $this->rememberUsedHeaders('Activity_last_updated_datetime');
        $this->rememberUsedHeaders('Activity_iatiidentifier_text');
    }

    /**
     * Remember headers whose values have already been filled.
     * @param string $header
     */
    protected function rememberUsedHeaders($header)
    {
        if (!in_array($header, $this->except)) {
            array_push($this->except, $header);
        }
    }

    /**
     * Generate Headers for Complete Csv.
     * @param null $version
     */
    protected function getHeaders($version = null)
    {
        if (is_null($version)) {
            $templatePath = self::TEMPLATE_PATH;
        } else {
            $templatePath = sprintf('Core/%s/Template/Csv/complete.csv', $version);
        }

        Excel::load(
            sprintf('%s/%s', app_path(), $templatePath),
            function ($reader) {
                foreach ($reader->first() as $key => $value) {
                    $this->headers[ucfirst($key)]         = '';
                    $this->data['headers'][ucfirst($key)] = ucfirst($key);
                }
            }
        );
    }

    /**
     * Fill Activity Elements data into the array holding the Csv data.
     * @param array      $elementFactories
     * @param Collection $activities
     * @return $this
     */
    protected function fillActivityElementData(array $elementFactories, Collection $activities)
    {
        foreach ($elementFactories as $factory) {
            if (!method_exists($this, $factory)) {
                throw new BadMethodCallException();
            }

            $this->data = $this->$factory(array_except($this->data, 'headers'), $activities);
        }

        return $this;
    }

    /**
     * Set default fields values for an Activity.
     * @param $activity
     */
    protected function setDefaultFieldValues($activity)
    {
        array_walk(
            $this->defaultFieldValues,
            function ($activityHeader, $header) use ($activity) {
                $this->data[$activity->id][$header] = $activity->default_field_values[0][$activityHeader];
                $this->rememberUsedHeaders($header);
            }
        );
    }

    /**
     * Fill Transaction specific data.
     * @param Collection $activities
     * @return $this
     */
    protected function fillTransactionData(Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId            = $activity->id;
            $transactions          = $activity->transactions;
            $transactionDataHolder = $this->getTransactionDataTemplate();

            foreach ($transactions as $transaction) {
                $this->data[$activityId]['Activity_transaction_flowtype_code']    = $this->concatenateRelation($transaction, 'transaction', 'flow_type', true, 'flow_type');
                $this->data[$activityId]['Activity_transaction_financetype_code'] = $this->concatenateRelation($transaction, 'transaction', 'finance_type', true, 'finance_type');
                $this->data[$activityId]['Activity_transaction_aidtype_code']     = $this->concatenateRelation($transaction, 'transaction', 'aid_type', true, 'aid_type');
                $this->data[$activityId]['Activity_transaction_tiedstatus_code']  = $this->concatenateRelation($transaction, 'transaction', 'tied_status', true, 'tied_status_code');

                $transactionDataHolder = $this->transactionData($transaction);
            }

            $this->data = $this->packTransactionData($activityId, $transactionDataHolder, $this->data);
        }

        return $this;
    }

    /**
     * Fill Result specific data.
     * @param Collection $activities
     * @return array
     */
    protected function fillResultData(Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId     = $activity->id;
            $results        = $activity->results;
            $resultMetaData = $this->getResultDataTemplate($activityId);

            foreach ($results as $result) {
                $resultMetaData = $this->resultData($activityId, $result, $resultMetaData);
            }

            $this->data = $this->packResultsData($activityId, $resultMetaData, $this->data);
        }

        return $this->data;
    }
}
