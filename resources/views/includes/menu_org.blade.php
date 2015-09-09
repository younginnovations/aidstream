<div class="panel panel-default">
    <div class="panel-heading">Identification</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="{{ url('/organization/' . Session::get('org_id')) }}">Organization Data</a></li>
            <li><a href="{{ url('/organization/' . Session::get('org_id') . '/reportingOrg') }}">Reporting Organization</a></li>
            <li><a href="{{ url('/organization/' . Session::get('org_id') . '/identifier') }}">Organization Identifier</a></li>
            <li><a href="{{ url('/organization/' . Session::get('org_id') . '/name') }}">Name</a></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Budgets</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="{{ url('/organization/' . Session::get('org_id') . '/total-budget') }}">Total Budget</a></li>
            <li><a href="{{ url('/organization/' . Session::get('org_id') . '/recipient-organization-budget') }}">Recipient Organization Budget</a></li>
            <li><a href="{{ url('/organization/' . Session::get('org_id') . '/recipient-country-budget') }}">Recipient Country Budget</a></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Documents</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="{{ url('/organization/' . Session::get('org_id') . '/document-link') }}">Document Link</a></li>
        </ul>
    </div>
</div>