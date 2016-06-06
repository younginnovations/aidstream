<div class="col-sm-12 add-wrap">
    <h2>Location</h2>
    <div class="col-sm-12" id="location-wrap">
        @if (old('location'))
            @if (old('recipient_country') == 'TZ')
                @foreach (old('location') as $index => $location)
                    <div class="col-sm-6" id="region-wrap">
                        {!! Form::label("location[$index][administrative][0][code]", 'Region', ['class' => 'control-label']) !!}
                        {!! Form::select("location[$index][administrative][0][code]", ['' => 'Select one of the following.'] + config('tz.location.region'), null, ['class' => 'form-control region', 'required' => 'required']) !!}

                        {!! Form::hidden("location[$index][administrative][0][vocabulary]", 'G1') !!}
                        {!! Form::hidden("location[$index][administrative][0][level]", '1') !!}
                    </div>

                    <div class="col-sm-6" id="district-wrap">
                        {!! Form::label("location[$index][administrative][1][code]", 'District', ['class' => 'control-label']) !!}
                        {!! Form::select("location[$index][administrative][1][code]", ['' => 'Select one of the following.'] + getDistrictFor(getVal($location, ['administrative', 0, 'code'])), old("location[$index][administrative][1][code]"), ['class' => 'form-control district', 'required' => 'required']) !!}

                        {!! Form::hidden("location[$index][administrative][1][vocabulary]", 'G1') !!}
                        {!! Form::hidden("location[$index][administrative][1][level]", '2') !!}
                    </div>
                @endforeach
            @else
                @foreach (old('location') as $index => $location)
                    <div class="col-sm-6" id="region-wrap">
                        {!! Form::label("location[$index][administrative][0][code]", 'Region', ['class' => 'control-label']) !!}
                        {!! Form::text("location[$index][administrative][0][code]", getVal($location, ['administrative', 0, 'code']), ['class' => 'form-control region', 'required' => 'required']) !!}

                        {!! Form::hidden("location[$index][administrative][0][vocabulary]", 'G1') !!}
                        {!! Form::hidden("location[$index][administrative][0][level]", '1') !!}
                    </div>

                    <div class="col-sm-6" id="district-wrap">
                        {!! Form::label("location[$index][administrative][1][code]", 'District', ['class' => 'control-label']) !!}
                        {!! Form::text("location[$index][administrative][1][code]", getVal($location, ['administrative', 1, 'code']), ['class' => 'form-control district', 'required' => 'required']) !!}

                        {!! Form::hidden("location[$index][administrative][1][vocabulary]", 'G1') !!}
                        {!! Form::hidden("location[$index][administrative][1][level]", '2') !!}
                    </div>
                @endforeach
            @endif
        @else
            @foreach ($project['location'] as $key => $location)
                <div class="col-sm-6" id="region-wrap">
                    {!! Form::label("location[$key][administrative][0][code]", 'Region', ['class' => 'control-label']) !!}
                    @if ($project['recipient_country'] == 'TZ')
                        {!! Form::select("location[$key][administrative][0][code]", ['' => 'Select one of the following.'] + config('tz.location.region'), getVal($location, ['administrative', 0, 'code']), ['class' => 'form-control region', 'required' => 'required']) !!}
                    @else
                        {!! Form::text("location[$key][administrative][0][code]", getVal($location, ['administrative', 0, 'code']), ['class' => 'form-control region', 'required' => 'required']) !!}
                    @endif
                    {!! Form::hidden("location[$key][administrative][0][vocabulary]", 'G1') !!}
                    {!! Form::hidden("location[$key][administrative][0][level]", '1') !!}
                </div>
                <div class="col-sm-6" id="district-wrap">
                    {!! Form::label('location[0][administrative][1][code]', 'District', ['class' => 'control-label']) !!}
                    @if ($project['recipient_country'] == 'TZ')
                        @if (array_key_exists(getVal($location, ['administrative', 0, 'code']), config('tz.location.district')))
                            {!! Form::select("location[$key][administrative][1][code]", ['' => 'Select one of the following.'] + getDistrictFor(getVal($location, ['administrative', 0, 'code'])), old("location[$key][administrative][1][code]"), ['class' => 'form-control district', 'required' => 'required']) !!}
                        @else
                            {!! Form::select("location[$key][administrative][1][code]", ['' => 'Select one of the following.'], null, ['class' => 'form-control district', 'required' => 'required']) !!}
                        @endif
                    @else
                        {!! Form::text("location[$key][administrative][1][code]", getVal($location, ['administrative', 1, 'code']), ['class' => 'form-control district', 'required' => 'required']) !!}
                    @endif

                    {!! Form::hidden("location[$key][administrative][1][vocabulary]", 'G1') !!}
                    {!! Form::hidden("location[$key][administrative][1][level]", '2') !!}
                </div>
            @endforeach

        @endif
        <button type="button" id="add-more-location-edit" class="add-more">Add Another Location</button>
    </div>
</div>
'