<?php

namespace App\Helpers;

class JsonHelper
{

    public static function JsonEncode($data)
    {
        return json_encode($data);
    }

    public static function JsonDecode($data)
    {
        return json_decode($data);
    }

    public static function Encode($object, $request, $data)
    {

        foreach ($data as $value) {
            $object->$value = JsonHelper::JsonEncode($request->get($value));
        }

        return $object;
    }

    public static function Decode($object, $data)
    {
        foreach ($data as $value) {
            $object->$value = JsonHelper::JsonDecode($object->$value);
        }

        return $object;
    }
}
