<?php namespace App\Services;

use App\Organization;
use App\User;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * createGet a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			'organization_name' => 'required|max:255',
			'organization_address' => 'required|max:255',
			'organization_user_identifier' => 'required|max:255',
			'first_name' => 'required|max:255',
			'last_name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'username' => 'required|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		$organization = Organization::create([
			'name' => $data['organization_name'],
			'address' => $data['organization_address'],
			'user_identifier' => $data['organization_user_identifier'],
		]);
		return User::create([
			'first_name' => $data['first_name'],
			'last_name' => $data['last_name'],
			'email' => $data['email'],
			'username' => $data['username'],
			'password' => bcrypt($data['password']),
			'org_id' => $organization->id,
			'role_id' => 2,
		]);
	}

}
