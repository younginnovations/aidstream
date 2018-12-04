<div class="registration-inner-wrapper">
    <div class="text-wrapper">
        <h2>@lang('organisation.organisation_information')</h2>
        <p>@lang('organisation.organisation_information_text')</p>
    </div>
    <div class="input-wrapper">
        {{-- <div class="choose-wrapper">
            @foreach($systemVersions as $id => $version)
                <div class="choose-option pull-left">
                    <input type="radio" value="{{$id}}" {{($id == $systemVersion) ? 'checked' : ($id == 1) ? 'checked' : ''}} name="systemVersion"/><span>{{$version}}</span>
                    <p>@lang(sprintf('organisation.%s', $version))</p>
                </div>
            @endforeach
        </div> --}}
        <input type="hidden" name="systemVersion" value="4">
        <div class="col-xs-12 col-md-12">
            {!! AsForm::text(['name' => 'organization[organization_name]', 'label' => trans('organisation.organisation_name'), 'class' => 'organization_name', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'html' => '<span class="availability-check hidden"></span>']) !!}
            {!! AsForm::text(['name' => 'organization[organization_name_np]', 'label' => trans('organisation.organisation_name_np'), 'class' => 'organization', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'html' => '<span class="availability-check hidden"></span>']) !!}

        </div>
        <div class="col-xs-12 col-md-12">
                {!! AsForm::text(['name' => 'organization[organization_name_abbr]', 'class' => 'organization_name_abbr', 'label' => trans('organisation.organisation_name_abbreviation'), 'help' => 'registration_org_name_abbr', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'html' => '<span class="availability-check hidden"></span>']) !!}
                {!! AsForm::select(['name' => 'organization[organization_type]', 'label' => trans('organisation.organisation_type'), 'class' => 'organization_type', 'data' => $orgType, 'required' => true , 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'empty_value' => trans('global.select_a_type')]) !!}

            </div>
        <div class="col-xs-12 col-md-12">
            {!! AsForm::select(['name' => 'organization[country]', 'value' => 'NP','label' => trans('organisation.organisation_country'), 'class' => 'country', 'data' => ['NP' => getVal($countries,['NP'])], 'required' => true , 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'empty_value' => trans('global.select_a_country')]) !!}
            {!! AsForm::text(['name' => 'organization[organization_address]', 'label' => trans('organisation.organisation_address'), 'class' => 'organization_address', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
        </div>
        <div class="col-xs-12 col-md-12">
            {!! AsForm::select(['name' => 'organization[organization_district][]', 'attr' => ['multiple' => 'multiple'],'label' => trans('organisation.district'), 'class' => 'organization_registration districts', 'required' => true , 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
            {!! AsForm::select(['name' => 'organization[organization_municipality][]','attr' => ['multiple' => 'multiple'], 'label' => trans('organisation.municipality'), 'class' => 'organization_registration municipalities', 'required' => true , 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
        </div>
        <div class="col-xs-12 col-md-12">
            {!! AsForm::select(['name' => 'organization[organization_registration_agency]', 'label' => trans('organisation.organisation_registration_agency'), 'class' => 'organization_registration_agency', 'data' => $orgRegAgency, 'required' => true , 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'empty_value' => trans('global.select_an_agency')]) !!}
            {!! AsForm::select(['name' => 'organization[organization_registration_district]','label' => trans('organisation.district'), 'class' => 'organization_registration registration_district', 'required' => true , 'parent' => 'col-xs-12 col-sm-6 col-md-6 registration_district_div']) !!} 
            {{-- {{ Form::hidden('organization[organization_registration_district]', null, ['class' => 'organization_registration registration_district', 'id' => 'organization[agency_name]','parent' => 'col-xs-12 col-sm-6 col-md-6 registration_district_div', 'empty_value' => trans('global.select_district')]) }} --}}
            {{-- {{ Form::hidden('organization[organization_registration_district]', null, ['class' => 'form-control organization_registration_district', 'id' => 'registration_district']) }} --}}
            {{ Form::hidden('organization[agencies]', ($agencies = getVal($regInfo, ['organization', 'agencies'], [])) ? $agencies : json_encode($orgRegAgency), ['class' => 'form-control agencies', 'id' => 'agencies', 'data-agency' => getVal($regInfo, ['organization', 'organization_registration_agency'])]) }}
            {{ Form::hidden('organization[new_agencies]', null, ['class' => 'form-control new_agencies', 'id' => 'organization[new_agencies]']) }}
            {{ Form::hidden('organization[agency_name]', null, ['class' => 'form-control agency_name', 'id' => 'organization[agency_name]']) }}
            {{ Form::hidden('organization[agency_website]', null, ['class' => 'form-control agency_website', 'id' => 'organization[agency_website]']) }}            
        </div>
        <div class="col-xs-12 col-md-12">
            {!! AsForm::text(['name' => 'organization[registration_number]', 'class' => 'registration_number', 'label' => trans('organisation.registration_number'), 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
            {!! AsForm::text(['name' => 'organization[organization_identifier]', 'help' => 'registration_org_identifier', 'label' => trans('organisation.organisational_iati_identifier'), 'class' => 'organization_identifier', 'id' => 'organization[organization_identifier]', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'attr' => ['readonly' => 'readonly']]) !!}
        </div>
    </div>
</div>
{{ Form::button(trans('global.continue_registration'), ['class' => 'btn btn-primary btn-submit btn-register btn-tab pull-right', 'type' => 'button',  'data-tab-trigger' => '#tab-users']) }}
