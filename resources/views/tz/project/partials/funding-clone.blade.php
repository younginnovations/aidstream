<div class="hidden" id="funding-org">
    <div class="col-sm-6">
        {!! Form::label('funding_organization[index][funding_organization_name]', 'Organization Name', ['class' => 'control-label required']) !!}
        {!! Form::text('funding_organization[index][funding_organization_name]', null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>

    <div class="col-sm-6">
        {!! Form::label('funding_organization[index][funding_organization_type]', 'Organization Type', ['class' => 'control-label required']) !!}
        {!! Form::select('funding_organization[index][funding_organization_type]', ['' => 'Select one of the following.'] + $organizationType, null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>
    <a href="javascript:void(0)" onclick="removeFunding(this)" class="remove_from_collection">Remove</a>
</div>
