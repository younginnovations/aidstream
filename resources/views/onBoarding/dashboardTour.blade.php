@extends('app')
@section('title', 'Welcome to AidStream')

@section('content')
    @include('includes.side_bar_menu')
@endsection
@section('foot')
    <script src="/js/userOnBoarding.js"></script>
    <script>
        UserOnBoarding.dashboardTour();
    </script>
@endsection

