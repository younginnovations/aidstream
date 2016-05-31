<div class="hidden" id="implementing-org">
    <div class="col-sm-6">
        {!! Form::label('implementing_organization[index][implementing_organization_name]', 'Organization Name', ['class' => 'control-label required']) !!}
        {!! Form::text('implementing_organization[index][implementing_organization_name]', null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>

    <div class="col-sm-6">
        {!! Form::label('implementing_organization[index][implementing_organization_type]', 'Organization Type', ['class' => 'control-label required']) !!}
        {!! Form::select('implementing_organization[index][implementing_organization_type]', ['' => 'Select one of the following.'] + $organizationType, null, ['class' => 'form-control', 'required' => 'required']) !!}
    </div>

    <a href="javascript:void(0)" onclick="removeImplementing(this)">Remove</a>
</div>
