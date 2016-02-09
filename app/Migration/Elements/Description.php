<?php namespace App\Migration\Elements;


class Description
{
    public function format(array $descriptionData)
    {
        return ['type' => $descriptionData['code'], 'narrative' => $descriptionData['narrative']];
    }
}
