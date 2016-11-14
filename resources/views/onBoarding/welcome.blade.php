@extends('app')
@section('title', 'Welcome to AidStream')

@section('content')
    @include('includes.side_bar_menu')
    <div class="modal fade" tabindex="-1" role="dialog" id="myModal">
        <div class="modal-dialog welcome-screen">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <img src={{ url('/images/logo-large.svg') }} alt="Aidstream" width="130" height="124">
                    <h1>Welcome to AidStream</h1>
                    <span class="welcome-name">{{ ucfirst($firstname )}} {{ ucfirst($lastname )}}</span>
                    <p> Thank you for choosing AidStream to publish your organisation's data to the IATI Registry.</p>
                    @if($loggedInUser->isAdmin() && count($completedSteps) != 5)
                        <p>
                            <span>Please set up your account to get started</span>
                            <a href="{{url('publishing-settings#1')}}" class="btn">Set up my Account</a>
                        </p>
                        <p>
                            I'll setup this later, <a href="/activity">go to dashboard</a>
                        </p>
                    @else
                        <a href="/activity" class="btn">Go to Dashboard</a>
                    @endif
                </div>
            </div>
        </div>
    </div>@endsection
@section('foot')
    <script>
        $(document).ready(function () {
            $('.introjs-hints').css('display', 'none');
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });
    </script>
@endsection
