<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
	{{ header("Cache-Control: no-cache, no-store, must-revalidate")}}
	{{ header("Pragma: no-cache") }}
	{{ header("Expires: 0 ")}}
	<title>AidStream Nepal</title>
	<link rel="stylesheet" href="{{ asset('/css/vendor.min.css') }}">
	<link href="http://cdn.leafletjs.com/leaflet-0.7/leaflet.css" rel="stylesheet">
	{!!  publicStylesheet() !!}
	<link href="{{ asset('/css/jquery.jscrollpane.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css"/>
</head>
<body class="front-page">
<section class="header-banner">
	@include('np.partials.header')
	<div class="introduction-wrapper">
		<div class="container">
			<div class="col-md-6">
				<div class="hero-content">
					<h1 class="lead">Comprehensive online portal of development projects of Nepal</h1>
					<p class="description">Collaborative tool and reporting platform for local government and implementing NGOs facilitating closer partnership towards sustainable development</p>
					<a href="#" class="btn btn-primary get-started-btn">Know more</a>
				</div>
			</div>

			<div class="col-md-6 d-none">
				<div class="hero-image">
					<img src="./images/np/ic_laptop.svg" alt="Banner Laptop">
				</div>
			</div>
		</div>
	</div>
</section>

<section class="np-section" id="about">
	<h1 class="section-title">Why Open your Data?</h1>
	<div class="container">
		<div class="col-md-4">
			<div class="card">
				<div class="card-content">
					<div class="card-img">
						<img src="./images/np/ic_increases_transparency.svg" alt="Image 1">
					</div>
					<h2>Increases Transparency</h2>
					<p>The transparent nature of publicly accessible data exposes a side of an organization which is quite often kept under wraps</p>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<div class="card-content">
					<div class="card-img">
						<img src="./images/np/ic_develops_credibility.svg" alt="Image 2">
					</div>
					<h2>Develops Credibility</h2>
					<p>Digitization of government data and information provides the public with greater insight into government activities, service delivery, upcoming plans and policies</p>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<div class="card-content">
					<div class="card-img">
						<img src="./images/np/ic_promotes_innovation.svg" alt="Image 3">
					</div>
					<h2>Promotes Innovation</h2>
					<p>Access to knowledge resources in the form of data supports innovation  by reducing duplication and promoting reuse of existing resources</p>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="np-section">
	<h1 class="section-title">AidStream Nepal</h1>
	<div class="register-grid">
		<div class="container">
			<div class="register-row">
				<div class="register-item">
					<div class="content">
						<h2><img src="/images/np/ic_government-logo.svg" alt="Local Government Logo">Local Government</h2>
						<div class="info-list">
							<ul>
								<li>Overview of development project status in the region.</li>
								<li>Present regional development status with accuracy.</li>
								<li>Get early access to development projects for approval.</li>
								<li>Gain insights of flow between donors and implementing NGOs.</li>
								<li>Setup separate development projects page for local government.</li>
							</ul>
						</div>
						<div class="register-btn">
							<a href="mailto:support@aidstream.org" class="btn btn-primary get-started-btn outline">Get registered for free</a>
						</div>
					</div>
				</div>
				<div class="register-item">
					<div class="content">
						<h2><img src="/images/np/ic_ngo-logo.svg" alt="Implementing NGOs Logo">Implementing NGOs</h2>
						<div class="info-list">
							<ul>
								<li>Manage projects data in a central repository.</li>
								<li>Present yourself as open and transparent organisation.</li>
								<li>Publish what you do to the benefit of the public.</li>
								<li>Promote better visibility among donors and local government.</li>
								<li>Platform to explain roles and contribution to the people.</li>
							</ul>
						</div>
						<div class="register-btn">
							<a href="{{ url('/register') }}" class="btn btn-primary get-started-btn outline">Contact Us</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="np-section">
	<h1 class="section-title">AidStream Nepal Premium User</h1>
	<div class="container text-center">
		<div class="feature-logo">
			<a href="{{ url('/municipality/dhangadi') }}"><img src="/images/np/ic_aidstream_dhangadhi.png" alt="AidStream Dhangadi Logo"/></a>
		</div>

		<div class="feature-content">
			<p>Do you also want to get registered?</p>
			<a class="btn btn-primary get-started-btn" href="{{ url('/register') }}">Get Started</a>
		</div>
	</div>
</section>

