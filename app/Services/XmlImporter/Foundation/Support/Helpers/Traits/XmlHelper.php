<?php namespace App\Services\XmlImporter\Foundation\Support\Helpers\Traits;

/**
 * Class XmlHelper
 * @package App\Services\XmlImporter\Foundation\Support\Helpers\Traits
 */
trait XmlHelper
{
    /**
     * Returns lat and long for location field.
     *
     * @param $values
     * @return array
     */
    protected function latAndLong($values)
    {
        $narrative = $this->value($values, 'point');
        $data      = ['latitude' => '', 'longitude' => ''];
        foreach ($narrative as $latLong) {
            $narrative = $latLong['narrative'];
            if ($narrative != "") {
                $text = explode(" ", $latLong['narrative']);
                if (count($text) == 2) {
                    $data['latitude']  = $text[0];
                    $data['longitude'] = $text[1];
                }
            }
        }

        return $data;
    }

    /**
     * Filter the provided key and groups the values in array.
     *
     * $values = data['value']
     * @param      $values
     * @param null $key
     * @return array
     */
    protected function filterValues($values, $key = null)
    {
        $index = 0;
        $data  = [[$key => '']];

        $values = $values ?: [];

        foreach ($values as $value) {
            if ($this->name($value['name']) == $key) {
                $data[$index][$key] = $this->value($value);
                $index ++;
            }
        }

        return $data;
    }

    /**
     *  Filter the provided key, Convert the provided template to array and groups the attributes.
     *
     * @param       $values
     * @param null  $key
     * @param array $template
     * @return array
     */
    protected function filterAttributes($values, $key = null, array $template)
    {
        $values = $values ?: [];

        $index = 0;
        $data  = $this->templateToArray($template);
        foreach ($values as $value) {
            if ($this->name($value['name']) == $key) {
                foreach (getVal($value, ['attributes'], []) as $attributeKey => $attribute) {
                    if ($attributeKey == 'indicator-uri') {
                        $attributeKey = 'indicator_uri';
                    }
                    (!array_key_exists($attributeKey, array_flip($template))) ?: $data[$index][$attributeKey] = $attribute;
                }
                $index ++;
            }
        }

        return $data;
    }

    /**
     * Converts the provided template into empty key => value pairs.
     *
     * @param array $template
     * @return array
     */
    protected function templateToArray(array $template)
    {
        if (is_array($template)) {
            $data = [array_flip($template)];
            foreach ($data as $index => $values) {
                foreach ($values as $key => $value) {
                    $data[$index][$key] = "";
                }
            }

            return $data;
        }

        return [];
    }

    /**
     * Get the value from the array.
     * If key is provided then the $fields = $data['value'] else $fields = $data.
     * If key is provided then the value is fetched from the value field of the data.
     * If the value is array then narrative is returned else only the value is returned.
     *
     * @param array $fields
     * @param null  $key
     * @return array|mixed|string
     */
    protected function value(array $fields, $key = null)
    {
        if (!$key) {
            return getVal($fields, ['value'], '');
        }
        foreach ($fields as $field) {
            if ($this->name($field['name']) == $key) {
                if (is_array($field['value'])) {
                    return $this->narrative($field);
                }

                return getVal($field, ['value'], '');
            }
        }

        return [['narrative' => '', 'language' => '']];
    }

    /**
     * Returns the all narrative present in the provided $subElement.
     *
     * @param $subElement
     * @return mixed
     */
    protected function narrative($subElement)
    {
        $field = [['narrative' => '', 'language' => '']];
        if (is_array(getVal((array) $subElement, ['value'], []))) {
            foreach (getVal((array) $subElement, ['value'], []) as $index => $value) {
                $field[$index] = [
                    'narrative' => trim(getVal($value, ['value'], '')),
                    'language'  => $this->attributes($value, 'lang')
                ];
            }

            return $field;
        } else {
            $field[0] = [
                'narrative' => trim(getVal($subElement, ['value'], '')),
                'language'  => $this->attributes($subElement, 'lang')
            ];

            return $field;
        }
    }

    /**
     * Get the name of the current Xml element.
     *
     * @param      $element
     * @param bool $snakeCase
     * @return string
     */
    protected function name($element, $snakeCase = false)
    {
        if (is_array($element)) {
            $camelCaseString = camel_case(str_replace('{}', '', $element['name']));

            return $snakeCase ? snake_case($camelCaseString) : $camelCaseString;
        }

        $camelCaseString = camel_case(str_replace('{}', '', $element));

        return $snakeCase ? snake_case($camelCaseString) : $camelCaseString;
    }

    /**
     * Returns the attributes of the provided element.
     * If key is provided then the attribute equal to the key is returned.
     * If fieldName and key both are provided then the attributes inside value is returned.
     *
     * @param array $element
     * @param null  $key
     * @param null  $fieldName
     * @return mixed|string
     */
    public function attributes(array $element, $key = null, $fieldName = null)
    {
        if (!$key) {
            return getVal($element, ['attributes'], []);
        }

        if ($fieldName && $key) {
            return $this->getSpecificAttribute($element, $fieldName, $key);
        }

        return $this->getLanguageAttribute($element, $key);
    }

    /**
     * Get specific attributes for Xml element.
     *
     * @param array $element
     * @param       $fieldName
     * @param       $key
     * @return mixed|string
     */
    protected function getSpecificAttribute(array $element, $fieldName, $key)
    {
        $value = "";

        foreach (getVal($element, ['value'], []) as $value) {
            if ($fieldName == $this->name($value['name'])) {
                return $this->attributes($value, $key);
            } else {
                $value = "";
            }
        }

        return $value;
    }

    /**
     * Get the Language attribute from a specific Xml element.
     *
     * @param array $element
     * @param       $key
     * @return string
     */
    protected function getLanguageAttribute(array $element, $key)
    {
        $value = getVal($element, ['attributes'], []);

        if ($value) {
            foreach ($value as $itemKey => $item) {
                if ($key == substr($itemKey, - 4, 4)) {
                    return $item;
                }
            }

            return getVal($element, ['attributes', $key], '');
        }

        return '';
    }

    /**
     * Returns narratives of provided key. (Mostly for V1 XML)
     * @param array $fields
     * @param       $key
     * @return array
     */
    protected function groupNarrative(array $fields, $key = null)
    {
        $narrative = [['narrative' => '', 'language' => '']];
        $index     = 0;

        if ($key) {
            foreach ($fields as $field) {
                if ($this->name($field['name']) == $key) {
                    $narrative[$index] = getVal($this->narrative($field), [0]);
                    $index ++;
                }
            }
        } else {
            $narrative = $this->narrative($fields);
        }


        return $narrative;
    }
}