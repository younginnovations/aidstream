@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Iati Identifier</div>
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">Step 1</div>
                            <div class="panel-body">
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
