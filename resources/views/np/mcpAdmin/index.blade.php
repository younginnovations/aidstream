@extends('np.mcpAdmin.includes.sidebar')

@section('title', 'Dashboard')

@section('content')
	{{Session::get('message')}}

	<div class="col-xs-9 col-lg-9 content-wrapper">
		@include('includes.response')
		<div id="xml-import-status-placeholder"></div>
		<div class="panel panel-default">
			<div class="panel__heading dashboard-panel__heading">
				<div>
					<div class="panel__title">Dashboard</div>
					<p>Find all your stats here</p>
				</div>
			</div>
			<div class="panel__body">
				<div class="panel__status text-center">
					<div class="col-sm-4">
						<h2>Total Activities</h2>
						<span class="count">
                {{ $activitiesCount }}
                </span>
						<div class="published-num">
							<span>No. of activities published to IATI:</span>
							0
						</div>
					</div>
					<div class="col-sm-4">
						<h2>Activity by Status</h2>
						<div class="stats">
							<div class="background-masker header-top"></div>
							<div class="background-masker header-left"></div>
							<div class="background-masker header-right"></div>
							<div class="background-masker header-bottom"></div>
							<div class="background-masker subheader-left"></div>
							<div class="background-masker subheader-right"></div>
							<div class="background-masker subheader-bottom"></div>
							<div class="background-masker content-top"></div>
							<div class="background-masker content-first-end"></div>
							<div class="background-masker content-second-line"></div>
							<div class="background-masker content-second-end"></div>
							<div class="background-masker content-third-line"></div>
							<div class="background-masker content-third-end"></div>
							<svg width="300" height="100"><rect width="190" height="6" y="0" x="80" fill="#edd0d0" rx="3" ry="3" id="rect-overlay"></rect><rect width="190" height="6" y="25" x="80" fill="#f3dbb9" rx="3" ry="3" id="rect-overlay"></rect><rect width="190" height="6" y="50" x="80" fill="#d5e8f3" rx="3" ry="3" id="rect-overlay"></rect><rect width="190" height="6" y="75" x="80" fill="#b4eccd" rx="3" ry="3" id="rect-overlay"></rect><rect width="162.85714285714286" height="6" y="0" x="80" fill="#e15353" rx="3" ry="3" id="rect"></rect><rect width="0" height="6" y="25" x="80" fill="#fcb651" rx="3" ry="3" id="rect"></rect><rect width="0" height="6" y="50" x="80" fill="#4f7286" rx="3" ry="3" id="rect"></rect><rect width="27.142857142857142" height="6" y="75" x="80" fill="#52cc88" rx="3" ry="3" id="rect"></rect><text fill="#484848" y="7">Draft</text><text fill="#484848" y="32">Completed</text><text fill="#484848" y="57">Verified</text><text fill="#484848" y="82">Published</text><text fill="#484848" y="7" x="275">6</text><text fill="#484848" y="32" x="275">0</text><text fill="#484848" y="57" x="275">0</text><text fill="#484848" y="82" x="275">1</text></svg></div>
					</div>
					<div class="col-sm-4">
						<h2>Total Budget</h2>
						<span class="count" id="budgetTotal"><small>$</small><span id="totalBudget">{{ array_sum($budget) }}</span><small id="placeValue"></small></span>
					<div class="highest-budget">Highest budget in an activity: <span id="maxBudget">${{ $budget[0] }}</span></div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@section('script')
	<script src="{{url('/lite/js/dashboard.js')}}"></script>
	<script src="{{url('/lite/js/lite.js')}}"></script>
	<script>

	</script>
@stop
