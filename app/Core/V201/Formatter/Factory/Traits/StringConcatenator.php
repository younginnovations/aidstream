<?php namespace App\Core\V201\Formatter\Factory\Traits;


use Illuminate\Database\Eloquent\Model;

/**
 * Trait StringConcatenator
 *
 * Concatenates data into string of different forms required for Complete Csv generation.
 * @package App\Core\V201\Formatter\Factory\Traits
 */
trait StringConcatenator
{
    /**
     * Concatenate the values in a specific $key of an $iterable with a ';'
     * @param array $iterable
     * @param       $key
     * @param null  $nested
     * @param null  $nestedKey
     * @return string
     */
    protected function concatenateIntoString(array $iterable = null, $key, $nested = null, $nestedKey = null)
    {
        if (!is_null($iterable)) {
            $placeholders = [];

            if (!$nested) {
                foreach ($iterable as $iterate) {
                    $placeholders[] = (is_array($iterate) && array_key_exists($key, $iterate)) ? $iterate[$key] : '';
                }
            } else {
                foreach ($iterable as $iterate) {
                    if (is_array($iterate) && array_key_exists($key, $iterate)) {
                        foreach ($iterate[$key] as $nest) {
                            $placeholders[] = (is_array($nest) && array_key_exists($nestedKey, $nest)) ? $nest[$nestedKey] : '';
                        }
                    }
                }
            }

            return implode(';', $placeholders);
        }
    }

    /**
     * Concatenate values from different relations of an Activity.
     * @param Model $relatedModel
     * @param       $relatedColumn
     * @param       $key
     * @param null  $nested
     * @param null  $nestedKey
     * @return mixed
     */
    protected function concatenateRelation(Model $relatedModel, $relatedColumn, $key, $nested = null, $nestedKey = null)
    {
        $relatedModel = $relatedModel->$relatedColumn;

        if (!$nested && !$nestedKey) {
            return getVal($relatedModel, [$key]);
        }

        return getVal($relatedModel, [$key, 0, $nestedKey], '');
    }

    /**
     * Concatenate Indicator values with multiple nesting levels.
     * @param array $indicators
     * @param       $first
     * @param       $second
     * @param null  $third
     * @param null  $fourth
     * @param null  $fifth
     * @return string
     */
    protected function concatenateIndicator(array $indicators, $first, $second, $third = null, $fourth = null, $fifth = null)
    {
        $temp = [];

        if (!$fourth && !$fifth) {
            if (!$third) {
                foreach ($indicators as $indicator) {
                    $temp[] = getVal($indicator, [$first, 0, $second], '');
                }

                return implode(';', $temp);
            }

            foreach ($indicators as $indicator) {
                foreach (getVal($indicator, [$first, 0, $second], []) as $nest) {
                    $temp[] = getVal($nest, [$third], '');
                }
            }

            return implode(';', $temp);
        }

        foreach ($indicators as $indicator) {
            foreach (getVal($indicator, [$first, 0, $second], []) as $superNest) {
                foreach (getVal($superNest, [$third], []) as $nest) {
                    if (!$fifth) {
                        $temp[] = getVal($nest, [$fourth], '');
                    } else {
                        foreach (getVal($nest, [$fourth], []) as $n) {
                            $temp[] = getVal($n, [$fifth], '');
                        }
                    }
                }
            }
        }

        return implode(';', $temp);
    }
}
