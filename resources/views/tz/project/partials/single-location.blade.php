<div class="col-sm-12 add-wrap">
    <h2>Location</h2>
    <div class="col-sm-12" id="location-wrap">
        <div class="col-sm-6" id="region-wrap">
            {!! Form::label('location[0][administrative][0][code]', 'Region', ['class' => 'control-label']) !!}
            {!! Form::select('location[0][administrative][0][code]', ['' => 'Select one of the following.', 1 => 'one', 2 => 'two'], null, ['class' => 'form-control region', 'required' => 'required']) !!}

            {!! Form::hidden('location[0][administrative][0][vocabulary]', 'G1') !!}
            {!! Form::hidden('location[0][administrative][0][level]', '1') !!}

            {{--{!! Form::label('location[0][administrative][0][code]', 'Region', ['class' => 'control-label']) !!}--}}
            {{--{!! Form::text('location[0][administrative][0][code]', null, ['class' => 'form-control region', 'required' => 'required']) !!}--}}

            {{--{!! Form::hidden('location[0][administrative][0][vocabulary]', 'G1') !!}--}}
            {{--{!! Form::hidden('location[0][administrative][0][level]', '1') !!}--}}
        </div>

        <div class="col-sm-6" id="district-wrap">
            {!! Form::label('location[0][administrative][1][code]', 'District', ['class' => 'control-label']) !!}
            {!! Form::select('location[0][administrative][1][code]', ['' => 'Select one of the following.', 1 => 'D1', 2 => 'D2'], null, ['class' => 'form-control district', 'required' => 'required']) !!}

            {!! Form::hidden('location[0][administrative][1][vocabulary]', 'G1') !!}
            {!! Form::hidden('location[0][administrative][1][level]', '2') !!}

            {{--{!! Form::label('location[0][administrative][1][code]', 'District', ['class' => 'control-label']) !!}--}}
            {{--{!! Form::text('location[0][administrative][1][code]', null, ['class' => 'form-control district', 'required' => 'required']) !!}--}}

            {{--{!! Form::hidden('location[0][administrative][1][vocabulary]', 'G1') !!}--}}
            {{--{!! Form::hidden('location[0][administrative][1][level]', '2') !!}--}}
        </div>
        <button type="button" id="add-more-location" class="add-more">Add More</button>
    </div>
</div>

{{--<div class="hidden col-sm-12" id="tz-location-clone">--}}
    {{--<div class="col-sm-4" id="region-wrap">--}}
        {{--{!! Form::label('location[0][administrative][0][code]', 'Region', ['class' => 'control-label']) !!}--}}
        {{--{!! Form::select('location[0][administrative][0][code]', ['' => 'Select one of the following.', 1 => 'one', 2 => 'two'], ['class' => 'form-control region', 'required' => 'required']) !!}--}}

        {{--{!! Form::hidden('location[0][administrative][0][vocabulary]', 'G1') !!}--}}
        {{--{!! Form::hidden('location[0][administrative][0][level]', '1') !!}--}}
    {{--</div>--}}

    {{--<div class="col-sm-4" id="district-wrap">--}}
        {{--{!! Form::label('location[0][administrative][1][code]', 'District', ['class' => 'control-label']) !!}--}}
        {{--{!! Form::select('location[0][administrative][1][code]', ['' => 'Select one of the following.', 1 => 'D1', 2 => 'D2'], ['class' => 'form-control district', 'required' => 'required', 'disabled' => 'disabled']) !!}--}}

        {{--{!! Form::hidden('location[0][administrative][1][vocabulary]', 'G1') !!}--}}
        {{--{!! Form::hidden('location[0][administrative][1][level]', '2') !!}--}}
    {{--</div>--}}

    {{--<a href="javascript:void(0)" onclick="removeLocation(this)">Remove</a>--}}
{{--</div>--}}

