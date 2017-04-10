@extends('lite.base.sidebar')

@section('title', 'Activities')

@section('content')
    {{Session::get('message')}}
    <div class="col-xs-9 col-lg-9 content-wrapper activity-wrapper">
        @include('includes.response')
        <div id="xml-import-status-placeholder"></div>
        <div class="panel panel-default">
            <div class="panel__heading">
                <div class="panel__title">@lang('lite/global.add_an_activity')</div>
                <p>@lang('lite/global.add_an_activity_in_simple_steps')</p>
            </div>
            <div class="panel__body">
                <div class="create-form create-project-form edit-form add-activity-form">
                    {!! form_start($form) !!}
                    <div class="form__block" id="basics">
                        <div class="col-md-9">
                            <h2>@lang('lite/global.basics')</h2>
                            <div class="row">
                                {!! form_until($form,'target_groups') !!}
                            </div>
                        </div>
                        <div class="panel__nav">
                            <div id="nav-anchor"></div>
                            <nav>
                                <div id="activity-progress-bar"></div>
                                <ul>
                                    <li><a href="#basics">@lang('lite/global.basics')</a>
                                    </li>
                                    <li><a href="#location">@lang('lite/global.location')</a></li>
                                    <li>
                                        <a href="#involved-organisations">@lang('lite/global.involved_organisations')</a>
                                    </li>
                                    <li><a href="#results-and-reports">@lang('lite/global.results_and_reports')</a>
                                    </li>

                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="form__block" id="location">
                        <div class="col-md-9">
                            <h2>@lang('lite/global.location')</h2>
                            <div class="row">
                                @if(isRegisteredForTz()) {!! form_until($form,'location') !!}
                                <div class="option-to-add-more form-group">
                                        <span class="pull-left">
                                            @lang('lite/global.add_more_country_than_tanzania')
                                        </span>
                                    <a href='#' class='pull-left display No' id='displayAddMore'><span class="pull-right">switch</span></a>
                                </div>
                                @else
                                    {!! form_until($form,'add_more_location') !!}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form__block" id="involved-organisations">
                        <div class="col-md-9">
                            <h2>@lang('lite/global.involved_organisations')</h2>
                            <div class="row">
                                {!! form_until($form,"add_more_implementing") !!}
                            </div>
                        </div>
                    </div>
                    <div class="form__block" id="results-and-reports">
                        <div class="col-md-9">
                            <h2>@lang('lite/global.results_and_reports')</h2>
                            <div class="row">
                                {!! form_until($form,"annual_report") !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        {!! form_rest($form) !!}
                        <a href="{{ $form->getModel() ? route('lite.activity.show', $activityId) : route('lite.activity.index')}}"
                           class="pull-right btn-go-back">@lang('lite/global.cancel_and_go_back')</a>
                    </div>
                    {!! form_end($form) !!}
                    <div class="funding_organisations-container hidden"
                         data-prototype="{{ form_row($form->funding_organisations->prototype()) }}">
                    </div>
                    <div class="implementing_organisations-container hidden"
                         data-prototype="{{ form_row($form->implementing_organisations->prototype()) }}">
                    </div>
                    @if(isRegisteredForTz())
                        <div class="administrative-container hidden">
                            @include('tz.partials.administrative')
                        </div>
                        <div class="location-container hidden">
                            @include('tz.partials.location')
                        </div>
                    @else
                        <div class="location-container hidden"
                             data-prototype="{{ form_row($form->location->prototype()) }}">
                        </div>
                        <div class="point-container hidden">
                            @include('lite.activity.partials.point')
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div id="countryChange" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Confirm</h4>
                </div>
                <div class="modal-body">
                    <p>It will remove the locations of this country. Are you sure?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default remove-location" data-dismiss="modal">Remove</button>
                    <button type="button" class="btn btn-default remove-location-cancel" data-dismiss="modal">Cancel</button>
                </div>
            </div>

        </div>
    </div>
@stop
@section('script')
    <script type="text/javascript" src="{{ url('/js/jquery.scrollto.js') }}"></script>
    <script type="text/javascript" src="{{ url('/lite/js/createActivity.js') }}"></script>
    <script type="text/javascript" src="{{url('/lite/js/progressBar.js')}}"></script>
    <script type="text/javascript" src="{{url('/js/leaflet.js')}}"></script>
    @if(isRegisteredForTz())
        <script type="text/javascript" src="{{url('/js/map.js')}}"></script>
        <script type="text/javascript" src="{{ url('/tz/js/location.js') }}"></script>
        <script type="text/javascript" src="{{ url('/tz/js/activity.js') }}"></script>
    @else
        <script type="text/javascript" src="{{url('/lite/js/map.js')}}"></script>
    @endif
    <script type="text/javascript" src="{{ url('/lite/js/location.js') }}"></script>

<script type="text/javascript">
var countryElement;
var selectElement;
var countryVal;
var geoJson = {!! $geoJson !!}
$(document).ready(function () {
  Location.clearUseMap();
  $('button.remove-location').on('click', function() {
    if(countryElement){
      Location.clearLocations();
    }
  });
  $('button.remove-location-cancel').on('click', function() {
    selectElement.val(countryVal).change();
  });
  var countryDetails = [{!! $countryDetails !!}];
  Location.prepareElements();
  @if(isRegisteredForTz())
    TzLocation.closeOpenedMap(countryDetails);
  TzLocation.onCountryChanged();
  @else
    Location.closeOpenedMap(countryDetails);
  Location.onCountryChange();
  Location.onCountryDelete();
  Map.reverseLocation();
  @endif
})
</script>
<script type="text/javascript">
var completedText = "{{strtolower(trans('lite/global.completed'))}}";
CreateActivity.editTextArea({!! empty(!$form->getModel()) !!});
CreateActivity.addToCollection();
CreateActivity.scroll();
CreateActivity.deleteCountryOnClick();
@if(isRegisteredForTz())
  var districts = [{!! json_encode(config('tz.district')) !!}];
Activity.changeRegionAndDistrict();
Activity.removeCountriesExceptTz();
Activity.addMoreAdministrative();
var district = [];
var counter = 0;
@foreach(getVal($form->getModel(),['location'],[]) as $index => $location )
  @foreach(getVal($location,['administrative'],[]) as $key => $administrative)
  @if(getVal($administrative,['region'],'') != "")
  district[counter] = [];
district[counter]["{!! getVal($administrative,['region'],'') !!}"] = "{!! getVal($administrative,['district'],'') !!}";
counter++;
@endif
  @endforeach
  @endforeach
  Activity.loadDistrictIfRegionIsPresent(district);
Activity.displayAddMoreCountry();
ProgressBar.setTotalFields(22);
ProgressBar.setLocationFields(5);
ProgressBar.addFilledFieldsForTz();
ProgressBar.onMapClicked();
@endif

    ProgressBar.calculateProgressBar(completedText);
        ProgressBar.calculate();
    </script>
@stop
