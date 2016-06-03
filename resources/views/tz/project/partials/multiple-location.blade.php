<div class="col-sm-12 add-wrap">
    <h2>Location</h2>
    <div class="col-sm-12" id="location-wrap">
        @foreach ($project['location'] as $key => $location)
            <div class="col-sm-6" id="region-wrap">
                {!! Form::label("location[$key][administrative][0][code]", 'Region', ['class' => 'control-label']) !!}
                @if ($project['recipient_country'] == 'TZ')
                    {!! Form::select("location[$key][administrative][0][code]", ['' => 'Select one of the following.', 1 => 'one', 2 => 'two'], getVal($location, ['administrative', 0, 'code']), ['class' => 'form-control region', 'required' => 'required']) !!}
                @else
                    {!! Form::text("location[$key][administrative][0][code]", getVal($location, ['administrative', 0, 'code']), ['class' => 'form-control region', 'required' => 'required']) !!}
                @endif
                {!! Form::hidden("location[$key][administrative][0][vocabulary]", 'G1') !!}
                {!! Form::hidden("location[$key][administrative][0][level]", '1') !!}
            </div>
            <div class="col-sm-6" id="district-wrap">
                {!! Form::label('location[0][administrative][1][code]', 'District', ['class' => 'control-label']) !!}
                @if ($project['recipient_country'] == 'TZ')
                    {!! Form::select("location[$key][administrative][1][code]", ['' => 'Select one of the following.', 1 => 'D1', 2 => 'D2'], getVal($location, ['administrative', 1, 'code']), ['class' => 'form-control district', 'required' => 'required']) !!}
                @else
                    {!! Form::text("location[$key][administrative][1][code]", getVal($location, ['administrative', 1, 'code']), ['class' => 'form-control district', 'required' => 'required']) !!}
                @endif

                {!! Form::hidden("location[$key][administrative][1][vocabulary]", 'G1') !!}
                {!! Form::hidden("location[$key][administrative][1][level]", '2') !!}
            </div>
        @endforeach
        <button type="button" id="add-more-location-edit" class="add-more">Add More</button>
    </div>
</div>
'