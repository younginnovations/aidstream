@extends('app')

@section('title', 'Organization - ' . $reporting_org['reporting_organization_identifier'])

@section('content')

    {{--*/
    $orgId = session('org_id');
    /*--}}
    @inject('code', 'App\Helpers\GetCodeName')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                @include('includes.breadcrumb')
                <?php
                $status_label = ['draft', 'completed', 'verified', 'published'];
                $btn_status_label = ['Completed', 'Verified', 'Published'];
                $btn_text = $status > 2 ? "" : $btn_status_label[$status];
                ?>
                <div class="element-panel-heading">
                    <div><span class="pull-left">Organization</span></div>
                    <div class="view-xml-btn org-xml-btn"><span class="pull-left"><a href="{{route('view.organizationXml', ['orgId' => $id])}}">View IATI XML file</a></span></div>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8 element-content-wrapper">
                    <div class="activity-status activity-status-{{ $status_label[$status] }}">
                        <ol>
                            @foreach($status_label as $key => $val)
                                @if($key == $status)
                                    <li class="active"><span>{{ $val }}</span></li>
                                @else
                                    <li><span>{{ $val }}</span></li>
                                @endif
                            @endforeach
                        </ol>
                        @if($btn_text != "")
                            <form method="POST" id="change_status" class="pull-right" action="{{ url('/organization/' . Auth::user()->org_id . '/update-status') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <input type="hidden" name="status" value="{{ $status + 1 }}">
                                @if($status == 2)
                                    <input type="button" value="Mark as {{ $btn_text }}" class="btn_confirm"
                                           data-title="Confirmation" data-message="Are you sure you want to Publish?">
                                @else
                                    <input type="submit" value="Mark as {{ $btn_text }}"> 
                                @endif
                            </form>
                        @endif
                    </div>
                    <div class="panel panel-default panel-element-detail element-show">
                        <div class="panel-body">
                            @include('Organization.partials.reportingOrganization')
                            @include('Organization.partials.organizationName')
                            @include('Organization.partials.totalBudget')
                            @include('Organization.partials.recipientOrganizationBudget')
                            @include('Organization.partials.recipientCountryBudget')
                            @include('Organization.partials.documentLink')
                            @include('Organization.partials.recipientRegionBudget')
                            @include('Organization.partials.totalExpenditure')
                        </div>
                    </div>
                </div>
                @include('includes.menu_org')
            </div>
        </div>
    </div>
@endsection
