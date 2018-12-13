@extends('np.municipalityAdmin.includes.base')

@section('title', trans('lite/title.activities'))

@inject('getCode', 'App\Helpers\GetCodeName')

@section('content')
    {{Session::get('message')}}

    <div class="col-xs-9 col-lg-9 content-wrapper">
        <div class="panel panel-default">
            <div class="panel__heading dashboard-panel__heading">
                <div>
                    <div class="panel__title">@lang('np/municipalityDashboard.organization_list')</div>
                </div>
            </div>
            <div class="panel__body">
                @if(count($organizationList) > 0)
                    <table class="panel__table no-header-table" id="dataTable">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th width="40%">@lang('np/municipalityDashboard.organization_name')</th>
                                <th>@lang('np/municipalityDashboard.activity_number')</th>
                                <th>@lang('np/municipalityDashboard.reporting_org')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $status_label = ['draft', 'completed', 'verified', 'published'];
                            ?>

                        @foreach ($organizationList as $key =>$organization)
                            <tr class="clickable-row" data-href= "{{ route('municipalityAdmin.organization-activities',[$organization->id]) }}">
                                <td>{{$key + 1}}</td>
                                <td>{{$organization->name}}</td>
                                <td >{{count($organization->activities)}}</td>
                                <td>
                                    @foreach($organization->reporting_org as $orgData)
                                    {{ $getCode->getCodeNameOnly('OrganizationType', $orgData['reporting_organization_type'], -4, 'Organization') }}
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @else
                    <div class="text-center no-data no-activity-data">
                        <p>@lang('np/municipalityDashboard.no_organization')</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{url('/np/js/dashboard.js')}}"></script>
    <script src="{{url('/np/js/np.js')}}"></script>
    <script>
    $(document).ready(function(){
        var searchPlaceholder = 'Search Organizations here';
        Np.dataTable(searchPlaceholder);

        $('#dataTable_filter>label').css('padding-top','80px');
    });
    </script>
@stop
