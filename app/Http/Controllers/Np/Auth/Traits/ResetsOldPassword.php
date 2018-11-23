<?php namespace App\Http\Controllers\Auth\Traits;

use App\Exceptions\Aidstream\InvalidUserException;
use Illuminate\Support\Facades\Hash;

/**
 * Class ResetsOldPassword
 * @package App\Http\Controllers\Auth\Traits
 */
trait ResetsOldPassword
{
    /**
     * Check if the old User requires his/her password reset.
     * @param $credentials
     * @return bool
     */
    protected function requiresPasswordReset($credentials)
    {
        if ($this->exists($credentials)->withOld($credentials['password'])) {
            return true;
        }

        return false;
    }

    /**
     * Check if the attempting User's username exists on the Aidstream database.
     * @param $credentials
     * @return $this
     */
    protected function exists($credentials)
    {
        $this->attemptingUser = array_key_exists('username', $credentials)
            ? $this->user->where('username', '=', $credentials['username'])->first()
            : $this->user->where('email', '=', $credentials['email'])->first();

        if (!$this->attemptingUser) {
            throw new InvalidUserException('The username/email you entered does not exist.');
        }

        return $this;
    }

    /**
     * Check if the attempting User has the old (md5 encrypted) password.
     * @param $password
     * @return bool
     */
    protected function withOld($password)
    {
        return ((strlen($this->attemptingUser->password) === self::MD5_PASSWORD_LENGTH) && ($this->attemptingUser->password === md5($password)));
    }

    /**
     * Reset the password for the attempting User.
     * @param $password
     */
    protected function resetPassword($password)
    {
        $this->attemptingUser->password = Hash::make($password);

        $this->attemptingUser->save();
    }
}
