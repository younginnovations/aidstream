<?php
namespace app\Core\Repositories;

interface OrganizationRepositoryInterface
{
    public function createOrganization(array $input);

    public function getOrganizations();

    public function getOrganization($id);

    public function updateOrganization($input, $organization);

    public function updateStatus($input, $organization);
}
