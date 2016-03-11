<?php namespace App\Http\Controllers\Complete\Traits;

/**
 * Class InterElementValidator
 * @package App\Http\Controllers\Complete\Activity\Traits
 */
trait InterElementValidator
{
    /**
     * Check if Recipient Country and Recipient Region elements meet the criteria specified by the IATI Standard.
     * @param array $activity
     * @param array $data
     * @return bool
     */
    protected function recipientCountryAndRegionAreInvalid(array $activity, array $data)
    {
        return ($this->recipientCountryOrRegionAreIncludedIn($activity) && (!$this->isEmpty($data['transaction'][0]['recipient_country'][0]) || !$this->isEmpty(
                    $data['transaction'][0]['recipient_region'][0]
                )));
    }

    /**
     * Check if Recipient Country or Region have been added on Activity level.
     * @param array $activity
     * @return bool
     */
    protected function recipientCountryOrRegionAreIncludedIn(array $activity)
    {
        $country = false;
        $region  = false;

        if (array_key_exists('recipient_country', $activity)) {
            $country = boolval($activity['recipient_country']);
        }

        if (array_key_exists('recipient_region', $activity)) {
            $region = boolval($activity['recipient_region']);
        }

        return ($country || $region);
    }

    /**
     * Check if the passed data provided through the form is empty.
     * i.e., to check if all fields are empty.
     *
     * @param array $formInput
     * @return bool
     */
    protected function isEmpty(array $formInput)
    {
        foreach ($formInput as $data) {
            if (!is_array($data)) {
                if (boolval($data)) {
                    return false;
                } else {
                    continue;
                }
            }

            if (is_array($data)) {
                foreach ($data as $datum) {
                    if (is_array($datum)) {
                        foreach ($datum as $value) {
                            if (is_array($value)) {
                                foreach ($value as $index) {
                                    if (boolval($index)) return false;
                                }
                            } else {
                                if (boolval($value)) return false;
                            }
                        }
                    }
                }
            }
        }

        return true;
    }
}
