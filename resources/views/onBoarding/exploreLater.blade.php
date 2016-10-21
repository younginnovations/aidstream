@extends('app')
@section('title', 'Welcome to AidStream')

@section('content')
    @include('includes.side_bar_menu')
    <script type="text/css"></script>
    <div class="modal fade" tabindex="-1" role="dialog" id="myModal">
        <div class="modal-dialog explore-screen">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <img src={{ url('/images/logo-large.svg') }} alt="Aidstream" width="130" height="124">
                    <h2>Continue exploring AidStream ?</h2>
                    @if(auth()->user()->role_id == 1)
                        <span>Set up your account to Start Publishing to the IATI Registry.</span>
                        @if($status)
                            <span class="checked-icon">Checked icon</span>
                        @else
                            <span class="unchecked-icon">Unchecked icon</span>
                        @endif
                    @endif
                    @if($status)
                        <div>You can always go back to Settings page to change your organisation's settings</div>
                    @else
                        @include('onBoarding.stepsNumber')
                    @endif
                    <a href="{{ url('dashboardTour') }}">
                        <button>Get to know your Dashboard</button>
                    </a>
                    <a href="#">You can always learn more on the Learn page.</a>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div><label>{{ Form::checkbox('check') }} I have already explored AidStream. Don't show me this
                            again.</label></div>
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
