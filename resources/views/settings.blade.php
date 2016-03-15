@extends('app')

@section('title', 'Settings')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper settings-wrapper">
                @include('includes.response')
                <div class="panel panel-default">
                    <div class="element-panel-heading">
                        <div>
                            Settings
                            @if(end($versions) !== $version)
                                {{--*/
                                $versionKey = array_search($version, $versions);
                                $newVersion = $versions[$versionKey + 1];
                                /*--}}
                            @endif
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="create-form settings-form">
                            {!! form_start($form) !!}
                            {!! form_until($form, 'default_field_values') !!}
                            <div class="settings-checkall-wrapper">
                                <h2>Choose elements to show/ hide</h2>
                                <div class="form-group">
                                    <label><input type="checkbox" class="checkAll"/><span
                                                class="check-text">Check All</span></label>
                                </div>
                                {!! form_row($form->default_field_groups) !!}
                            </div>
                            {!! form_end($form) !!}
                        </div>
                        <div class="collection-container hidden"
                             data-prototype="{{ form_row($form->reporting_organization_info->prototype()) }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
