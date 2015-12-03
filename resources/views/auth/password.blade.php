<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>Aidstream</title>

	    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
	    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

	    <!-- Fonts -->
	    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	    <!--[if lt IE 9]>
	    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->

	    @yield('head')

	</head>
	<body>
		<div class="login-wrapper">
			<div class="container-fluid login-container reset-container">
				<div class="row">
					<div class="col-lg-4 col-md-8 col-md-offset-2 form-body">
						<div class="panel panel-default">
							<div class="panel-heading">
								<img src="{{url('images/logo.png')}}" alt="">
								<div class="title">Reset password</div>
							</div>
							<div class="panel-body">
								@if (session('status'))
									<div class="alert alert-success">
										{{ session('status') }}
									</div>
								@endif

								@if (count($errors) > 0)
									<div class="alert alert-danger">
										<strong>Whoops!</strong> There were some problems with your input.<br><br>
										<ul>
											@foreach ($errors->all() as $error)
												<li>{{ $error }}</li>
											@endforeach
										</ul>
									</div>
								@endif

								<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">

									<div class="form-group">
										<label class="control-label">E-Mail Address</label>
										<div class="col-md-12">
											<input type="email" class="form-control" name="email" value="{{ old('email') }}">
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-12">
											<button type="submit" class="btn btn-primary btn-submit">
												Send Password Reset Link
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
