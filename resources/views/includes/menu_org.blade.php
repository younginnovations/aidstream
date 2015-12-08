<div class="col-xs-4 col-md-4 col-lg-4 element-sidebar-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">Identification</div>
        <div class="panel-body">
            <ul class="nav">
                <li><a href="{{ url('/organization/' . Session::get('org_id') . '/reportingOrg') }}">Reporting Organization</a><span class="help-text" data-toggle="tooltip"
                                                                                                                                     data-placement="top" data-trigger="click"
                                                                                                                                     title="@lang(session()->get('version') . '/help.Organisation_ReportingOrg')">help text</span>
                </li>
                <li><a href="{{ url('/organization/' . Session::get('org_id') . '/identifier')  }}">Organization Identifier</a><span class="help-text" data-toggle="tooltip"
                                                                                                                                     data-placement="top" data-trigger="click"
                                                                                                                                     title="@lang(session()->get('version') . '/help.Organisation_Identifier')">help text</span>
                </li>
                <li><a href="{{ url('/organization/' . Session::get('org_id') . '/name') }}">Name</a><span class="help-text" data-toggle="tooltip" data-placement="top"
                                                                                                           data-trigger="click" title="@lang(session()->get('version') . '/help.Organisation_Name')">help text</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Budgets</div>
        <div class="panel-body">
            <ul class="nav">
                <li><a href="{{ route('organization.total-budget.index', Auth::user()->org_id) }}">Total Budget</a><span class="help-text" data-toggle="tooltip" data-placement="top"
                                                                                                                         data-trigger="click"
                                                                                                                         title="@lang(session()->get('version') . '/help.Organisation_TotalBudget')">help text</span>
                </li>
                <li><a href="{{ route('organization.recipient-organization-budget.index', Auth::user()->org_id)}}">Recipient Organization Budget</a><span class="help-text"
                                                                                                                                                          data-toggle="tooltip" data-placement="top"
                                                                                                                                                          data-trigger="click"
                                                                                                                                                          title="@lang(session()->get('version') . '/help.Organisation_RecipientOrgBudget')">help text</span>
                </li>
                <li><a href="{{ url('/organization/' . Session::get('org_id') . '/recipient-country-budget') }}">Recipient Country Budget</a><span class="help-text"
                                                                                                                                                   data-toggle="tooltip" data-placement="top"
                                                                                                                                                   data-trigger="click"
                                                                                                                                                   title="@lang(session()->get('version') . '/help.Organisation_RecipientCountryBudget')">help text</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Documents</div>
        <div class="panel-body">
            <ul class="nav">
                <li><a href="{{ url('/organization/' . Session::get('org_id') . '/document-link') }}">Document Link</a><span class="help-text"
                                                                                                                             data-toggle="tooltip" data-placement="top"
                                                                                                                             data-trigger="click"
                                                                                                                             title="@lang(session()->get('version') . '/help.Organisation_DocumentLink')">help text</span>
                </li>
            </ul>
        </div>
    </div>
</div>