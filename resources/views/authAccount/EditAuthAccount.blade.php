<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="/images/favicon.ico">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/css/morris.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/style-responsive.css" rel="stylesheet" />
    <link href="/css/jquery-ui.min.css" rel="stylesheet" />
    <link href="/css/jquery-ui.structure.min.css" rel="stylesheet" />
    <link href="/css/jquery-ui.theme.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
      <script src="/js/html5shiv.js"></script>
      <script src="/js/respond.min.js"></script>
      <![endif]-->
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
                            <span id="username" class="username" value="{{ Auth::user()->username }}">
                                {{Auth::user()->username}}
                            </span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu extended logout">
                            <div class="log-arrow-up"></div>
                            <li><a href="/logout"><i class="fa fa-key"></i>Log Out</a></li>
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
                <div class="menu_left col-lg-2">
                    <select id="myselect" class="form-control" disabled="true">

                        <option value="google" 
                            @if ($Authaccount->developerToken !== null)
                                selected="selected"
                            @endif
                        >
                            Google
                        </option>
                        <option value="yahoo_yss"
                            @if ($Authaccount->license !== null)
                                selected="selected"
                            @endif
                        >
                            Yahoo
                        </option>
                    </select>
                </div>
                <div class="menu_right col-lg-10">
                    <form action="{{route('update-account')}}" method="POST" class="form-horizontal form-auth" role="form" id="google" name="google" >
                        {{csrf_field()}}
                        <h2 class="form-auth-heading">Google Form</h2>
                        <div class="form-warp">
                            <input type="hidden" class="form-control" value="{{ Auth::user()->id }}" id="account_id" name="account_id">
                            <input type="hidden" class="form-control" value="{{ Auth::user()->username }}" id="userAgent" name="userAgent">

                            <div class="form-group">
                                <label class="control-label col-sm-4" for="">DeveloperToken</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="developerToken" name="developerToken" placeholder="DeveloperToken" value="{{$Authaccount->developerToken}}">
                                </div>
                            </div>

                            @if ($errors->has('developerToken'))
                                <div class="alert alert-danger">
                                    <strong>{{$errors->first('developerToken')}}</strong>
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="control-label col-sm-4" for="">ClientCustomerId</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="clientCustomerId" name="clientCustomerId" placeholder="ClientCustomerId" value="{{$Authaccount->clientCustomerId}}">
                                </div>
                            </div>

                            @if ($errors->has('ClientCustomerId'))
                                <div class="alert alert-danger">
                                    <strong>{{$errors->first('ClientCustomerId')}}</strong>
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="control-label col-sm-4" for="">OnBehalfOfAccountId</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="onBehalfOfAccountId" name="onBehalfOfAccountId" placeholder="OnBehalfOfAccountId" value="{{$Authaccount->onBehalfOfAccountId}}">
                                </div>
                            </div>

                            @if ($errors->has('OnBehalfOfAccountId'))
                                <div class="alert alert-danger">
                                    <strong>{{$errors->first('OnBehalfOfAccountId')}}</strong>
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="control-label col-sm-4" for="">OnBehalfOfPassword</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" rows="5" id="onBehalfOfPassword" name="onBehalfOfPassword">{{$Authaccount->onBehalfOfPassword}}</textarea>
                                </div>
                            </div>

                            @if ($errors->has('onBehalfOfPassword'))
                                <div class="alert alert-danger">
                                    <strong>{{$errors->first('onBehalfOfPassword')}}</strong>
                                </div>
                            @endif

                            <div class="col-sm-offset-2 col-sm-8">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>

                    <form action="{{route('store-account')}}" method="POST" class="form-horizontal form-auth" role="form" id="yahoo_yss" name="yahoo_yss">
                        {{csrf_field()}}
                        <h2 class="form-auth-heading">Yahoo Form</h2>
                        <div class="form-warp">
                            <input type="hidden" class="form-control" value="{{ Auth::user()->id }}" id="account_id" name="account_id">
                            <input type="hidden" class="form-control" value="{{ Auth::user()->username }}" id="userAgent" name="userAgent">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="">License</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="license" name="license" placeholder="License" value="{{$Authaccount->license}}">
                                </div>
                            </div>

                            @if ($errors->has('license'))
                                <div class="alert alert-danger">
                                    <strong>{{$errors->first('license')}}</strong>
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="control-label col-sm-4" for="">AccountId</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="accountId" name="accountId" placeholder="AccountId" value="{{$Authaccount->accountId}}">
                                </div>
                            </div>

                            @if ($errors->has('accountId'))
                                <div class="alert alert-danger">
                                    <strong>{{$errors->first('accountId')}}</strong>
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="control-label col-sm-4" for="">APIAccountId</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="apiAccountId" name="apiAccountId" placeholder="apiAccountId" value="{{$Authaccount->apiAccountId}}">
                                </div>
                            </div>

                            @if ($errors->has('apiAccountId'))
                                <div class="alert alert-danger">
                                    <strong>{{$errors->first('apiAccountId')}}</strong>
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="control-label col-sm-4" for="">APIAccountPassword</label>
                                <div class="col-sm-8">
                                    <textarea  class="form-control" rows="5" id="apiAccountPassword" name="apiAccountPassword">{{$Authaccount->apiAccountPassword}}</textarea>
                                </div>
                            </div>

                            @if ($errors->has('apiAccountPassword'))
                                <div class="alert alert-danger">
                                    <strong>{{$errors->first('apiAccountPassword')}}</strong>
                                </div>
                            @endif

                            <div class="col-sm-offset-2 col-sm-8">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
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
    <script src="/js/jquery-date-range.js"></script>
    <script src="/js/morris.min.js"></script>
    <script src="/js/raphael-min.js"></script>
    <script src="/js/jquery.tablesorter.min.js"></script>
    <script src="/js/moment-with-locales.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
    <!-- Custom js-->
    <script>
        $("#myselect").on("change", function() {
            $("#" + $(this).val()).show().siblings().hide();
        });

        $(document).ready(function (){
            var value = $("#myselect").find("option:selected").val();
            console.log(value);
            $("#" + value).show().siblings().hide();
        })
    </script>
    <script src="/js/common-function.js"></script>
<!-- s -->
</body>
</html>
