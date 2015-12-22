<?php namespace App\Core\V202;

use App\Core\V201\IatiOrganization as V201;
use App;

class IatiOrganization extends V201
{
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
        return app('App\Core\V202\Element\Organization\DocumentLink');
    }

    public function getDocumentLinkRequest()
    {
        return app('App\Core\V202\Requests\Organization\CreateDocumentLinkRequest');
    }
}
