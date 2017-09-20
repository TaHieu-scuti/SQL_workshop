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

    <title>Account report</title>

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
            <a href="index.html" class="logo">
                <img src="https://adgainersolutions.com/adgainer/application/images/logos/ad-gainer-logo-v1.1-250x54px-transp-bkgd-flat.png">
            </a>
            <!--logo end-->
            <div class="top-nav ">
                <ul class="nav pull-right top-menu">
                    <!-- user login dropdown start-->
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="username">
                                kakeya@scuti.asia
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
                <div class="row breadcrumb-list">
                    <div class="col-md-12">
                    <!--breadcrumbs start -->
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <div class="breadcrumb-item-detail">
                                <span class="title">Account<br></span>
                                <a data-toggle="dropdown" id="dropdownMenu1" class="dropdown-toggle" href="#">
                                    <span class="content">Account name</span>
                                </a>
                                <ul class="dropdown-menu extended tasks-bar" id="dropdownMenu1">
                                    <li>
                                        <p class="heading">
                                        <span class="glyphicon glyphicon-search"></span> 
                                        <input type="text" placeholder="Search for account">
                                        </p>
                                    </li>
                                    <div class="dropdown-menu scroll-menu">
                                    <li>
                                        <a href="#">
                                            <div class="desc">All engine accounts</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="desc">
                                                <img src="/images/yahoo.png">
                                                All Yahoo Japan accounts
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="desc">
                                                <img src="/images/yahoo.png">
                                                Samsung
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="desc">
                                                <img src="/images/yahoo.png">
                                                Oppo
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="desc">
                                                <img src="/images/yahoo.png">
                                                Nokia
                                            </div>
                                        </a>
                                    </li>
                                    </div>
                                </ul>
                            </div>
                        </li>
                        <li class="breadcrumb-item">
                            <div class="breadcrumb-item-detail">
                                <span class="title">Campaign<br></span>
                                <a data-toggle="dropdown" id="dropdownMenu2" class="dropdown-toggle" href="#">
                                    <span class="content">All campaign (5)</span>
                                </a>
                                <ul class="dropdown-menu extended tasks-bar" id="dropdownMenu2">
                                    <li>
                                        <p class="heading">
                                        <span class="glyphicon glyphicon-search"></span>
                                        <input type="text" placeholder="Search for campaign">
                                        </p>
                                    </li>
                                    <div class="dropdown-menu scroll-menu">
                                    <li>
                                        <a href="#">
                                            <div class="desc">All campaigns</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="desc">test 1</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="desc">test 2</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="desc">test 3</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="desc">test 4</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <div class="desc">test 5</div>
                                        </a>
                                    </li>
                                    </div>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <!--breadcrumbs end -->
                    </div>
                </div>
                <div class="row information">
                    <div class="col-md-4 col-xs-12">
                        <section class="panel">
                            <div class="panel-body">
                                <span class="title">Account<br></span>
                                <span class="element-name">
                                    <img src="/images/yahoo.png">
                                    Campaign name
                                </span>
                            </div>
                        </section>
                    </div>

                    <div class="col-md-3 col-xs-12 selected-time" id="add-time-period">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <section class="panel">
                            <div class="panel-body" id="time-period">
                                @include('layouts.time-period')
                            </div>
                        </section>
                        </a>
                        <ul class="col-md-2 dropdown-menu extended tasks-bar date-option">
                            <li data-date="today">
                                <a href="#">
                                    <div class="desc">Today</div>
                                </a>
                            </li>
                            <li data-date="yesterday">
                                <a href="#">
                                    <div class="desc">Yesterday</div>
                                </a>
                            </li>
                            <li data-date="last7DaysToday">
                                <a href="#">
                                    <div class="desc">Last 7 days( include today)</div>
                                </a>
                            </li>
                            <li data-date="last7days">
                                <a href="#">
                                    <div class="desc">Last 7 days( exclude today)</div>
                                </a>
                            </li>
                            <li data-date="last30days">
                                <a href="#">
                                    <div class="desc">Last 30 days</div>
                                </a>
                            </li>
                            <li data-date="last90days">
                                <a href="#">
                                    <div class="desc">Last 90 days</div>
                                </a>
                            </li>
                            <div class="dropdown-divider"></div>
                            <li data-date="thisWeek">
                                <a href="#">
                                    <div class="desc">This week</div>
                                </a>
                            </li>
                            <li data-date="thisMonth">
                                <a href="#">
                                    <div class="desc">This month</div>
                                </a>
                            </li>
                            <li data-date="thisQuarter">
                                <a href="#">
                                    <div class="desc">This quarter</div>
                                </a>
                            </li>
                            <li data-date="thisYear">
                                <a href="#">
                                    <div class="desc">This year</div>
                                </a>
                            </li>
                            <div class="dropdown-divider"></div>
                            <li data-date="lastBusinessWeek">
                                <a href="#">
                                    <div class="desc">Last business week (Mon – Fri)</div>
                                </a>
                            </li>
                            <li data-date="lastFullWeek">
                                <a href="#">
                                    <div class="desc">Last full week</div>
                                </a>
                            </li>
                            <li data-date="lastMonth">
                                <a href="#">
                                    <div class="desc">Last month</div>
                                </a>
                            </li>
                            <li data-date="lastQuarter">
                                <a href="#">
                                    <div class="desc">Last quarter</div>
                                </a>
                            </li>
                            <li data-date="lastYear">
                                <a href="#">
                                    <div class="desc">Last year</div>
                                </a>
                            </li>
                            <div class="dropdown-divider"></div>
                            <li class="custom-li" data-date="custom">
                                <a href="#">
                                    <div class="desc">Custom</div>
                                </a>
                            </li>
                            <li id="datepicker" class="custom-date">
                            <form action="#" class="form-horizontal tasi-form">
                                <div class="form-group col-md-12">
                                    <div class="input-group col-md-6">
                                        <span class="input-group-addon">From</span>
                                        <input type="text" class="form-control dpd1 " id ="from" name="from">
                                    </div>
                                    <div class="input-group col-md-6">
                                        <span class="input-group-addon">To</span>
                                        <input type="text" class="form-control dpd2" id ="to" name="to">
                                    </div>
                                    <div style="clear: both;">
                                        <button type="button" class="btn btn-primary apply-custom-period">Apply</button>
                                        <button type="button" class="btn btn-danger">Cancel</button>
                                    </div>
                                </div>
                            </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row group">
                    <div class="col-md-8 col-xs-12 pull-left">
                        <ul class="panel">
                            <li class="panel-body">
                                <a href="campaign-list.html">
                                    CAMPAIGNS
                                </a>
                            </li>
                            <li class="panel-body">
                                <a href="#">
                                    AD GROUPS
                                </a>
                            </li>
                            <li class="panel-body">
                                <a href="keywords.html">
                                    KEYWORDS
                                </a>
                            </li>
                            <li class="panel-body">
                                <a href="ad-list.html">
                                    ADS
                                </a>
                            </li>
                            <li class="panel-body separator">
                            </li>
                            <li class="panel-body">
                                <a href="prefectures.html">
                                    PREFECTURES
                                </a>
                            </li>
                            <li class="panel-body">
                                <a href="time-zone.html">
                                    BY TIME ZONE
                                </a>
                            </li>
                            <li class="panel-body">
                                <a href="days-of-the-week.html">
                                    BY DAYS OF THE WEEK
                                </a>
                            </li>
                            <li class="panel-body">
                                <a href="./devices.html">
                                    DEVICES
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-2 col-xs-12 selection-dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <section class="panel">
                            <div class="panel-body" id="graph-column">
                                @include('layouts.graph-column')
                            </div>
                        </section>
                        </a>
                        <ul class="col-md-2 dropdown-menu extended tasks-bar">
                            <li>
                                <p class="heading">
                                <span class="glyphicon glyphicon-search"></span>
                                <input type="text" placeholder="Search by columns" onkeyup="searchUp()" id="txtLiveSearch">
                                </p>
                            </li>
                            <div id="listSearch">
                                @foreach ($columnsLiveSearch as $columnsSearch)
                                <li>
                                    <a href="#">
                                        <div class="desc">{{ $columnsSearch }}</div>
                                    </a>
                                </li>
                                @endforeach
                            </div>
                        </ul>
                    </div>
                    <div class="col-md-2 col-xs-12 selection-dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <section class="panel">
                            <div class="panel-body" id= "status-label">
                                    @include('layouts.status-title')
                            </div>
                        </section>
                        </a>
                        <ul class="col-md-2 dropdown-menu extended tasks-bar status-option">
                            <li data-status="all">
                                <a href="#">
                                    <div class="desc">Show all</div>
                                </a>
                            </li>
                            <li data-status="disabled">
                                <a href="#">
                                    <div class="desc">Show disabled</div>
                                </a>
                            </li>
                            <li data-status="enabled">
                                <a href="#">
                                    <div class="desc">Show enabled</div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row line-chart">
                    <div class="loading-gif-on-graph hidden-graph"></div>
                    <section class="panel">
                        <div class="panel-body">
                            <div id="report-graph" class="graph"></div>
                        </div>
                    </section>
                </div>

                <div class="row statistic">
                    <div class="col-md-2 active" data-name="clicks">
                        <a href="javascript:void(0)">
                            <section class="panel">
                                <div class="panel-body">
                                    <span class="title">Clicks<br></span>
                                    <span class="content">
                                        <i class="fa fa-circle"></i>{{ number_format($totalDataArray['clicks'], 2, '.', ',') }}<br>
                                    </span>
                                </div>
                            </section>
                        </a>
                    </div>
                    <div class="col-md-2" data-name="impressions">
                        <a href="javascript:void(0)">
                            <section class="panel">
                                <div class="panel-body">
                                    <span class="title">Impr<br></span>
                                    <span class="content">{{ number_format($totalDataArray['impressions'], 2, '.', ',') }}<br> </span>
                                </div>
                            </section>
                        </a>
                    </div>
                    <div class="col-md-2" data-name="cost">
                        <a href="javascript:void(0)">
                            <section class="panel">
                                <div class="panel-body">
                                    <span class="title">Cost<br></span>
                                    <span class="content"><i class="fa fa-rmb"></i>{{ number_format($totalDataArray['cost'], 2, '.', ',') }}<br> </span>
                                </div>
                            </section>
                        </a>
                    </div>
                    <div class="col-md-2" data-name="averageCpc">
                        <a href="javascript:void(0)">
                            <section class="panel">
                                <div class="panel-body">
                                    <span class="title">AvgCPC<br></span>
                                    <span class="content">
                                        <i class="fa fa-rmb"></i>{{ number_format($totalDataArray['averageCpc'], 2, '.', ',') }}<br>
                                    </span>
                                </div>
                            </section>
                        </a>
                    </div>
                    <div class="col-md-2" data-name="averagePosition">
                        <a href="javascript:void(0)">
                            <section class="panel">
                                <div class="panel-body">
                                    <span class="title">Avg pos<br></span>
                                    <span class="content">
                                        {{ number_format($totalDataArray['averageCpc'], 2, '.', ',') }}<br>
                                    </span>
                                </div>
                            </section>
                        </a>
                    </div>
                    <div class="col-md-2" data-name="invalidClicks">
                        <a href="javascript:void(0)">
                            <section class="panel">
                                <div class="panel-body">
                                    <span class="title">InvalidClicks<br></span>
                                    <span class="content">
                                        {{ number_format($totalDataArray['invalidClicks'], 2, '.', ',') }}<br>
                                    </span>
                                </div>
                            </section>
                        </a>
                    </div>
                </div>

                <div class="row csv-file">
                    <div class="col-md-1 col-xs-6 icon dropdown">
                        <div class="dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <div class="glyphicon glyphicon-download-alt"></div>
                            <span class="caret"></span>
                        </div>
                        <ul class="dropdown-menu extended"  aria-labelledby="dropdownMenu1">
                           
                            <li><a href="{{ route('export_csv') }}">CSV</a></li>
                            <li><a href="{{ route('export_excel') }}">Excel</a></li>
                        </ul>
                    </div>
                    <div class="col-md-1 col-xs-6 columns">
                        <a data-toggle="modal" href="#columnsModal">
                        <section class="panel">
                            <div class="panel-body ">
                              Columns
                            </div>
                        </section>
                        </a>
                        <!-- Modal -->
                        <div class="modal fade" id="columnsModal" tabindex="-1" role="dialog" aria-labelledby="columnsModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title">Customize</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form class="form-horizontal">
                                        <div class="result-per-page">
                                            <p>Results per page</p>
                                            <div class="form-group">
                                                <input type="radio" name="resultPerPage" value="20" {{(int)$keyPagination === 20 ? 'checked' : ''}} > 20<br>
                                            </div> 

                                            <div class="form-group">
                                                <input type="radio" name="resultPerPage" value="50" {{(int)$keyPagination === 50 ? 'checked' : ''}}> 50<br>
                                            </div>

                                            <div class="form-group">
                                                <input type="radio" name="resultPerPage" value="100" {{(int)$keyPagination === 100 ? 'checked' : ''}}> 100
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="items-to-display">
                                            <p>Items to display</p>
                                            <div>
                                                <div class="form-group">
                                                    <input type="checkbox" id="selectAll"> Select all
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            @include('layouts.fields_on_modal')
                                            <div class="clearfix"></div>
                                        </div>
                                        <div>
                                            <button type="button" class="apply-button btn btn-primary">Apply</button>
                                        </div>
                                        </form>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                    </div>
                </div>
                <div class="table_data_report" style="position: relative;">
                <div class="loading-gif-on-table hidden-table"></div>
                    @include('layouts.table_data')
                </div>
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
    <!-- Custom js-->
    <script src="/js/common-function.js"></script>
    <script src="/js/common.js"></script>
    <script src="/js/morris-script.js"></script>
    <script>
        $(window).on('hashchange', function() {
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                if (page == Number.NaN || page <= 0) {
                    return false;
                } else {
                    getAccountReports(page);
                }
            }
        });
        $(document).ready(function() {
            $(document).on('click', '.pagination a', function (e) {
                getAccountReports($(this).attr('href').split('page=')[1]);
                e.preventDefault();
            });
        });
        function getAccountReports(page) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type : 'POST',
                url : 'update-table?page=' + page,
                dataType: 'json',
                success: function (data) {
                    $('.table_data_report').html('');
                    $('.table_data_report').html(data);
                    history.pushState("", "", '?page=' + page);
                },
                error: function (data) {
                    alert('Reports could not be loaded.');
                }
            });
        }
    </script>
</body>
</html>
