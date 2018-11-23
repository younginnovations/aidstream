<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <title>{{ $isTz ? trans('title.tz_aidstream'): $isNp? 'Aidstream Nepal' : trans('title.aidstream') }}</title>
    <link rel="stylesheet" href="{{asset('/css/vendor.min.css')}}">
    {!! publicStylesheet() !!}
</head>
<body>
@if($isTz)
    @include('tz.partials.header')
@elseif($isNp)
    @include('np.partials.header')
@else
    @include('includes.header')
@endif

<section class="main-container">
    <div class="organisation-list-wrapper">
        <div class="col-md-12 text-center">
            @include('includes.response')
            <h2><strong>{{ count($organizations) }} @lang('global.organisations_have_published_their')
            <div class="width-900">
                <div class="search-org">
                    <label for="search" class="pull-left">@lang('perfectViewer.search'):</label>
                    <input id="search" type="text" placeholder="@lang('perfectViewer.search_organisations')" class="pull-left">
                </div>
            </div>
            <div class="organisations-list width-900">
                <ul class="org_list">
                    @foreach($organizations as $index => $organization)
                        <li>
                        <a href="{{ url('/who-is-using/'.$organization->org_slug)}}">
                            @if($organization->logo_url)
                                <img id="org_logo" src="{{ $organization->logo_url }}" alt="{{ $organization->name }}">
                                <label for="org_logo">{{ $organization->name }}</label>
                            @else
                                <label for="org_logo">{{ $organization->name }}</label>
                            @endif
                        </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
@if($isTz)
    @include('tz.partials.footer')
@else
    @include('includes.footer')
@endif

{{--<div class="hidden">--}}
{{--<ul class="no-image-logo">--}}
{{--<li><span><a href=""></a></span></li>--}}
{{--</ul>--}}
{{--<ul class="has-image-logo">--}}
{{--<li><a href=""><img/></a></li>--}}
{{--</ul>--}}
{{--</div>--}}
<script type="text/javascript" src="{{url('/js/jquery.js')}}"></script>
<script type="text/javascript" src="{{url('/js/bootstrap.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        function hamburgerMenu() {
            $('.navbar-toggle.collapsed').click(function () {
                $('.navbar-collapse').toggleClass('out');
                $(this).toggleClass('collapsed');
            });
        }

        hamburgerMenu();

        $("#search").on("keyup", function () {
            var g = $(this).val().toLowerCase();
            $(".org_list li a label").each(function () {
                var s = $(this).text().toLowerCase();
                $(this).closest('.org_list li')[s.indexOf(g) !== -1 ? 'show' : 'hide']();
            });
        });
    });


</script>
</body>
</html>
