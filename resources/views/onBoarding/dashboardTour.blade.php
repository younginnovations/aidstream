@extends('app')
@section('title', 'Welcome to AidStream')

@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-lg-9 content-wrapper activity-wrapper">
                <div class="panel panel-default">
                    <div class="panel-content-heading">
                        <div data-hint="Click here to view the list of Activities you have added." data-hintPosition="bottom-middle">Activities</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('foot')
    <script src="/js/userOnBoarding.js"></script>
    <script>
        var roleId = "{!! session('role_id') !!}";
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            introJs().addHints();
//            UserOnBoarding.dashboardTour();
        });
    </script>
@endsection

