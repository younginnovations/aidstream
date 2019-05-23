@extends('settings.settings')

@section('panel-body')
    <div class="panel-body">
        <div class="create-form settings-form org-info-form">
            {{ Form::model($organization,['method'=>'POST', 'route' => 'organization-information.update','files' => true]) }}
            {!! form_rest($form) !!}
            @if(session('version') == 'V203')
            <div class="col-md-12 col-xs-12">
                {!! AsForm::select(['name'=>'secondary_reporter', 'class' => 'secondary_reporter','empty_value' => trans('global.select_one_of_the_following_options') ,'data'=>["1"=>'Yes',"0"=>'No'], 'label'=>trans('organisation.secondary_reporter'), 'parent'=>'col-xs-12 col-sm-6 col-md-6', 'required' => true]) !!}
            </div>
            @endif
            <div class="col-md-12 col-xs-12">
                {!! AsForm::text(['name'=>'user_identifier', 'class' => 'organization_name_abbr','required' => true,'label'=>trans('organisation.organisation_name_abbreviation'), 'parent'=>'col-xs-12 col-sm-6 col-md-6']) !!}
                {!! AsForm::select(['name'=>'organization_type','label' => trans('organisation.organisation_type'),'data' => $organizationTypes, 'value' => getVal((array) $organization->reporting_org, [ 0 ,'reporting_organization_type']),'empty_value' => trans('global.select_one_of_the_following_options') ,'required' => true,'parent'=>'col-xs-12 col-sm-6 col-md-6']) !!}
            </div>
            <div class="col-md-12 col-xs-12">
                {!! AsForm::text(['name' => 'address','label' => trans('organisation.address'),'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
                {!! AsForm::select(['name' => 'country','label' => trans('organisation.country'), 'data' => $countries,'empty_value' => trans('global.select_one_of_the_following_options'),'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6','id' => 'country']) !!}
            </div>
            <h2>IATI @lang('organisation.organisational_identifier')</h2>
            <div class="col-md-12 col-xs-12">
                {!! AsForm::select(['name' => 'registration_agency', 'data' => $registrationAgency,'required' => true, 'label' => trans('organisation.organisation_registration_agency'),'parent' => 'col-xs-12 col-sm-6 col-md-6', 'id' => 'registration_agency', 'empty_value' => trans('global.select_an_agency'), 'attr' => ['data-agency' => json_encode($registrationAgency)]]) !!}
                {!! AsForm::text(['name' => 'registration_number', 'required'=> true, 'label' => trans('organisation.organisation_registration_number'),'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
            </div>
            <div class="col-md-12 col-xs-12 single-form-wrap">
                {!! AsForm::text(['name'=>'organization_url' , 'label' => trans('organisation.organisation_website_url'), 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
            </div>
            <div class="col-md-12 col-xs-12 single-form-wrap">
                {!! AsForm::text(['name'=>'twitter' , 'label' => trans('organisation.organisation_twitter_handler'), 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
                <div class="description col-xs-12 col-sm-6 col-md-6"> @lang('organisation.please_insert_a_valid_twitter_username_example') '@oxfam ' or 'oxfam'</div>
            </div>
            <div class="col-md-12 col-xs-12 single-form-wrap">
                {!! AsForm::text(['name' => 'telephone', 'label' => trans('organisation.organisation_telephone_number'), 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
            </div>
            <div class="col-md-6 col-xs-12 upload-logo-block">
                {{ Form::label(null, trans('organisation.organisation_logo')) }}
                <div class="upload-logo">
                    {{ Form::file('organization_logo',['class'=>'inputfile form-control','id' => 'picture']) }}
                    <label for="file-logo">
                        <div class="uploaded-logo {{ $organization->logo ? 'has-image' : '' }}">
                            @if($organization->logo)
                                <img src="{{$organization->logo_url}}" height="150" width="150" alt="{{$organization->logo}}" id="selected_picture"/>
                            @else
                                <img src="" height="172" width="172" alt="Uploaded Image" id="selected_picture"/>
                            @endif
                            <div class="change-logo-wrap"><span class="change-logo">@lang('setting.change_logo')</span></div>
                        </div>
                    </label>

                    <span class="upload-label">@lang('setting.upload_your_organisation_logo')</span>
                </div>
                <div class="description col-xs-12 col-sm-6 col-md-6">@lang('setting.please_use_jpg')</div>
            </div>
            {{--<div class="col-md-6 col-xs-12 uploaded-logo">--}}

            {{--</div>--}}
            <div class="form-group">
                {{ Form::submit(trans('global.save_organisation_information'),['class' => 'btn btn-primary form-control btn-submit btn-form']) }}
            </div>
        </div>
        {{ Form::close() }}
        <div class="collection-container hidden"
             data-prototype="{{ form_row($form->narrative->prototype()) }}"></div>
    </div>
    </div>
    @if(session('status'))
        @include('settings.usernameUpdated')
    @endif
@endsection
@section('foot')
    <script src="{{ url('js/chunk.js') }}"></script>
    <script>
        var agencies = JSON.parse($('#registration_agency').attr('data-agency'));
        $('document').ready(function () {
            Chunk.displayPicture();
            Chunk.changeCountry();
            Chunk.abbrGenerator();
            @if(session('status'))
                 $('#usernameChanged').modal({
                backdrop: 'static',
                keyboard: false
            });
            @endif
        });
    </script>
@endsection
