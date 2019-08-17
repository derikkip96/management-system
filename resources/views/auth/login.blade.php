<!DOCTYPE html>
<html lang="en">


<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1" name="viewport" />
	<title>Adrian || Login</title>
	<!-- Favicon-->
	<link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">

	<!-- Plugins Core Css -->
	<link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">

	<!-- Custom Css -->
	<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/css/pages/extra_pages.css') }}" rel="stylesheet" />
</head>

<body class="login-page">
	<div class="limiter">
		<div class="container-login100 page-background">
			<div class="wrap-login100">


							<form method="POST" action="{{ route('login') }}" class="login100-form validate-form">
								<span class="login100-form-logo">
									<img alt="" src="{{ asset('assets/images/loading.png') }}">
								</span>
								<span class="login100-form-title p-b-34 p-t-27">
									Log in
								</span>
								{{ csrf_field() }}
								<div class="wrap-input100 validate-input{{ $errors->has('email') ? ' has-error' : '' }}">
									<!-- <label for="email" class="control-label">E-Mail Address</label> -->

									<div class="">
										<input id="email" type="email" class="input100{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email" name="email" value="{{ old('email') }}" required autofocus>
										<i class="material-icons focus-input1001">mail</i>
										@if ($errors->has('email'))
										<span class="help-block">
											<strong>{{ $errors->first('email') }}</strong>
										</span>
										@endif
									</div>
								</div>

								<div class="wrap-input100 validate-input{{ $errors->has('password') ? ' has-error' : '' }}">
									<!-- <label for="password" class="control-label">Password</label> -->

									<div class="">
										<input id="password" type="password" class="input100" placeholder="Password" name="password" required>
										<i class="material-icons focus-input1001">lock</i>
										@if ($errors->has('password'))
										<span class="help-block">
											<strong>{{ $errors->first('password') }}</strong>
										</span>
										@endif
									</div>
								</div>


								<div class="custom-control custom-checkbox mb-3">
									<input type="checkbox" class="custom-control-input" {{ old('remember') ? 'checked' : '' }} id="keep-signed-in">
									<label class="custom-control-label" for="keep-signed-in">Keep Me Signed In</label>
								</div>
								<div class="container-login100-form-btn">
									<button type="submit" class="login100-form-btn">
										<i class="batch-icon batch-icon-key"></i>
										Sign In
									</button>
								</div>
								<hr>

								<div class="text-center p-t-50">
									<a class="txt1" href="{{ route('password.request') }}">
										Forgot Your Password?
									</a>
								</div>
							</form>
			</div>
		</div>
	</div>

<!-- Plugins Js -->

<script src="{{ asset('assets/js/app.min.js') }}"></script>

<!-- Extra page Js -->
<script src="{{ asset('assets/js/pages/examples/pages.js') }}"></script>

</body>


</html>
