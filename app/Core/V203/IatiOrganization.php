<?php namespace App\Core\V203;

use App\Core\V201\IatiOrganization as V201;
use App\Core\IatiFilePathTrait;

class IatiOrganization extends V201
{
    use IatiFilePathTrait;

    function __construct()
    {
        $this->setType('Organization');
    }

    public function getTotalBudget()
    {
        return app('App\Core\V202\Element\Organization\TotalBudget');
    }

    public function getRecipientOrgBudget()
    {
        return app('App\Core\V202\Element\Organization\RecipientOrgBudget');
    }

    public function getDocumentLink()
    {
        return app('App\Core\V203\Element\Organization\DocumentLink');
    }

    public function getDocumentLinkRequest()
    {
        return app('App\Core\V203\Requests\Organization\CreateDocumentLinkRequest');
    }

    public function getOrgXmlService()
    {
        return app('App\Core\V202\Xml\Organization\XmlService');
    }

    public function getRepository()
    {
        return app('App\Core\V203\Repositories\Organization\OrganizationRepository');
    }
}
