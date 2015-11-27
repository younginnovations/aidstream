<div class="panel panel-default">
    <div class="panel-heading">Identification</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="{{ route('activity.reporting-organization.index', [$id]) }}">Reporting Organization</a></li>
            <li><a href="{{ route('activity.iati-identifier.index', $id) }}">Iati Identifier</a></li>
            <li><a href="{{ route('activity.other-identifier.index', $id) }}">Other Identifier</a></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Basic Activity Information</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="{{ route('activity.title.index', $id) }}">Title</a></li>
            <li><a href="{{ route('activity.description.index', $id) }}">Description</a></li>
            <li><a href="{{ route('activity.activity-status.index', $id) }}">Activity Status</a></li>
            <li><a href="{{ route('activity.activity-date.index', $id) }}">Activity Date</a></li>
            <li><a href="{{ route('activity.contact-info.index', $id) }}">Contact Info</a></li>
            <li><a href="{{ route('activity.activity-scope.index', $id) }}">Activity Scope</a></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Participating Organisations</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="{{ route('activity.participating-organization.index', $id) }}">Participating Organisation</a>
            </li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Geopolitical Information</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="{{ route('activity.recipient-country.index', $id) }}">Recipient Country</a></li>
            <li><a href="{{ route('activity.recipient-region.index', $id) }}">Recipient Region</a></li>
            <li><a href="{{ route('activity.location.index', $id) }}">Location</a></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Classifications</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="{{ route('activity.sector.index', $id) }}">Sector</a></li>
            <li><a href="{{ route('activity.country-budget-items.index', $id) }}">Country Budget Item</a></li>
            <li><a href="{{ route('activity.policy-maker.index', $id) }}">Policy Maker</a></li>
            <li><a href="{{ route('activity.collaboration-type.index', $id) }}">Collaboration Type</a></li>
            <li><a href="{{ route('activity.default-flow-type.index', $id) }}">Default Flow Type</a></li>
            <li><a href="{{ route('activity.default-finance-type.index', $id) }}">Default Finance Type</a></li>
            <li><a href="{{ route('activity.default-aid-type.index', $id) }}">Default Aid Type</a></li>
            <li><a href="{{ route('activity.default-tied-status.index', $id) }}">Default Tied Status</a></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Financial</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="{{ route('activity.budget.index', $id) }}">Budget</a></li>
            <li><a href="{{ route('activity.planned-disbursement.index', $id) }}">Planned Disbursement</a></li>
            <li><a href="{{ route('activity.capital-spend.index', $id) }}">Capital Spend</a></li>
            <li><a href="{{ route('activity.transaction.index', $id) }}">Transaction</a></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Related Documents</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="{{ route('activity.document-link.index', $id) }}">Document Link</a></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Relations</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="{{ route('activity.related-activity.index', $id) }}">Related Activity</a></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Performance</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="{{ route('activity.legacy-data.index', $id) }}">Legacy Data</a></li>
            <li><a href="{{ route('activity.condition.index', $id) }}">Conditions</a></li>
            <li><a href="{{ route('activity.result.index', $id) }}">Result</a></li>
        </ul>
    </div>
</div>
