@extends('app')

@section('title', trans('title.organisation').' - ' . $reporting_org['reporting_organization_identifier'])

@inject('getCode', 'App\Helpers\GetCodeName')

@section('content')

    {{--*/ $orgId = $id;   /*--}}
    @inject('code', 'App\Helpers\GetCodeName')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @if($loggedInUser->userOnBoarding)
                    @include('includes.steps')
                @endif
                @include('includes.response')
                @include('includes.breadcrumb')
                <?php
                $status_label = ['draft', 'completed', 'verified', 'published'];
                $btn_status_label = ['Completed', 'Verified', 'Published'];
                $btn_text = $status > 2 ? "" : $btn_status_label[$status];
                ?>
                <div class="element-panel-heading">
                    <div><span class="pull-left">@lang('global.organisation')</span></div>
                    <div class="view-xml-btn org-xml-btn"><span class="pull-left"><a href="{{route('view.organizationXml', ['orgId' => $id])}}">@lang('global.view_iati_xml_file')</a></span>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="activity-status activity-status-{{ $status_label[$status] }}">
                        <ol>
                            @foreach($status_label as $key => $val)
                                @if($key == $status)
                                    <li class="active"><span>{{ trans(sprintf('global.%s',$val)) }}</span></li>
                                @else
                                    <li><span>{{ trans(sprintf('global.%s',$val)) }}</span></li>
                                @endif
                            @endforeach
                        </ol>
                        @if($btn_text != "")
                            <form method="POST" id="change_status" class="pull-right" action="{{ url('/organizationData/' . $id . '/updateStatus') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <input type="hidden" name="status" value="{{ $status + 1 }}">
                                @if($status == 2)
                                    <input type="button" value="{{trans('global.mark_as')}} {{ trans(sprintf('global.%s',strtolower($btn_text))) }}" class="btn_confirm"
                                           data-title="Confirmation" data-message="{{ trans('global.are_you_sure_to_publish') }}">
                                @else
                                    <input type="submit" value="{{trans('global.mark_as')}} {{ trans(sprintf('global.%s',strtolower($btn_text )))}}">
                                @endif
                            </form>
                        @else
                            <div class="popup-link-content">
                                <a href="#" title="{{ucfirst($organizationDataStatus)}}" class="{{ucfirst($organizationDataStatus)}}">{{ucfirst($organizationDataStatus)}}</a>
                                <div class="link-content-message">
                                    {!!$message!!}
                                </div>
                            </div>
                        @endif
                    </div>
                    <a href="" class="pull-right print">@lang('global.print')</a>
                    <div class="panel panel-default panel-element-detail element-show">
                        <div class="panel-body panel-organization-body">
                            @include('Organization.partials.reportingOrganization')
                            @include('Organization.partials.organizationIdentifier')
                            @include('Organization.partials.organizationName')
                            @include('Organization.partials.totalBudget')
                            @include('Organization.partials.recipientOrganizationBudget')
                            @if(session('version') != 'V201')
                                @include('Organization.partials.recipientRegionBudget')
                            @endif
                            @include('Organization.partials.recipientCountryBudget')
                            @include('Organization.partials.totalExpenditure')
                            @include('Organization.partials.documentLink')
                        </div>
                    </div>
                </div>
                @include('includes.menu_org')
            </div>
        </div>
    </div>
@endsection
@section('foot')
    <script>
        $(document).ready(function () {
            $('[data-toggle="popover"]').popover({html: true});
        });
    </script>
@endsection
