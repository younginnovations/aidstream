<div class="panel panel-default">
    <div class="panel-heading">Identification</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="{{ url('/organization/' . Session::get('org_id')) }}">Organization Data</a><span class="help-text">help text</span></li>
            <li><a href="{{ url('/organization/' . Session::get('org_id') . '/reportingOrg') }}">Reporting Organization</a><span class="help-text">help text</span></li>
            <li><a href="{{ url('/organization/' . Session::get('org_id') . '/identifier')  }}">Organization Identifier</a><span class="help-text">help text</span></li>
            <li><a href="{{ url('/organization/' . Session::get('org_id') . '/name') }}">Name</a><span class="help-text">help text</span></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Budgets</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="{{ route('organization.total-budget.index', Auth::user()->org_id) }}">Total Budget</a><span class="help-text">help text</span></li>
            <li><a href="{{ route('organization.recipient-organization-budget.index', Auth::user()->org_id)}}">Recipient Organization Budget</a><span class="help-text">help text</span></li>
            <li><a href="{{ url('/organization/' . Session::get('org_id') . '/recipient-country-budget') }}">Recipient Country Budget</a><span class="help-text">help text</span></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Documents</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="{{ url('/organization/' . Session::get('org_id') . '/document-link') }}">Document Link</a><span class="help-text">help text</span></li>
        </ul>
    </div>
</div>