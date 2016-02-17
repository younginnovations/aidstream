@if(!empty($reporting_org))
    <div class="panel panel-default">
        <div class="panel-heading">Reporting Organization
            <a href="{{ url('/organization/' . $orgId . '/reportingOrg') }}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-element-body row">
            <div class="col-xs-12 col-md-12">
                <div class="col-xs-12 col-xs-4">Ref:</div>
                <div class="col-xs-12 col-xs-8">{{ $reporting_org['reporting_organization_identifier'] }}</div>
            </div>
            <div class="col-xs-12 col-md-12">
                <div class="col-xs-12 col-xs-4">Type:</div>
                <div class="col-xs-12 col-xs-8">{{ $reporting_org['reporting_organization_type'] }}</div>
            </div>
            @foreach($reporting_org['narrative'] as $narrative)
                <div class="col-xs-12 col-md-12">
                    <div class="col-xs-12 col-xs-4">Narrative Text:</div>
                    <div class="col-xs-12 col-xs-8">{{ $narrative['narrative'] . ' [' . $narrative['language'] . ']' }}</div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Organization Identifier
            <a href="{{ url('/organization/' . $orgId . '/identifier') }}" class="edit-element">edit</a>
        </div>
        <div class="panel-body panel-element-body row">
            <div class="col-xs-12 col-md-12">
                <div class="col-xs-12 col-xs-4">Text:</div>
                <div class="col-xs-12 col-xs-8">{{ $reporting_org['reporting_organization_identifier'] }}</div>
            </div>
        </div>
    </div>
@endif
