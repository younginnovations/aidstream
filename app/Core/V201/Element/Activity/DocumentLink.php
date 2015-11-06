<?php namespace App\Core\V201\Element\Activity;

/**
 * Class DocumentLink
 * @package app\Core\V201\Element\Activity
 */
class DocumentLink
{
    /**
     * @return  Document Link form
     */
    public function getForm()
    {
        return 'App\Core\V201\Forms\Activity\DocumentLinks';
    }

    /**
     * @return Document Link repository
     */
    public function getRepository()
    {
        return App('App\Core\V201\Repositories\Activity\DocumentLink');
    }
}
