@extends('np.main')

@section('title', 'About')

@section('content')
<section class="header-banner">
	@include('np.partials.header')
	<div class="about-wrapper">
		<div class="col-md-12 text-center">
			<div class="about-banner-content">
				<h1>About us</h1>
				<p>
					AidStream Nepal is a platform developed to meet the transparency needs of CSOs, development partners, government and wider stakeholders in Nepal. It aims to empower civil society
					organizations working in Nepal by making <a href="http://iatistandard.org/">IATI</a> more accessible to them. Together, we want to make aid data open and transparent, and ultimately,
					use that data to improve aid outcomes in Nepal.
				</p>
			</div>
		</div>
		<div class="screenshot text-center">
			<img src="images/screenshot.png" alt="">
		</div>
	</div>
	<div class="bottom-line">
		<div class="information-section about-information-section">
			<div class="col-md-12 text-center">
				<div>
					<p>
						AidStream Nepal is available to all those Nepali CSOs implementing development projects. We have created a Nepal-specific, highly user-friendly version of AidStream to allow
						you to
						enter your project data with ease, and you can reach a dedicated Nepal support team by emailing us directly at <a href="mailto:support@aidstream.org">support@aidstream.org</a>.
					</p>
					<p>
						If you are interested in issues of transparency and accountability, create an account today and get started with AidStream Nepal. You can refer to our <a
								href="https://github.com/younginnovations/aidstream-tz/wiki/User-Guide" target="_blank">User Guide</a> to learn more about how the system works, and how you can make it
						work for you.
					</p>
					<p>
						AidStream Nepal is easy to use, helps to demonstrate and increase your impact and is free to use. It won’t cost you a single penny, so try it today and see your data form part
						of the global IATI standard for aid.
					</p>
					<p>
						AidStream Nepal is brought to you by <a href="http://www.yipl.com.np/">YoungInnovations</a>, with support from local organizations and partners in Nepal. It has been
						developed
						as an Open Source product, and is also available in <a href="https://github.com/younginnovations/aidstream-tz/">GitHub</a>. The platform has been forked from the existing
						global AidStream platform which is already a go-to place for global actors to publish data into IATI. Please feel free to reach out to us at <a
								href="mailto:support@aidstream.org">support@aidstream.org</a> if you want to contribute to this open source development.
					</p>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection