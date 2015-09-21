<?php namespace App\Core;


class ProtoName {

    public function __construct($_this) {

        foreach($_this->getFields() as $collection_1 => $data_1) {
            if($data_1->getType() != 'collection') continue;
            $count = substr_count($data_1->getName(), '__NAME');
            $_this->modify($collection_1, 'collection', [
                'prototype_name' => '__NAME' . $count . '__',
            ]);
        }

    }

}