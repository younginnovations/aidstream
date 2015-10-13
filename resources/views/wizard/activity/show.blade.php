@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Activity Data</div>

                    <div class="panel-body">

                        Activity View

                    </div>
                </div>
            </div>
            <div class="col-xs-4">
                @include('wizard.activity.includes.menu_activity_element')
            </div>
        </div>
    </div>
@endsection
