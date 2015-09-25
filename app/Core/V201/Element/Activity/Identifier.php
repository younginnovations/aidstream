<?php

namespace app\Core\V201\Element\Activity;

class Identifier
{
    /**
     * @return string
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\Identifier";
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\IatiIdentifierRepository');
    }
}
