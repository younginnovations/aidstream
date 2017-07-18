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
                    <div class="view-xml-btn org-xml-btn">
                        <span class="pull-left">
                            <a class="view-xml-btn org-xml-btn" href="{{ route('organization.create', session('org_id')) }}">@lang('global.create_organisation')</a>
                        </span>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                <span>
                    Below you’ll find your organization (reporting organization) and all other organizations that you’ve listed as partner organization in your activities. Any organization you’ll add here will be available as an option while associating an activity to a partner organization.
                </span>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <td>@lang('global.reporting_organisation')</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <a href="{{route('organization.show', $reportingOrg->id)}}">
                                        {{ $reportingOrg->name[0]['narrative'] }}
                                    </a>
                                </td>
                                <td>
                                    {{ $reportingOrg->type ? $getCode->getCodeNameOnly('OrganizationType', $reportingOrg->type, -4, 'Organization') : '' }}
                                </td>
                                <td>
                                    {{ $reportingOrg->getStatus() }}
                                </td>
                                <td>
                                    <a href="{{ route('organization-data.edit', $reportingOrg->id)}}" class="edit pull-right">@lang('global.edit')</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <td>@lang('global.partner_organisations')</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($participatingOrg as $orgData)
                                <tr>
                                    <td>
                                        <a href="{{route('organization.show', $orgData->id)}}">
                                            {{ $orgData->name[0]['narrative'] ? $orgData->name[0]['narrative'] : trans('global.name_not_given')}}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $orgData->includedActivities() }}
                                    </td>
                                    <td>
                                        {{ $getCode->getCodeNameOnly('OrganizationType', $orgData->type, -4, 'Organization') }}
                                    </td>
                                    <td>{{ $orgData->getStatus() }}</td>
                                    <td>
                                        <a href="{{ route('organization-data.delete', $orgData->id)}}" class="delete pull-right">@lang('global.delete')</a>
                                        <a href="{{ route('organization-data.edit', $orgData->id)}}" class="edit pull-right">@lang('global.edit')</a>
                                        @if ($orgData->status === 3 && ($orgData->organization->published_to_registry === 1))
                                            <form action="{{ route('organization-data.unpublish', $orgData->id) }}" method="POST">
                                                {{ csrf_field() }}
                                                <small>
                                                    <input class="button button-submit btn-xs btn-action-wrap" type="submit" value="@lang('global.unpublish_organisation')">
                                                </small>
                                            </form>
                                        @endif
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
