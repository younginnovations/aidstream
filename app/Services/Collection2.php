<?php namespace App\Services;

use Illuminate\Support\Collection;

class Collection2 extends Collection
{
    public function __construct($items = [])
    {
        parent::__construct($items);
    }

    /**
     * Filter items by the given key value pair.
     *
     * @param  string $key
     * @param  mixed  $value
     * @param  bool   $strict
     * @return static
     */
    public function matchRegistrar($key, $value, $strict = true)
    {
        return $this->filter(
            function ($item) use ($key, $value, $strict) {
                if (data_get($item, $key)) {
                    return preg_match('/' . $value . '/i', implode(",", data_get($item, $key)));
                }
            }
        );
    }

    /**
     * Filter items by the given key value pair.
     *
     * @param  string $key
     * @param  mixed  $value
     * @param  bool   $strict
     * @return static
     */
    public function match($key, $value, $strict = true)
    {
        return $this->filter(
            function ($item) use ($key, $value, $strict) {
                return preg_match('/' . $value . '/i', data_get($item, $key));
            }
        );
    }
}