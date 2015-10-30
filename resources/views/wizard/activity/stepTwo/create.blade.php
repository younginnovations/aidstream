@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Activity Title and Description</div>
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">Step 2</div>
                            <div class="panel-body">
                                <h4>Your iati identifier is : {{ $iatiIdentifier['iati_identifier_text'] }}</h4>
                                {!! form($form) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-4">
                @include('wizard.activity.includes.menu_activity_element')
            </div>
        </div>
    </div>
@endsection
