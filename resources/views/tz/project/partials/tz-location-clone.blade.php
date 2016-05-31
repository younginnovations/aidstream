<div class="hidden col-sm-12" id="tz-location-clone">
    <div class="col-sm-4" id="region-wrap">
        {!! Form::label('location[index][administrative][0][code]', 'Region', ['class' => 'control-label']) !!}
        {!! Form::select('location[index][administrative][0][code]', ['' => 'Select one of the following.', 1 => 'one', 2 => 'two'], ['class' => 'form-control region', 'required' => 'required']) !!}

        {!! Form::hidden('location[index][administrative][0][vocabulary]', 'G1') !!}
        {!! Form::hidden('location[index][administrative][0][level]', '1') !!}
    </div>

    <div class="col-sm-4" id="district-wrap">
        {!! Form::label('location[index][administrative][1][code]', 'District', ['class' => 'control-label']) !!}
        {!! Form::select('location[index][administrative][1][code]', ['' => 'Select one of the following.', 1 => 'D1', 2 => 'D2'], ['class' => 'form-control district', 'required' => 'required', 'disabled' => 'disabled']) !!}

        {!! Form::hidden('location[index][administrative][1][vocabulary]', 'G1') !!}
        {!! Form::hidden('location[index][administrative][1][level]', '2') !!}
    </div>

    <a href="javascript:void(0)" onclick="removeLocation(this)">Remove</a>
</div>
