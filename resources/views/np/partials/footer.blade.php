{{--Footer--}}
<footer>
	<div class="footer-top-wrapper">
		<div class="contact text-center">
			<h2>Want to know more?</h2>
			<a href="mailto:support@aidstream.org" class="btn btn-primary get-started-btn">Contact Us</a>
		</div>

		<div class="container" style="position: relative;">
			<div class="footer-content">
				<div class="social-wrap">
					<ul>
						<li><a href="https://github.com/younginnovations/aidstream-tz" class="github" title="Fork us on Github">Fork us on Github</a></li>
						<li><a href="https://twitter.com/aidstream" class="twitter" title="Follow us on Twitter">Follow us on Twitter</a></li>
					</ul>
				</div>
				<div class="nav">
					<ul>
						<li><a href="{{ url('/about') }}">About</a></li>
						<li><a href="{{ url('/who-is-using') }}">Who's Using?</a></li>
					</ul>
					<ul>
						@if(auth()->check())
							<li><a href="{{ url((auth()->user()->role_id == 1 || auth()->user()->role_id == 2) ? route('np.activity.index') : config('app.super_admin_dashboard'))}}">Go to
									Dashboard</a></li>
						@else
							<li><a href="{{ route('login.overridden') }}">Login</a></li>
							<li><a href="{{ url('/register') }}">Register</a></li>
						@endif
					</ul>
				</div>
				<div class="logo">
					<div class="col-md-12 text-center">
						<a href="{{ url('/') }}" title="AidStream Nepal"><img src="/images/np/ic_aidstream-logo-np-white.png" alt="AidStream Nepal"></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
{{--End-Footer--}}

