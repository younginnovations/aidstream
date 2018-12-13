@extends('np.main')

@section('title', 'Who Is Using')


@section('content')
	<section class="header-banner">
		@include('np.partials.header')
	</section>

	<section class="main-container">
		<div class="organisation-list-wrapper">
			<div class="col-md-12 text-center">
				@include('includes.response')
				<h2>
				<strong>{{ count($organizations) }} @lang('global.organisations_have_published_their')</strong>
				</h2>
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
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        $("#search").on("keyup", function () {
            var g = $(this).val().toLowerCase();
            $(".org_list li a label").each(function () {
                var s = $(this).text().toLowerCase();
                $(this).closest('.org_list li')[s.indexOf(g) !== -1 ? 'show' : 'hide']();
            });
        });
    });
</script>
@endsection
