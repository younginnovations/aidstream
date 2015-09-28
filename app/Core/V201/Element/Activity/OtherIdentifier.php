<?php

namespace app\Core\V201\Element\Activity;

class OtherIdentifier
{
    /**
     * @return string
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Activity\MultipleOtherIdentifierForm";
    }

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\OtherIdentifierRepository');
    }
}
