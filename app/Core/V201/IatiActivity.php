<?php namespace App\Core\V201;

use App;
use App\Core\V201\Wizard\WizardIatiActivity;

/**
 * Class IatiActivity
 * @package App\Core\V201
 */
class IatiActivity extends WizardIatiActivity
{

    public function getIdentifier()
    {
        return app('App\Core\V201\Element\Activity\Identifier');
    }

    public function getRepository()
    {
        return app('App\Core\V201\Repositories\Activity\ActivityRepository');
    }

    public function getIatiIdentifierRequest()
    {
        return app('App\Core\V201\Requests\Activity\IatiIdentifierRequest');
    }

    public function getOtherIdentifier()
    {
        return app('App\Core\V201\Element\Activity\OtherIdentifier');
    }

    public function getOtherIdentifierRequest()
    {
        return app('App\Core\V201\Requests\Activity\OtherIdentifierRequest');
    }

    public function getTitle()
    {
        return app('App\Core\V201\Element\Activity\Title');
    }

    public function getTitleRequest()
    {
        return app('App\Core\V201\Requests\Activity\Title');
    }

    public function getDescription()
    {
        return app('App\Core\V201\Element\Activity\Description');
    }

    public function getDescriptionRequest()
    {
        return app('App\Core\V201\Requests\Activity\Description');
    }

    public function getActivityStatus()
    {
        return app('App\Core\V201\Element\Activity\ActivityStatus');
    }

    public function getActivityStatusRequest()
    {
        return app('App\Core\V201\Requests\Activity\ActivityStatus');
    }

    public function getActivityDate()
    {
        return app('App\Core\V201\Element\Activity\ActivityDate');
    }

    public function getActivityDateRequest()
    {
        return app('App\Core\V201\Requests\Activity\ActivityDate');
    }

    public function getContactInfo()
    {
        return app('App\Core\V201\Element\Activity\ContactInfo');
    }

    public function getContactInfoRequest()
    {
        return app('App\Core\V201\Requests\Activity\ContactInfo');
    }

    public function getActivityScope()
    {
        return app('App\Core\V201\Element\Activity\ActivityScope');
    }

    public function getActivityScopeRequest()
    {
        return app('App\Core\V201\Requests\Activity\ActivityScope');
    }

    public function getParticipatingOrganization()
    {
        return app('App\Core\V201\Element\Activity\ParticipatingOrganization');
    }

    public function getParticipatingOrganizationRequest()
    {
        return app('App\Core\V201\Requests\Activity\ParticipatingOrganization');
    }

    public function getRecipientCountry()
    {
        return app('App\Core\V201\Element\Activity\RecipientCountry');
    }

    public function getRecipientCountryRequest()
    {
        return app('App\Core\V201\Requests\Activity\RecipientCountry');
    }

    public function getLocation()
    {
        return app('App\Core\V201\Element\Activity\Location');
    }

    public function getLocationRequest()
    {
        return app('App\Core\V201\Requests\Activity\Location');
    }

    public function getRecipientRegion()
    {
        return app('App\Core\V201\Element\Activity\RecipientRegion');
    }

    public function getRecipientRegionRequest()
    {
        return app('App\Core\V201\Requests\Activity\RecipientRegion');
    }

    public function getSector()
    {
        return app('App\Core\V201\Element\Activity\Sector');
    }

    public function getSectorRequest()
    {
        return app('App\Core\V201\Requests\Activity\Sector');
    }

    public function getCountryBudgetItem()
    {
        return app('App\Core\V201\Element\Activity\CountryBudgetItem');
    }

    public function getCountryBudgetItemRequest()
    {
        return app('App\Core\V201\Requests\Activity\CountryBudgetItem');
    }

    public function getBudget()
    {
        return app('App\Core\V201\Element\Activity\Budget');
    }

    public function getBudgetRequest()
    {
        return app('App\Core\V201\Requests\Activity\Budget');
    }

    public function getPolicyMaker()
    {
        return app('App\Core\V201\Element\Activity\PolicyMaker');
    }

    public function getPolicyMakerRequest()
    {
        return app('App\Core\V201\Requests\Activity\PolicyMaker');
    }

    public function getCollaborationType()
    {
        return app('App\Core\V201\Element\Activity\CollaborationType');
    }

