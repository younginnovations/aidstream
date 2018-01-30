<?php
namespace App\Core\V201\Repositories\Organization;

use App\Models\Organization\OrganizationData;
use Illuminate\Database\Eloquent\Model;

class DocumentLinkRepository
{
    /**
     * @var OrganizationData
     */
    protected $org;

    /**
     * @param OrganizationData $org
     */
    function __construct(OrganizationData $org)
    {
        $this->org = $org;
    }

    /**
     * @param $input
     * @param $organization
     * @return bool
     */
    public function update(array $input, OrganizationData $organization)
    {
        $organization->document_link = $input['document_link'];

        return $organization->save();
    }

    /**
     * write brief description
     * @param $organization_id
     * @return model
     */
    public function getOrganizationData($organization_id)
    {
        return $this->org->where('id', $organization_id)->first();
    }

    /**
     * write brief description
     * @param $organization_id
     * @return model
     */
    public function getDocumentLinkData($organization_id)
    {
        return $this->org->where('id', $organization_id)->first()->document_link;
    }
}
