<?php namespace App\Core\V203;

use App\Core\V201\IatiActivity as V201;
use App\Core\IatiFilePathTrait;

class IatiActivity extends V201
{
    use IatiFilePathTrait;

    function __construct()
    {
        $this->setType('Activity');
    }

    public function getParticipatingOrganization()
    {
        return app('App\Core\V203\Element\Activity\ParticipatingOrganization');
    }

    public function getBudget()
    {
        return app('App\Core\V202\Element\Activity\Budget');
    }

    public function getBudgetRequest()
    {
        return app('App\Core\V202\Requests\Activity\Budget');
    }

    public function getPlannedDisbursement()
    {
        return app('App\Core\V202\Element\Activity\PlannedDisbursement');
    }

    public function getPlannedDisbursementRequest()
    {
        return app('App\Core\V202\Requests\Activity\PlannedDisbursement');
    }

    public function getRecipientRegion()
    {
        return app('App\Core\V202\Element\Activity\RecipientRegion');
    }

    public function getUploadActivity()
    {
        return app('App\Core\V203\Element\Activity\UploadActivity');
    }
    
    public function getRecipientRegionRequest()
    {
        return app('App\Core\V202\Requests\Activity\RecipientRegion');
    }

    public function getSector()
    {
        return app('App\Core\V202\Element\Activity\Sector');
    }

    public function getTag()
    {
        return app('App\Core\V203\Element\Activity\Tag');
    }

    public function getTagRequest()
    {
        return app('App\Core\V203\Requests\Activity\Tag');
    }

    public function getSectorRequest()
    {
        return app('App\Core\V202\Requests\Activity\Sector');
    }

    public function getPolicyMarker()
    {
        return app('App\Core\V202\Element\Activity\PolicyMarker');
    }

    public function getPolicyMarkerRequest()
    {
        return app('App\Core\V202\Requests\Activity\PolicyMarker');
    }

    public function getTransaction()
    {
        return app('App\Core\V203\Element\Activity\Transaction');
    }

    public function getTransactionRequest()
    {
        return app('App\Core\V202\Requests\Activity\Transaction');
    }

    public function getUploadTransaction()
    {
        return app('App\Core\V202\Element\Activity\UploadTransaction');
    }

    public function getDocumentLink()
    {
        return app('App\Core\V203\Element\Activity\DocumentLink');
    }

    public function getDocumentLinkRequest()
    {
        return app('App\Core\V203\Requests\Activity\DocumentLink');
    }

    public function getResult()
    {
        return app('App\Core\V203\Element\Activity\Result');
    }

    public function getResultRequest()
    {
        return app('App\Core\V202\Requests\Activity\Result');
    }

    public function getChangeActivityDefault()
    {
        return app('App\Core\V203\Element\Activity\ChangeActivityDefault');
    }

    public function getActivityXmlService()
    {
        return app('App\Core\V203\Xml\Activity\XmlService');
    }

    public function getDownloadCsv()
    {
        return app('App\Core\V202\Element\DownloadCsv');
    }

    public function getDefaultAidType()
    {
        return app('App\Core\V203\Element\Activity\DefaultAidType');
    }

    public function getDefaultAidTypeRequest()
    {
        return app('App\Core\V203\Requests\Activity\DefaultAidType');
    }
}
