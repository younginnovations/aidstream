@inject('organizationData', 'App\Helpers\OrganizationData')

{{--*/
$orgData = $organizationData->get();
$orgId = session('org_id');
/*--}}
<div class="element-menu-wrapper">
    <div class="element-sidebar-dropdown">
        <div class="edit-element">@lang('global.edit')<span class="caret"></span></div>
    </div>
    <div class="col-xs-4 col-md-4 col-lg-4 element-sidebar-wrapper">
        <div class="panel panel-default">
            <div class="panel-heading"> @lang('element.identification')</div>
            <div class="panel-body">
                <ul class="nav">
                    <li>
                        {{--*/ $filled = $orgData['reporting_org']; /*--}}
                        <a href="{{ url('/organization/' . $orgId . '/reportingOrg') }}" class="{{ $filled ? 'active' : '' }}" title="{{ $filled ? 'Edit' : 'Add' }} @lang('element.reporting_organisation')">
                            <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                            @lang('element.reporting_organisation')
                        </a>
                        <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.Organisation_ReportingOrg')">help text</span>
                    </li>
                    <li>
                        {{--*/ $filled = $orgData['reporting_org']; /*--}}
                        <a href="{{ url('/organization/' . $orgId . '/identifier')  }}" class="{{ $filled ? 'active' : '' }}" title="{{ $filled ? 'Edit' : 'Add' }} @lang('element.organisation_identifier')">
                            <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                            @lang('element.organisation_identifier')
                        </a>
                        <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.Organisation_Identifier')">help text</span>
                    </li>
                    <li>
                        {{--*/ $filled = $orgData['name']; /*--}}
                        <a href="{{ url('/organization/' . $orgId . '/name') }}" class="{{ $filled ? 'active' : '' }}" title="{{ $filled ? 'Edit' : 'Add' }} Name">
                            <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                            @lang('element.name')
                        </a>
                        <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.Organisation_Name')">help text</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">@lang('element.budget')</div>
            <div class="panel-body">
                <ul class="nav">
                    <li>
                        {{--*/ $filled = $orgData['total_budget']; /*--}}
                        <a href="{{ route('organization.total-budget.index', $orgId) }}" class="{{ $filled ? 'active' : '' }}" title="{{ $filled ? 'Edit' : 'Add' }} @lang('element.total_budget')">
                            <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                            @lang('element.total_budget')
                        </a>
                        <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.Organisation_TotalBudget')">help text</span>
                    </li>
                    <li>
                        {{--*/ $filled = $orgData['recipient_organization_budget']; /*--}}
                        <a href="{{ route('organization.recipient-organization-budget.index', $orgId)}}" class="{{ $filled ? 'active' : '' }}"
                           title="{{ $filled ? 'Edit' : 'Add' }} @lang('element.recipient_organisation_budget')">
                            <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                            @lang('element.recipient_organisation_budget')
                        </a>
                        <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.Organisation_RecipientOrgBudget')">help text</span>
                    </li>
                    <li>
                        {{--*/ $filled = $orgData['recipient_region_budget']; /*--}}
                        <a href="{{ url('/organization/' . $orgId . '/recipient-region-budget') }}" class="{{ $filled ? 'active' : '' }}" title="{{ $filled ? 'Edit' : 'Add' }} @lang('element.recipient_region_budget')">
                            <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                            @lang('element.recipient_region_budget')
                        </a>
                        <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.Organisation_RecipientCountryBudget')">help text</span>
                    </li>
                    <li>
                        {{--*/ $filled = $orgData['recipient_country_budget']; /*--}}
                        <a href="{{ url('/organization/' . $orgId . '/recipient-country-budget') }}" class="{{ $filled ? 'active' : '' }}" title="{{ $filled ? 'Edit' : 'Add' }} @lang('element.recipient_country_budget')">
                            <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                            @lang('element.recipient_country_budget')
                        </a>
                        <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.Organisation_RecipientCountryBudget')">help text</span>
                    </li>
                    <li>
                        {{--*/ $filled = $orgData['total_expenditure']; /*--}}
                        <a href="{{ url('/organization/' . $orgId . '/total-expenditure') }}" class="{{ $filled ? 'active' : '' }}" title="{{ $filled ? 'Edit' : 'Add' }} @lang('element.total_expenditure')">
                            <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                            @lang('element.total_expenditure')
                        </a>
                        <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.org_total_expenditure')">help text</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">@lang('element.documents')</div>
            <div class="panel-body">
                <ul class="nav">
                    <li>
                        {{--*/ $filled = $orgData['document_link']; /*--}}
                        <a href="{{ url('/organization/' . $orgId . '/document-link') }}" class="{{ $filled ? 'active' : '' }}" title="{{ $filled ? 'Edit' : 'Add' }} Document Link">
                            <span class="action-icon {{ $filled ? 'edit-value' : 'add' }}">icon</span>
                            @lang('element.document_link')
                        </a>
                        <span class="help-text" data-toggle="tooltip" data-placement="top" title="@lang(session()->get('version') . '/help.Organisation_DocumentLink')">help text</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
