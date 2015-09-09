<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');
Route::get('home', 'HomeController@index');
Route::resource('settings', 'Complete\SettingsController');
Route::get('organization/{id}/identifier','Complete\Organization\OrganizationController@showIdentifier');
Route::resource('organization','Complete\Organization\OrganizationController');
Route::resource('organization.reportingOrg','Complete\Organization\OrgReportingOrgController');
Route::resource('organization.name','Complete\Organization\NameController');
Route::resource('organization.total-budget','Complete\Organization\OrgTotalBudgetController');
Route::resource('organization.recipient-organization-budget','Complete\Organization\RecipientOrganizationBudgetController');
//Route::resource('organization.recipient-country-budget','Complete\Organization\RecipientOrganizationBudgetController');
Route::resource('organization.document-link','Complete\Organization\DocumentLinkController');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
