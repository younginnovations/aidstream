<?php
namespace App\Core\V201\Element\Organization;

use App\Core\Elements\BaseElement;
use App;

class RecipientOrgBudget extends BaseElement
{
    protected $narratives = [];

    public function setNarrative($narrative)
    {
        $this->narratives[] = $narrative;

        return $this;
    }

    public function getForm()
    {
        return "App\Core\V201\Forms\Organization\MultipleRecipientOrgBudgetForm";
    }

    /**
     * @param $organization
     * @return mixed
     */
    public function getXmlData($organization)
    {
        $organizationData   = [];
        $recipientOrgBudget = $organization->buildRecipientOrgBudget();
        foreach ($recipientOrgBudget as $RecipientOrgBudget) {
            $organizationData[] = array(
                'narrative' => $this->buildNarrative($RecipientOrgBudget['narrative'])
            );
        }

        return $organizationData;
    }

    public function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\RecipientOrgBudgetRepository');
    }

}