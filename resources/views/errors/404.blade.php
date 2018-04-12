<!DOCTYPE html>
<html lang="@lang('language.language')">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="/images/favicon.ico">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/css/morris.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/jquery-ui.min.css" rel="stylesheet" />
    <link href="/css/jquery-ui.structure.min.css" rel="stylesheet" />
    <link href="/css/jquery-ui.theme.min.css" rel="stylesheet" />
</head>
<body class="full-width">
<section id="container" class="">
    <!--header start-->
    <header class="header white-bg">
        <!--logo start-->
        <a href="/" class="logo">
            <img src="https://adgainersolutions.com/adgainer/application/images/logos/ad-gainer-logo-v1.1-250x54px-transp-bkgd-flat.png">
        </a>
        <!--logo end-->
        <div class="top-nav ">
            <ul class="nav pull-right top-menu">
                <!-- user login dropdown start-->
                <li><a href="{{URL::asset('')}}language/en"><img src="/images/english.png"></a></li>
                <li><a href="{{URL::asset('')}}language/ja"><img src="/images/jp.png"></a></li>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span id="username" class="username" value="{{ !is_null(Auth::user()) ? Auth::user()->username : Auth::guard('redisGuard')->user()->username }}">
                                {{!is_null(Auth::user()) ? Auth::user()->username : Auth::guard('redisGuard')->user()->username}}
                            </span>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu extended logout">
                        <div class="log-arrow-up"></div>
                        <li><a href="/logout"><i class="fa fa-key"></i>@lang('language.log_out')</a></li>
                    </ul>
                </li>
                <!-- user login dropdown end -->
            </ul>
            <!--search & user info end-->
        </div>
    </header>
    <!--header end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div class="container" style="text-align: center; font-size: 20px">
                <h1>@lang('language.error')</h1>

                <div class="alert alert-danger">@lang('language.notFound')</div>

                <p>@lang('language.request')</p>

                <p>@lang('language.contact')</p>

                <a href="javascript:history.go(-1)">@lang('language.goBack')</a>
            </div>
        </section>
    </section>
</section>
<!--main content end-->
<!-- Lib js -->
<script src="/js/jquery.js"></script>
<script src="/js/jquery-1.8.3.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<!-- s -->
</body>
</html>