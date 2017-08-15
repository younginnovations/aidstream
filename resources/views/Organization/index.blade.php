@extends('app')

@section('title', trans('title.organisation').' - ' . $reportingOrg['reporting_organization_identifier'])

@inject('getCode', 'App\Helpers\GetCodeName')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                @include('includes.breadcrumb')
                <div class="element-panel-heading">
                    <div><span class="pull-left">@lang('global.organisations')</span></div>
                </div>
                <div class="col-xs-12 element-content-wrapper full-width-wrapper">
                <div class="element-description">
                    <p>Below you’ll find your organization (reporting organization) and all other organizations that you’ve listed as partner organization in your activities. Any organization you’ll add here will be available as an option while associating an activity to a partner organization.</p>
                </div>
                    <div class="panel-body panel-org-body panel__reporting-org">
                        <h3>@lang('global.reporting_organisation')</h3>
                        <table class="table table-striped">
                            {{--<thead>--}}
                            {{--<tr>--}}
                                {{--<th>@lang('global.reporting_organisation')</th>--}}
                                {{--<th></th>--}}
                                {{--<th></th>--}}
                                {{--<th></th>--}}
                            {{--</tr>--}}
                            {{--</thead>--}}
                            <tbody>
                            <tr>
                                <td width="10px">
                                    <a href="{{ route('organization-data.edit', $reportingOrg->id)}}" class="edit-activity pull-right">@lang('global.edit')</a>
                                </td>
                                <td class="organisation-name">
                                    <a href="{{route('organization.show', $reportingOrg->id)}}">
                                    {{ $reportingOrg->name[0]['narrative'] }}
                                    </a>
                                    <span class="identifier">US-EIN-042347643</span>
                                </td>
                                <td class="sector">
                                    {{--{{ $reportingOrg->type ? $getCode->getCodeNameOnly('OrganizationType', $reportingOrg->type, -4, 'Organization') : '' }}--}}
                                    Private Sector
                                </td>
                                <td>
                                    <div class="activity__status activity-status-{{ $reportingOrg->getStatus() }}">
                                        <span>{{ $reportingOrg->getStatus() }}</span>
                                    </div>
                                </td>
                                <td width="50px">
                                    <div class="view-more">
                                        <a href="#">⋯</a>
                                        <div class="view-more-actions">
                                            <ul>
                                                <li><a href="#" class="merge-with">Merge with ...</a></li>
                                                <li>
                                                    <a href="#" class="edit-this-org">Edit this organisation</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-body panel-org-body panel__partner-org">
                        <h3>@lang('global.partner_organisations')</h3>
                        <a class="pull-right add-new-organisation" href="{{ route('organization.create', session('org_id')) }}">@lang('global.add_a_new_organisation')</a>
                        <table class="table table-striped">
                            {{--<tbody>--}}
                            {{--<tr class="clickable-row" data-href="#">--}}
                                {{--<td width="10px">--}}
                                    {{--<a href="#" class="edit-activity pull-right">@lang('global.edit')</a>--}}
                                {{--</td>--}}
                                {{--<td class="organisation-name">--}}
                                    {{--<a href="#">DFID</a>--}}
                                    {{--<span class="identifier">GB-GOV-1</span>--}}
                                {{--</td>--}}
                                {{--<td class="activities">--}}
                                    {{--<span>4</span> Activities--}}
                                {{--</td>--}}
                                {{--<td class="sector">--}}
                                    {{--Government--}}
                                {{--</td>--}}
                                {{--<td>--}}
                                    {{--<div class="activity__status activity-status-Published">--}}
                                        {{--<span>Published</span>--}}
                                    {{--</div>--}}
                                {{--</td>--}}
                                {{--<td width="50px">--}}
                                    {{--<div class="view-more">--}}
                                        {{--<a href="#">⋯</a>--}}
                                        {{--<div class="view-more-actions">--}}
                                            {{--<ul>--}}
                                                {{--<li><a href="#" class="merge-with">Merge with ...</a></li>--}}
                                                {{--<li>--}}
                                                    {{--<a href="#" class="edit-this-org">Edit this organisation</a>--}}
                                                {{--</li>--}}
                                            {{--</ul>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                            {{--<tr class="clickable-row" data-href="#">--}}
                                {{--<td width="10px">--}}
                                    {{--<a href="#" class="edit-activity pull-right">@lang('global.edit')</a>--}}
                                {{--</td>--}}
                                {{--<td class="organisation-name">--}}
                                    {{--<a href="#">Abt Associates Inc.</a>--}}
                                    {{--<span class="identifier">US-EIN-042347643</span>--}}
                                {{--</td>--}}
                                {{--<td class="activities">--}}
                                    {{--<span>6</span> Activities--}}
                                {{--</td>--}}
                                {{--<td class="sector">--}}
                                    {{--Private sector--}}
                                {{--</td>--}}
                                {{--<td>--}}
                                    {{--<div class="activity__status activity-status-Completed">--}}
                                        {{--<span>Completed</span>--}}
                                    {{--</div>--}}
                                {{--</td>--}}
                                {{--<td width="50px">--}}
                                    {{--<div class="view-more">--}}
                                        {{--<a href="#">⋯</a>--}}
                                        {{--<div class="view-more-actions">--}}
                                            {{--<ul>--}}
                                                {{--<li><a href="#" class="merge-with">Merge with ...</a></li>--}}
                                                {{--<li>--}}
                                                    {{--<a href="#" class="edit-this-org">Edit this organisation</a>--}}
                                                {{--</li>--}}
                                            {{--</ul>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                            {{--<tr class="clickable-row" data-href="#">--}}
                                {{--<td width="10px">--}}
                                    {{--<a href="{{ route('organization-data.edit', $reportingOrg->id)}}" class="edit-activity pull-right">@lang('global.edit')</a>--}}
                                {{--</td>--}}
                                {{--<td class="organisation-name">--}}
                                    {{--<a href="#">Nepal Women Commission</a>--}}
                                    {{--<span class="identifier">NP-CRO-098126</span>--}}
                                {{--</td>--}}
                                {{--<td class="activities">--}}
                                    {{--<span>2</span> Activities--}}
                                {{--</td>--}}
                                {{--<td class="sector">--}}
                                    {{--Private Sector--}}
                                {{--</td>--}}
                                {{--<td>--}}
                                    {{--<div class="activity__status activity-status-Published">--}}
                                        {{--<span>Published</span>--}}
                                    {{--</div>--}}
                                {{--</td>--}}
                                {{--<td width="50px">--}}
                                    {{--<div class="view-more">--}}
                                        {{--<a href="#">⋯</a>--}}
                                        {{--<div class="view-more-actions">--}}
                                            {{--<ul>--}}
                                                {{--<li><a href="#" class="merge-with">Merge with ...</a></li>--}}
                                                {{--<li>--}}
                                                    {{--<a href="#" class="edit-this-org">Edit this organisation</a>--}}
                                                {{--</li>--}}
                                            {{--</ul>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                            {{--<tr class="clickable-row" data-href="#">--}}
                                {{--<td width="10px">--}}
                                    {{--<a href="{{ route('organization-data.edit', $reportingOrg->id)}}" class="edit-activity pull-right">@lang('global.edit')</a>--}}
                                {{--</td>--}}
                                {{--<td class="organisation-name">--}}
                                    {{--<a href="#">DFIDUK</a>--}}
                                {{--</td>--}}
                                {{--<td class="activities">--}}
                                    {{--<span>2</span> Activities--}}
                                {{--</td>--}}
                                {{--<td class="sector">--}}
                                    {{--Government--}}
                                {{--</td>--}}
                                {{--<td>--}}
                                    {{--<div class="activity__status activity-status-Published">--}}
                                        {{--<span>Published</span>--}}
                                    {{--</div>--}}
                                {{--</td>--}}
                                {{--<td width="50px">--}}
                                    {{--<div class="view-more">--}}
                                        {{--<a href="#">⋯</a>--}}
                                        {{--<div class="view-more-actions">--}}
                                            {{--<ul>--}}
                                                {{--<li><a href="#" class="merge-with">Merge with ...</a></li>--}}
                                                {{--<li>--}}
                                                    {{--<a href="#" class="edit-this-org">Edit this organisation</a>--}}
                                                {{--</li>--}}
                                            {{--</ul>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                            {{--</tbody>--}}
                            <tbody>
                            @foreach($participatingOrg as $orgData)
                                <tr class="clickable-row" data-href="#">
                                    <td width="10px">
                                        <a href="#" class="edit-activity pull-right">@lang('global.edit')</a>
                                    </td>
                                    <td class="organisation-name">
                                        <a href="{{route('organization.show', $orgData->id)}}">
                                            {{ $orgData->name[0]['narrative'] ? $orgData->name[0]['narrative'] : trans('global.name_not_given')}}
                                        </a>
                                        <span class="identifier">GB-GOV-1</span>
                                    </td>
                                    <td class="activities">
                                        {{ $orgData->includedActivities() }}
                                    </td>
                                    <td class="sector">
                                        {{ $getCode->getCodeNameOnly('OrganizationType', $orgData->type, -4, 'Organization') }}
                                    </td>
                                    <td>
                                        <div class="activity__status activity-status-{{ $orgData->getStatus() }}">
                                            <span>{{ $orgData->getStatus() }}</span>
                                        </div>
                                    </td>
                                    <td width="50px">
                                        <div class="view-more">
                                            <a href="#">⋯</a>
                                            <div class="view-more-actions">
                                                <ul>
                                                    <li><a href="#" class="merge-with">Merge with ...</a></li>
                                                    <li>
                                                        <a href="{{ route('organization-data.edit', $orgData->id)}}" class="edit-this-org">Edit this organisation</a>
                                                    </li>
                                                    @if ($orgData->status === 3 && ($orgData->organization->published_to_registry === 1))
                                                        <form action="{{ route('organization-data.unpublish', $orgData->id) }}" method="POST">
                                                            {{ csrf_field() }}
                                                            <small>
                                                                <input class="button button-submit btn-xs btn-action-wrap" type="submit" value="@lang('global.unpublish_organisation')">
                                                            </small>
                                                        </form>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
