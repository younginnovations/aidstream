<?php

/**
 * ger headers from json file
 * @param $folder
 * @param $fileName
 * @return mixed
 */
function getHeaders($folder, $fileName)
{
    return json_decode(file_get_contents(app_path("Migration/Templates/$folder/$fileName.json")), true);
}
