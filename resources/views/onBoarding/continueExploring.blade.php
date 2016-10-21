@extends('app')
@section('title', 'Great Going')

@section('content')
    @include('includes.side_bar_menu')
    <div class="modal fade" tabindex="-1" role="dialog" id="myModal">
        <div class="modal-dialog explore-screen">
            <div class="modal-content text-center">
                <div class="modal-header"></div>
                <div class="modal-body">
                    <img src={{ url('/images/logo-large.svg') }} alt="Aidstream" width="130" height="124">
                    <h1>Great going {{ucfirst($firstname)}}!</h1>
                    @if(Auth::user()->isAdmin())
                        <span>Set up your account to Start Publishing to the IATI Registry.</span>
                        @if($status)
                            <span class="checked-icon">Checked icon</span>
                        @else
                            <span class="checked-icon">Unchecked icon</span>
                        @endif
                    @endif
                    @if($status)
                        <p>You have successfully setup your account. You can always go back to Settings page to change
                            your organisation's settings</p>
                    @elseif(isset($completedSteps))
                        <div class="onboarding-steps">
                            @include('onBoarding.stepsNumber')
                            <p>Please finish setting up your account to be able to publish your data.</p>
                        </div>
                    @endif
                    <a href="{{ url('dashboardTour') }}">
                        <button>Get to know your Dashboard</button>
                    </a>
                    <span class="explore-later"><a href="{{ url('activity')  }}">Explore Later</a></span>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('foot')
    <script>
        $(document).ready(function () {
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });
    </script>
@endsection
