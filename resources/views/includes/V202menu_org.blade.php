@inject('organizationData', 'App\Helpers\OrganizationData')

{{--*/
$orgData = $organizationData->get();
$orgId = session('org_id');
/*--}}
<div class="element-menu-wrapper">
    <div class="element-sidebar-dropdown">
        <div class="edit-element">edit<span class="caret"></span></div>
    </div>
<div class="col-xs-4 col-md-4 col-lg-4 element-sidebar-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">Identification</div>
        <div class="panel-body">
            <ul class="nav">
                <li>
                    {{--*/ $filled = $orgData['reporting_org']; /*--}}
                    <a href="{{ url('/organization/' . $orgId . '/reportingOrg') }}" class="{{ $filled ? 'active' : '' }}" title="{{ $filled ? 'Edit' : 'Add' }} Reporting Organization">
                        <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                        Reporting Organization
                    </a>
                    <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.Organisation_ReportingOrg')">help text</span>
                </li>
                <li>
                    {{--*/ $filled = $orgData['reporting_org']; /*--}}
                    <a href="{{ url('/organization/' . $orgId . '/identifier')  }}" class="{{ $filled ? 'active' : '' }}" title="{{ $filled ? 'Edit' : 'Add' }} Organization Identifier">
                        <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                        Organization Identifier
                    </a>
                    <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.Organisation_Identifier')">help text</span>
                </li>
                <li>
                    {{--*/ $filled = $orgData['name']; /*--}}
                    <a href="{{ url('/organization/' . $orgId . '/name') }}" class="{{ $filled ? 'active' : '' }}" title="{{ $filled ? 'Edit' : 'Add' }} Name">
                        <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                        Name
                    </a>
                    <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.Organisation_Name')">help text</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Budgets</div>
        <div class="panel-body">
            <ul class="nav">
                <li>
                    {{--*/ $filled = $orgData['total_budget']; /*--}}
                    <a href="{{ route('organization.total-budget.index', $orgId) }}" class="{{ $filled ? 'active' : '' }}" title="{{ $filled ? 'Edit' : 'Add' }} Total Budget">
                        <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                        Total Budget
                    </a>
                    <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.Organisation_TotalBudget')">help text</span>
                </li>
                <li>
                    {{--*/ $filled = $orgData['recipient_organization_budget']; /*--}}
                    <a href="{{ route('organization.recipient-organization-budget.index', $orgId)}}" class="{{ $filled ? 'active' : '' }}"
                       title="{{ $filled ? 'Edit' : 'Add' }} Recipient Organization Budget">
                        <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                        Recipient Organization Budget
                    </a>
                    <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.Organisation_RecipientOrgBudget')">help text</span>
                </li>
                <li>
                    {{--*/ $filled = $orgData['recipient_region_budget']; /*--}}
                    <a href="{{ url('/organization/' . $orgId . '/recipient-region-budget') }}" class="{{ $filled ? 'active' : '' }}" title="{{ $filled ? 'Edit' : 'Add' }} Recipient Region Budget">
                        <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                        Recipient Region Budget
                    </a>
                    <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.Organisation_RecipientCountryBudget')">help text</span>
                </li>
                <li>
                    {{--*/ $filled = $orgData['recipient_country_budget']; /*--}}
                    <a href="{{ url('/organization/' . $orgId . '/recipient-country-budget') }}" class="{{ $filled ? 'active' : '' }}" title="{{ $filled ? 'Edit' : 'Add' }} Recipient Country Budget">
                        <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                        Recipient Country Budget
                    </a>
                    <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.Organisation_RecipientCountryBudget')">help text</span>
                </li>
                <li>
                    {{--*/ $filled = $orgData['total_expenditure']; /*--}}
                    <a href="{{ url('/organization/' . $orgId . '/total-expenditure') }}" class="{{ $filled ? 'active' : '' }}" title="{{ $filled ? 'Edit' : 'Add' }} Total Expenditure">
                        <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                        Total Expenditure
                    </a>
                    <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.org_total_expenditure')">help text</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Documents</div>
        <div class="panel-body">
            <ul class="nav">
                <li>
                    {{--*/ $filled = $orgData['document_link']; /*--}}
                    <a href="{{ url('/organization/' . $orgId . '/document-link') }}" class="{{ $filled ? 'active' : '' }}" title="{{ $filled ? 'Edit' : 'Add' }} Document Link">
                        <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                        Document Link
                    </a>
                    <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.Organisation_DocumentLink')">help text</span>
                </li>
            </ul>
        </div>
    </div>
</div>
