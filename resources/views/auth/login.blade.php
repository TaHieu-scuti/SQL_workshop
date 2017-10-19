{{--@extends('layouts.app')--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="/images/favicon.ico">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/style-responsive.css" rel="stylesheet" />
    <link href="/css/jquery-ui.min.css" rel="stylesheet" />
    <link href="/css/jquery-ui.structure.min.css" rel="stylesheet" />
    <link href="/css/jquery-ui.theme.min.css" rel="stylesheet" />
</head>
<body class="login-body">
    <div class="container">
    {{--@section('content')--}}
    <header class="header white-bg">
    <!--logo start-->
    <a href="index.html" class="logo">
        <img src="https://adgainersolutions.com/adgainer/application/images/logos/ad-gainer-logo-v1.1-250x54px-transp-bkgd-flat.png">
    </a>
    </header>
        <form class="form-horizontal form-signin" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <h2 class="form-signin-heading">sign in now</h2>
            <div class="login-wrap">
                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                    <div class="col-md-12">
                        <input id="username" type="text" class="form-control" name="username" placeholder="Username" value="{{ old('username') }}" required autofocus>

                        @if ($errors->has('username'))
                            <span class="help-block">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <div class="col-md-12">
                        <input id="password" type="password" class="form-control" placeholder="Password" name="password" required>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                {{-- <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                            </label>
                        </div>
                    </div>
                </div> --}}

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-primary styleColor">
                            Login
                        </button>

                        {{--<a class="btn btn-link" href="{{ route('password.request') }}">
                            Forgot Your Password?
                        </a>--}}
                    </div>
                </div>
            </div>
        </form>
    </div>
    {{--@endsection--}}
</body>
</html>