<section class="np-section">
	<h1 class="section-title mb-lg">Why use AidStream Nepal?</h1>
	<div class="container">
		<div class="full-width">
			<div class="col-sm-6">
				<div class="img-block">
					<img src="./images/np/ic_use_interface.svg" alt="Image">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="highlight-content">
					<h2>Easy to use interfaces</h2>
					<p>AidStream Nepal has a clear, clean and easy-to-use interface which allows you to quickly add and edit activities, as well as offering you the option of importing activities in
						bulk.
						Using AidStream Nepal guarantees that your data will always be logged correctly in the right section, with no messy XML causing you to make mistakes!</p>
				</div>
			</div>
		</div>
		<div class="full-width reverse">
			<div class="col-sm-6">
				<div class="img-block">
					<img src="./images/np/ic_less_complexities.svg" alt="Image">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="highlight-content">
					<h2>Less IATI XML complexities</h2>
					<p>Entering data in AidStream Nepal is as easy as filling a simple form. The system hides all the complexities and technicalities of the xml. With AidStream Nepal, the necessity
						to
						understand
						the details of the IATI standard becomes lesser.</p>
				</div>
			</div>
		</div>
		<div class="full-width">
			<div class="col-sm-6">
				<div class="img-block">
					<img src="./images/np/ic_data_with_ease.svg" alt="Image">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="highlight-content">
					<h2>Publish data with ease!</h2>
					<p>AidStream Nepal uses the form you fill out to generate the necessary XML files and sends your data direct to the IATI Registry - all with a single click! All you have to do issit back and relax - AidStream Nepal takes care of everything else.</p>
				</div>
			</div>
		</div>
	</div>
</section>

@include('np.partials.footer')

<script src="{{ asset('/np/js/jquery.js') }}"></script>
<script src="{{ asset('/np/js/modernizr.js') }}"></script>
<script type="text/javascript" src="{{ asset('/np/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/np/js/jquery.mousewheel.js') }}"></script>
<script type="text/javascript" src="{{url('/js/jquery.jscrollpane.min.js')}}"></script>
<script type="text/javascript" src="{{url('/np/js/underscore-min.js')}}"></script>
<script type="text/javascript" src="{{url('/np/js/backbone-min.js')}}"></script>
<script type="text/javascript" src="{{url('/np/js/regions.js')}}"></script>
<script type="text/javascript" src="{{url('/np/js/leaflet/leaflet.js')}}"></script>
<script type="text/javascript" src="{{url('/np/js/mapping.js')}}"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>

<script>

    $(".hero-content .btn").click(function() {
        $('html,body').animate({
                scrollTop: $("#about").offset().top},
            'slow');
    });

    var projectCollection = new ProjectCollection({
        url: '/api/activities'
    });

    projectCollection.fetch({reset: true});
    var mapView = null;

    projectCollection.on('reset', function () {
        new SectorsListView({
            collection: projectCollection.getSectorsCollection(),
            projectsCollection: projectCollection
        }).render();
        new RegionListView({
            collection: projectCollection.getRegionsCollection(),
            projectsCollection: projectCollection
        }).render();
        new ProjectsListView({
            collection: projectCollection
        });
        mapview = new MapView({collection: projectCollection})
        projectCollection.trigger('renderAll');

        $(".zanzibar").click(function (e) {
            projectCollection.resetSectors();
            projectCollection.resetRegions();
            projectCollection.trigger("zoom-zanzibar");
            projectCollection.trigger("select-zanzibar");
            projectCollection.trigger('renderAll');
        });
        $(".resetmap").click(function (e) {
            projectCollection.resetSectors();
            projectCollection.resetRegions();
            projectCollection.trigger('renderAll');
        });
        $(".card-body").jScrollPane();
    });
</script>

<script type="text/template" id="project-list-item">
	<td><a href="<%= project['activity_url'] %>"><%= project["title"] %></a></td>
<td><a href="<%= project['organization_url']%>"><%= project["reporting_organisation"] %></a></td>
<td><%= project["sectors"] %></td>
</script>
<script type="text/template" id="region-checkbox-item">
<label>
<input type='checkbox' class='region-checkbox'/><%= region %>
</label>
</script>
<script type="text/template" id="sector-checkbox-item">
<label>
<input type='checkbox' class='sector-checkbox' /><%= sector %>
</label>
</script>


<script>
    $(document).ready(function () {
        function hamburgerMenu() {
            $('.navbar-toggle.collapsed').click(function () {
                $('.navbar-collapse').toggleClass('out');
                $(this).toggleClass('collapsed');
            });
        }

        hamburgerMenu();
    });
</script>

</body>
</html>

