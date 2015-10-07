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
            <li><a href="#">Participating Organisation</a></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Geopolitical Information</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="{{ route('activity.recipient-country.index', $id) }}">Recipient Country</a></li>
            <li><a href="#">Recipient Region</a></li>
            <li><a href="#">Location</a></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Classifications</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="#">Sector</a></li>
            <li><a href="#">Country Budget Item</a></li>
            <li><a href="#">Policy Maker</a></li>
            <li><a href="#">Collaboration Type</a></li>
            <li><a href="#">Default Flow Type</a></li>
            <li><a href="#">Default Finance Type</a></li>
            <li><a href="#">Default Aid Type</a></li>
            <li><a href="#">Default Tied Status</a></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Financial</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="#">Budget</a></li>
            <li><a href="#">Planned Disbursement</a></li>
            <li><a href="#">Capital Send</a></li>
            <li><a href="#">Transaction</a></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Related Documents</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="#">Document Link</a></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Relations</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="#">Related Activity</a></li>
        </ul>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Performance</div>
    <div class="panel-body">
        <ul class="nav">
            <li><a href="#">Legacy Data</a></li>
            <li><a href="#">Conditions</a></li>
            <li><a href="#">Result</a></li>
        </ul>
    </div>
</div>
