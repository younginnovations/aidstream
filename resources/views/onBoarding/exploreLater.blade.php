@extends('app')
@section('title', 'Welcome to AidStream')

@section('content')
    @include('includes.side_bar_menu')
    <script type="text/css"></script>
    <div class="modal fade" tabindex="-1" role="dialog" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div><a href="activity">Close</a></div>
                    <div><img src="/img/logo.png"/></div>
                    <div>Continue exploring AidStream ?</div>
                    @if(auth()->user()->role_id == 1)
                        <em>Set up your account to Start Publishing to the IATI Registry.</em>
                        @if($status)
                            Checked icon
                        @else
                            Unchecked icon
                        @endif
                    @endif
                    @if($status)
                        <div>You can always go back to Settings page to change your organisation's settings</div>
                    @else
                        @include('onBoarding.stepsNumber')
                    @endif
                    <div>
                        <a href="{{ url('dashboardTour') }}">
                            <button>Get to know your Dashboard</button>
                        </a>
                    </div>
                    <div>You can always learn more on the Learn page.</div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div><label>{{ Form::checkbox('check') }} I have already explored AidStream. Don't show me this again.</label></div>
                    <a data-controls-modal="your_div_id"
                       data-backdrop="static"
                       data-keyboard="false"
                       href="#"></a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('foot')
    <script src="/js/userOnBoarding.js"></script>
    <script>
        $(document).ready(function () {
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            UserOnBoarding.completedTour();
        });
    </script>
@endsection
