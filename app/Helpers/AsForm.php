<?php

/**
 * Class AsForm
 */
class AsForm
{
    /**
     * returns mapped data for field block
     * @param $args
     * @return mixed
     */
    protected static function prepareData($args)
    {
        $name                   = getVal($args, ['name']);
        $args['name']           = $name;
        $validationName         = str_replace(']', '', str_replace('[', '.', $name));
        $args['validationName'] = $validationName;
        $fieldName              = substr($validationName, ($dotPosition = strrpos($validationName, '.')) ? $dotPosition + 1 : 0);
        getVal($args, ['label']) !== "" ?: $args['label'] = str_replace('_', ' ', title_case($fieldName));
        $args['parent']   = getVal($args, ['parent']);
        $args['required'] = getVal($args, ['required'], false);
        $args['value']    = getVal($args, ['value'], null);
        $args['class']    = getVal($args, ['class']);
        $args['data']     = getVal($args, ['data'], []);
        $data             = [];
        !getVal($args, ['empty_value']) ?: $data[''] = $args['empty_value'];
        $data += $args['data'];
        $args['data'] = $data;

        $args['html'] = getVal($args, ['html']);
        $args['attr'] = getVal($args, ['attr'], []);
        $args['attr'] += ['class' => sprintf('form-control %s', $args['class'])];

        return $args;
    }

    /**
     * returns field block of requested field type
     * @param $args
     * @param $field
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected static function field($args, $field)
    {
        $data          = self::prepareData($args);
        $data['field'] = $field;

        return view('forms.field', $data);
    }

    /**
     * returns username label and field
     * @param $args
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function username($args)
    {
        $data                   = self::prepareData($args);
        $validationName         = str_replace(']', '', str_replace('[', '.', $data['hiddenName']));
        $data['validationName'] = $validationName;
        $data['field']          = 'username';

        return view('forms.username', $data);
    }

    /**
     * return form field view according to statically called method name
     * @param $name
     * @param $args
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function __callStatic($name, $args)
    {
        return self::field($args[0], $name);
    }
}
