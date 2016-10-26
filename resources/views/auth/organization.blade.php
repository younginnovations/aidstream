<div class="registration-inner-wrapper">
    <div class="text-wrapper">
        <h2>Organisation Information</h2>
        <p>Information about the <strong>organisation</strong> you want to create an account for on AidStream.</p>
    </div>

    <div class="input-wrapper">
        <div class="col-xs-12 col-md-12">
            {!! AsForm::text(['name' => 'organization[organization_name]', 'label' => 'Organisation Name', 'class' => 'organization_name', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'html' => '<span class="availability-check hidden"></span>']) !!}
            {!! AsForm::text(['name' => 'organization[organization_name_abbr]', 'class' => 'organization_name_abbr', 'label' => 'Organisation Name Abbreviation', 'help' => 'registration_org_name_abbr', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'html' => '<span class="availability-check hidden"></span>']) !!}
        </div>
        <div class="col-xs-12 col-md-12">
            {!! AsForm::select(['name' => 'organization[organization_type]', 'label' => 'Organisation Type', 'class' => 'organization_type', 'data' => $orgType, 'required' => true , 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'empty_value' => 'Select a Type']) !!}
            {!! AsForm::select(['name' => 'organization[country]', 'label' => 'Organisation Country', 'class' => 'country', 'label' => 'Organisation Country', 'data' => $countries, 'required' => true , 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'empty_value' => 'Select a Country']) !!}
        </div>
        <div class="col-xs-12 col-md-12">
            {!! AsForm::text(['name' => 'organization[organization_address]', 'label' => 'Organisation Address', 'class' => 'organization_address', 'label' => 'Organisation Address', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
            {!! AsForm::select(['name' => 'organization[organization_registration_agency]', 'label' => 'Organisation Registration Agency', 'class' => 'organization_registration_agency', 'data' => $orgRegAgency, 'required' => true , 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'empty_value' => 'Select an Agency', 'html' => '<button type="button" class="btn-xs btn-link add_agency">Add Agency</button>']) !!}
            {{ Form::hidden('organization[agencies]', ($agencies = getVal($regInfo, ['organization', 'agencies'], [])) ? $agencies : json_encode($orgRegAgency), ['class' => 'form-control agencies', 'id' => 'agencies', 'data-agency' => getVal($regInfo, ['organization', 'organization_registration_agency'])]) }}
            {{ Form::hidden('organization[new_agencies]', null, ['class' => 'form-control new_agencies', 'id' => 'organization[new_agencies]']) }}
            {{ Form::hidden('organization[agency_name]', null, ['class' => 'form-control agency_name', 'id' => 'organization[agency_name]']) }}
            {{ Form::hidden('organization[agency_website]', null, ['class' => 'form-control agency_website', 'id' => 'organization[agency_website]']) }}
        </div>
        <div class="col-xs-12 col-md-12">
            {!! AsForm::text(['name' => 'organization[registration_number]', 'class' => 'registration_number', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6']) !!}
            {!! AsForm::text(['name' => 'organization[organization_identifier]', 'help' => 'registration_org_identifier', 'label' => 'Organisational IATI Identifier', 'class' => 'organization_identifier', 'id' => 'organization[organization_identifier]', 'required' => true, 'parent' => 'col-xs-12 col-sm-6 col-md-6', 'attr' => ['readonly' => 'readonly']]) !!}
        </div>
    </div>
</div>
{{ Form::button('Continue Registration', ['class' => 'btn btn-primary btn-submit btn-register btn-tab pull-right', 'type' => 'button',  'data-tab-trigger' => '#tab-users']) }}
