<?php namespace App\Core\V203\Formatter;

use App\Core\V201\Formatter\CompleteCsvDataFormatter as V201CompleteCsvDataFormatter;
use App\Models\Activity\Activity;
use BadMethodCallException;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;
use Psr\Log\LoggerInterface;

/**
 * Class CompleteCsvDataFormatter
 * @package App\Core\V202\Formatter
 */
class CompleteCsvDataFormatter extends V201CompleteCsvDataFormatter
{
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
     * CompleteCsvDataFormatter constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->getHeaders(Session::get('version'));
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
        } catch (Exception $exception) {
            $this->logger->error($exception);
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

        if ($activity->default_field_values[0]['humanitarian'] == 0) {
            $this->data[$activity->id]['Activity_humanitarian'] = 'No';
        } else {
            $this->data[$activity->id]['Activity_humanitarian'] = 'Yes';
        }

        $this->rememberUsedHeaders('Activity_last_updated_datetime');
        $this->rememberUsedHeaders('Activity_iatiidentifier_text');
        $this->rememberUsedHeaders('Activity_humanitarian');
    }
}