    public function getCollaborationTypeRequest()
    {
        return app('App\Core\V201\Requests\Activity\CollaborationType');
    }

    public function getDefaultFlowType()
    {
        return app('App\Core\V201\Element\Activity\DefaultFlowType');
    }

    public function getDefaultFlowTypeRequest()
    {
        return app('App\Core\V201\Requests\Activity\DefaultFlowType');
    }

    public function getDefaultFinanceType()
    {
        return app('App\Core\V201\Element\Activity\DefaultFinanceType');
    }

    public function getDefaultFinanceTypeRequest()
    {
        return app('App\Core\V201\Requests\Activity\DefaultFinanceType');
    }

    public function getDefaultAidType()
    {
        return app('App\Core\V201\Element\Activity\DefaultAidType');
    }

    public function getDefaultAidTypeRequest()
    {
        return app('App\Core\V201\Requests\Activity\DefaultAidType');
    }

    public function getDefaultTiedStatus()
    {
        return app('App\Core\V201\Element\Activity\DefaultTiedStatus');
    }

    public function getDefaultTiedStatusRequest()
    {
        return app('App\Core\V201\Requests\Activity\DefaultTiedStatus');
    }

    public function getCapitalSpend()
    {
        return app('App\Core\V201\Element\Activity\CapitalSpend');
    }

    public function getCapitalSpendRequest()
    {
        return app('App\Core\V201\Requests\Activity\CapitalSpend');
    }

    public function getPlannedDisbursement()
    {
        return app('App\Core\V201\Element\Activity\PlannedDisbursement');
    }

    public function getPlannedDisbursementRequest()
    {
        return app('App\Core\V201\Requests\Activity\PlannedDisbursement');
    }

    public function getDocumentLink()
    {
        return app('App\Core\V201\Element\Activity\DocumentLink');
    }

    public function getDocumentLinkRequest()
    {
        return app('App\Core\V201\Requests\Activity\DocumentLink');
    }

    public function getRelatedActivity()
    {
        return app('App\Core\V201\Element\Activity\RelatedActivity');
    }

    public function getRelatedActivityRequest()
    {
        return app('App\Core\V201\Requests\Activity\RelatedActivity');
    }

    public function getTransaction()
    {
        return app('App\Core\V201\Element\Activity\Transaction');
    }

    public function getTransactionRequest()
    {
        return app('App\Core\V201\Requests\Activity\Transaction');
    }

    public function getUploadTransaction()
    {
        return app('App\Core\V201\Element\Activity\UploadTransaction');
    }

    public function getUploadTransactionRequest()
    {
        return app('App\Core\V201\Requests\Activity\UploadTransaction');
    }

    public function getLegacyData()
    {
        return app('App\Core\V201\Element\Activity\LegacyData');
    }

    public function getLegacyDataRequest()
    {
        return app('App\Core\V201\Requests\Activity\LegacyData');
    }

    public function getCondition()
    {
        return app('App\Core\V201\Element\Activity\Condition');
    }

    public function getConditionRequest()
    {
        return app('App\Core\V201\Requests\Activity\Condition');
    }

    public function getActivityXmlService()
    {
        return app('App\Core\V201\Element\Activity\XmlService');
    }

    public function getTransactionRepository()
    {
        return app('App\Core\V201\Repositories\Activity\Transaction');
    }

    public function getResultRepository()
    {
        return app('App\Core\V201\Repositories\Activity\Result');
    }

    public function getResult()
    {
        return app('App\Core\V201\Element\Activity\Result');
    }

    public function getResultRequest()
    {
        return app('App\Core\V201\Requests\Activity\Result');
    }

    public function getUploadActivity()
    {
        return app('App\Core\V201\Element\Activity\UploadActivity');
    }

    public function getUploadActivityRequest()
    {
        return app('App\Core\V201\Requests\Activity\UploadActivity');
    }

    public function getCsvImportValidator()
    {
        return app('App\Core\V201\Requests\Activity\CsvImportValidator');
    }

    public function getChangeActivityDefault()
    {
        return app('App\Core\V201\Element\Activity\ChangeActivityDefault');
    }

    public function getChangeActivityDefaultRequest()
    {
        return app('App\Core\V201\Requests\Activity\ChangeActivityDefault');
    }
}
