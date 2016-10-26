<?php namespace App\Core\V202;

use App\Core\V201\IatiSettings as V201;
use App\Core\IatiFilePathTrait;

class IatiSettings extends V201
{
    use IatiFilePathTrait;

    function __construct()
    {
        $this->setType('Settings');
    }

    public function getDefaultValues()
    {
        return 'App\Core\V202\Forms\Settings\DefaultValues';
    }
}
