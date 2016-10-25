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
                    @if(Auth::user()->isAdmin())
                        <a href="{{url('publishing-settings')}}">Set up your account to Start Publishing to the IATI Registry.</a>
                    @endif
                    <a href="{{ url('dashboardTour') }}" class="btn">Get to know your Dashboard</a>
                    <span class="explore-later"><a href="{{ url('activity')  }}">Explore Later</a></span>
                </div>
            </div>
        </div>
    </div>
@endsection
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
