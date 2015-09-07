<?php
/**
 * Created by PhpStorm.
 * User: kriti
 * Date: 9/7/15
 * Time: 2:20 PM
 */

namespace App\Core\Repositories;

interface OrganizationDataRepositoryInterface
{
    public function createOrganization(array $input);

    public function getOrganizations();

    public function getOrganization($id);

    public function updateOrganization($input, $organization);
}