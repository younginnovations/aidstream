@extends('app')
@section('title', 'Great Going')

@section('content')
    @include('includes.side_bar_menu')
    <div class="modal fade" tabindex="-1" role="dialog" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div><img src="/img/logo.png"/></div>
                    <div>Great going {{ucfirst($firstname)}}</div>
                    @if(Auth::user()->isAdmin())
                        <div>
                            <em>Set up your account to Start Publishing to the IATI Registry.</em>
                            @if(session('completed') == 'yes')
                                'Checked'
                            @else
                                'Unchecked'
                            @endif
                        </div>
                    @endif
                    <div>You can always go back to Settings page to change your organisation's settings</div>
                    <div><a href="{{ url('dashboardTour') }}">
                            <button>Get to know your Dashboard</button>
                        </a></div>
                    <div><a href="{{ url('activity')  }}">Explore Later</a></div>
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
