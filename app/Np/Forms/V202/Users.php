<?php namespace App\Np\Forms\V202;


use App\Core\V201\Traits\GetCodes;
use App\Np\Forms\NpBaseForm;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

/**
 * Class Users
 * @package App\Np\Forms\V202
 */
class Users extends NpBaseForm
{
    use GetCodes;

    /**
     * Form Structure for the user.
     */
    public function buildForm()
    {
        $dbRoles = DB::table('role')->whereNotNull('permissions')->orderBy('role', 'desc')->get();
        $roles   = [];

        foreach ($dbRoles as $role) {
            $roles[$role->id] = $role->role;
        }

        $this->addText('first_name', trans('lite/users.first_name'))
             ->addText('last_name', trans('lite/users.last_name'))
             ->addText('email', trans('lite/users.email_address'))
             ->add(
                 'username',
                 'text',
                 [
                     'label'      => trans('lite/users.username'),
                     'required'   => true,
                     'wrapper'    => ['class' => 'form-group col-sm-6'],
                     'help_block' => $this->addHelpText('registration_admin_username'),
                     'attr'       => ['class' => 'username']
                 ]
             )
             ->addPassword('password', trans('lite/users.password'))
             ->addPassword('password_confirmation', trans('lite/users.confirm_password'))
             ->addSelect(
                 'role_id',
                 $roles,
                 trans('lite/users.permission'),
                 null,
                 null,
                 true,
                 ['wrapper' => ['class' => 'form-group col-sm-6']]
             );
    }
}
