<?php namespace App\Core\V201\Repositories;

use App\Models\Organization\Organization;
use App\Services\Verification;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * Class UserRepository
 * @package App\Core\V201\Repositories
 */
class UserRepository
{
    protected $current_user;

    protected $user;

    protected $verification;

    public function __construct(User $user, Verification $verification)
    {
        $this->current_user = Auth::user();
        $this->user         = $user;
        $this->verification = $verification;
    }

    /** Update profile of the user
     * @param $input
     */
    public function updateUserProfile($input)
    {
        $user = $this->current_user;

        if (!Auth::user()->isAdmin()) {
            $user->username = $input['username'];
        }

        $user->first_name   = $input['first_name'];
        $user->last_name    = $input['last_name'];
        $user->email        = $input['email'];
        $timeZone           = explode(' : ', $input['time_zone']);
        $user->time_zone_id = $timeZone[0];
        $user->time_zone    = $timeZone[1];

        $file  = Input::file('profile_picture');
        $orgId = session('org_id');

        if ($file) {
            $fileName = sprintf("%s_%s.%s", $user->username, $user->id, $file->getClientOriginalExtension());
            $fileUrl  = url(sprintf("%s%s", 'files/users/', $fileName));
            $image    = Image::make(File::get($file))->resize(150, 150)->encode();
            Storage::put('users/' . $fileName, $image);
            $user->profile_url     = $fileUrl;
            $user->profile_picture = $fileName;
        }

        $user->save();

        if (array_key_exists('secondary_email', $input)) {
            $this->updateSecondaryContactInfo($input, $orgId);
        }
    }

    /** update secondary contact of the organization.
     * @param $input
     * @param $orgId
     */
    public function updateSecondaryContactInfo($input, $orgId)
    {
        $organization = Organization::where('id', $orgId)->first();

        $secondary = $organization->secondary_contact;

        if (getVal((array) $organization->secondary_contact, ['email']) != $input['secondary_email']) {
            $secondary['first_name']         = $input['secondary_first_name'];
            $secondary['last_name']          = $input['secondary_last_name'];
            $secondary['email']              = $input['secondary_email'];
            $organization->secondary_contact = $secondary;
            $organization->save();

            $this->verification->sendSecondaryVerificationEmail($organization);
        } else {
            $secondaryContact = [
                'first_name'              => $input['secondary_first_name'],
                'last_name'               => $input['secondary_last_name'],
                'email'                   => $input['secondary_email'],
                'verification_code'       => getVal((array) $secondary, ['verification_code']),
                'verification_created_at' => getVal((array) $secondary, ['verification_created_at']),
                'verified'                => getVal((array) $secondary, ['verified'])
            ];

            $organization->secondary_contact = $secondaryContact;
            $organization->save();
        }
    }

    /** returns details of the given user.
     * @param $userId
     * @return mixed
     */
    public function getUser($userId)
    {
        return $this->user->findOrFail($userId);
    }

    /** returns all the users of the organization.
     * @return mixed
     */
    public function getAllUsersOfOrganization()
    {
        return $this->user->where('org_id', session('org_id'))->get();
    }

    /** updates username of the organization.
     * @param $old_user_identifier
     * @param $new_user_identifier
     */
    public function updateUsername($old_user_identifier, $new_user_identifier)
    {
        $users = $this->getAllUsersOfOrganization();
        foreach ($users as $user) {
            $old_username   = $user->username;
            $nameOnly       = substr($old_username, strlen($old_user_identifier) + 1);
            $user->username = $new_user_identifier . '_' . $nameOnly;
            $user->save();
        }
    }
}