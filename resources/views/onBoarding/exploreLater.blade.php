@extends('app')
@section('title', 'Welcome to AidStream')

@section('content')
    @include('includes.side_bar_menu')
    <div class="modal fade" tabindex="-1" role="dialog" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div><a href="activity">Close</a></div>
                    <div><img src="/img/logo.png"/></div>
                    <div>Continue exploring AidStream ?</div>
                    @if(auth()->user()->role_id == 1)
                        <div>Set up your account to Start Publishing to the IATI Registry</div>
                        <ol>
                            <a href="{{url('publishing-settings#1')}}">
                                <li>1</li>
                            </a>
                            <a href="{{url('publishing-settings#2')}}">
                                <li>2</li>
                            </a>
                            <a href="{{url('publishing-settings#3')}}">
                                <li>3</li>
                            </a>
                            <a href="{{url('activity-elements-checklist#4')}}">
                                <li>4</li>
                            </a>
                            <a href="{{url('default-values#5')}}">
                                <li>5</li>
                            </a>
                        </ol>
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
