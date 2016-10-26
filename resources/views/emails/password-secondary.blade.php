{{--*/
$organization = session()->pull('organization');
$secondary    = $organization->secondary_contact;
$admin        = $organization->users->where('role_id', 1)->first();

$secondaryName = sprintf('%s %s', $secondary['first_name'], $secondary['last_name']);
$orgName       = $organization->name;
$adminName     = $admin->name;
$adminEmail    = $admin->email;
/*--}}
<p>Ooops!</p>

<p>Dear {{ $secondaryName }},</p>

<p>The AidStream administrator associated with your organisation, {{ $orgName }}, has indicated that they have forgotten or lost their login credentials.</p>

<p>The login credentials associated with your organisation’s AidStream Administrator account are:</p>

<p>Name: {{ $adminName }}<br/>
    Email address : {{ $adminEmail }}</p>

<p>Please pass this information on to the Administrator and ask them to use it to reset their account password, if necessary. If the registered administrator is no longer with your organisation,
    please contact us at <a href="support@aidstream.org">support@aidstream.org</a> and we can help you to change the details associated with the administrator account.</p>

<p>Thanks,<br/>
    The AidStream Team</p>