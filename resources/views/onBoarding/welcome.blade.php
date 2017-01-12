@extends('app')
@section('title', trans('title.welcome_to_aidstream'))

@section('content')
    @include('includes.side_bar_menu')
    <div class="modal fade" tabindex="-1" role="dialog" id="myModal">
        <div class="modal-dialog welcome-screen">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <img src={{ url('/images/logo-large.svg') }} alt="Aidstream" width="130" height="124">
                    <h1>@lang('global.welcome_to_aidstream')</h1>
                    <span class="welcome-name">{{ ucfirst($firstname )}} {{ ucfirst($lastname )}}</span>
                    <p> @lang('global.thank_you_for_choosing_aidstream')</p>
                    @if($loggedInUser->isAdmin() && count($completedSteps) != 5)
                        <p>
                            <span>@lang('global.please_set_up_your_account')</span>
                            <a href="{{url('publishing-settings#1')}}" class="btn">@lang('global.setup_my_account')</a>
                        </p>
                        <p>
                            @lang('global.will_setup_later') <a href="/activity">@lang('global.go_to_dashboard')</a>
                        </p>
                    @else
                        <a href="/activity" class="btn">@lang('global.go_to_dashboard')</a>
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
