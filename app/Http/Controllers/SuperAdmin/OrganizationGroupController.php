<?php namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin\UserGroup;
use App\SuperAdmin\Requests\OrganizationGroup;
use App\SuperAdmin\Services\OrganizationGroupManager;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class OrganizationGroupController
 * @package App\Http\Controllers\SuperAdmin
 */
class OrganizationGroupController extends Controller
{
    /**
     * @var OrganizationGroupManager
     */
    protected $orgGroupManager;
    /**
     * @var UserGroup
     */
    protected $userGroup;

    /**
     * @param OrganizationGroupManager $orgGroupManager
     * @param UserGroup                $userGroup
     */
    function __construct(OrganizationGroupManager $orgGroupManager, UserGroup $userGroup)
    {
        $this->middleware('auth.superAdmin');
        $this->orgGroupManager = $orgGroupManager;
        $this->userGroup       = $userGroup;
    }

    public function lists()
    {
        $organizations = $this->orgGroupManager->getOrganizationGroups();

        return view('superAdmin.groupOrganization.listGroupOrganization', compact('organizations'));
    }

    public function create(FormBuilder $formBuilder, $groupId = null)
    {
        $orgModel = [];
        if (null !== $groupId) {
            $orgModel = $this->orgGroupManager->getModelForUpdate($groupId);
        }

        $form = $formBuilder->create(
            'App\SuperAdmin\Forms\OrganizationGroup',
            [
                'method' => (null !== $groupId) ? 'PUT' : 'POST',
                'url'    => (null !== $groupId) ? route('admin.edit-group', [$groupId]) : route('admin.create-organization-group'),
                'model'  => ($groupId) ? $orgModel : null,
            ]
        );

        return view('superAdmin.groupOrganization.create', compact('form', 'groupId'));
    }

    /**
     * save organization group
     * @param OrganizationGroup $groupRequest
     * @param null              $groupId
     * @return mixed
     */
    public function save(OrganizationGroup $groupRequest, $groupId = null)
    {
        $groupData = $groupRequest->all();
        (null !== $groupId) ? $this->orgGroupManager->save($groupData, $groupId) : $this->orgGroupManager->save($groupData);

        return redirect()->to('admin/group-organizations')->withMessage(
            'Organization Group' . (null !== $groupId) ? 'updated' : 'added'
        );
    }

    /**
     * delete group
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $user = $this->userGroup->findOrFail($id);
        $user->delete($user);

        return redirect()->back()->withMessage('Organization group has been deleted.');
    }
}
