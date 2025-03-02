var prefixRoute = getRoutePrefix();
var global_graph_field_selected = '';
var lineChart;
var Script = function () {
    //morris chart
    $(function () {
        initMorris();

        getDataForLayouts();

        function getDataForLayouts() {
            let url = new URL(window.location.href);
            let numberOfPage = url.searchParams.get('page');
            let page = numberOfPage ? '?page=' + numberOfPage : '';
            if (page.startsWith('?')) {
                page += '&';
            } else {
                page += '?';
            }
            page += 'windowName=' + self.window.name;
            $.ajax({
                url: prefixRoute + "/getDataForLayouts" + page,
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend : function () {
                    sendingRequestTable();
                },
                success: function(response) {
                    $('.table_data_report').html(response.tableDataLayout);
                    $('.summary_report').html(response.summaryReportLayout);
                    setSelectedGraphColumn();
                    $('#time-period').html(response.timePeriodLayout);
                    $('#status-label').html(response.statusLayout);
                    $('#fieldsOnModal').html(response.fieldsOnModal);
                    $('.result-per-page').html(response.keyPagination);
                    filterColumnChecked();
                    hideSpinners();
                },
                error : function (response) {
                    checkErrorAjax(response);
                }
            });
        }

        $('.summary_report').delegate('.fields', 'click', function() {
            var $active = $('.statistic .fields.active');
            var columnName = $(this).data('name');
            updateMorris(columnName);
            //remove and add blue dot in summary boxes
            if (!$(this).hasClass('active')) {
                $(this).addClass('active');
                $active.removeClass('active');
                $(this).find('.small-blue-stuff').addClass('fa fa-circle');
                $active.find('.small-blue-stuff').removeClass('fa fa-circle');
            }
        });
        /*
        *
        * reload  pages
        * update blue dot in summary boxes same graph
        */
        $(document).ready(function(){
            $('.panel .panel-body a').css('color','#797979');
            let columnName = $('#selectpickerGraph').find("option:selected").data('column');
            updateMorris(columnName);
        })

        $('.date-option li:not(.custom-li, .custom-date)').click(function() {
            var option = $(this).data('date');
            var milestone = getFilterDate(option);
            $.ajax({
                url : prefixRoute + "/display-graph",
                type : "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {
                    'startDay' : milestone['startDay'],
                    'endDay' : milestone['endDay'],
                    'timePeriodTitle' : milestone['timePeriodTitle'],
                    'windowName' : self.window.name,
                },
                beforeSend : function () {
                    $('.morris-hover').css('display', 'none');
                    sendingRequest();
                },
                success : function (response) {
                    $('.selected-time').removeClass('open');
                    $('.date-option').removeClass('activeBlock');
                    processData(response);
                    $('#time-period').html(response.timePeriodLayout);
                },
                error : function (response) {
                    checkErrorAjax(response);
                },
                complete : function () {
                    completeRequest();
                }
            });
        });

        $('.apply-custom-period').click(function() {
            var option = $('.custom-li').data('date');
            var date= $('#datefilter').val().split(" - ");
            var startDay = date[0];
            var endDay = date[1];
            var milestone = getFilterDate(option);
            $.ajax({
                url : prefixRoute + "/display-graph",
                type : "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {
                    'startDay' : startDay,
                    'endDay' : endDay,
                    'timePeriodTitle' : milestone['timePeriodTitle'],
                    'windowName' : self.window.name,
                },
                beforeSend : function () {
                    $('.morris-hover').css('display', 'none');
                    sendingRequest();
                },
                success : function (response) {
                    $('.selected-time').removeClass('open');
                    $('.date-option').removeClass('activeBlock');
                    processData(response);
                    $('#time-period').html(response.timePeriodLayout);
                },
                error : function (response) {
                    checkErrorAjax(response);
                },
                complete : function () {
                    completeRequest();
                }
            });
        });
        /*
        *
        * onclicking status button
        * update table with selected status
        */
        $('.status-option li').click(function () {
            let option = $(this).data('status');
            let statusTitle = 'Show 0';
            let status = 'showZero';

            if (option === 'hideZero') {
                statusTitle = 'Hide 0';
                status = 'hideZero';
            }

            $.ajax({
                url : prefixRoute + "/display-graph",
                type : "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {
                    'status' : status,
                    'statusTitle' : statusTitle,
                    'windowName' : self.window.name,
                },
                beforeSend : function () {
                    $('.morris-hover').css('display', 'none');
                    sendingRequest();
                },
                success : function (response) {
                    processData(response);
                    $('#time-period').html(response.timePeriodLayout);
                    $('#status-label').html(response.statusLayout);
                },
                error : function (response) {
                    checkErrorAjax(response);
                },
                complete : function () {
                    completeRequest();
                }
            });
        });
        /*
        *
        * onclicking column button
        * update graph with selected column
        */
        $('#selectpickerGraph').on('changed.bs.select', function (e) {
            let columnName = $(this).find("option:selected").data('column');
            updateMorris(columnName);
        });

        $('.normal-report').click(function () {
            $('.specific-filter-item').removeClass('active');
            $('.panel .normal-report').addClass('active');
        });

        // initialise graph
        function initMorris()
        {
            lineChart = Morris.Line({
                element: 'report-graph',
                xkey: 'date',
                ykeys: ['clicks'],
                labels: ['clicks'],
                lineColors:['#0d88e0'],
                parseTime:false,
                lineWidth:'3px',
                pointSize: 0,
                smooth: false,
                redraw: true,
                hideHover: 'auto'
            });
        }

        $(window).on('resize', function() {
            lineChart.redraw();
            if (lineChart.data.length === 1) {
                $('#report-graph svg circle').attr('r', 3);
            }
        });

        function setMorris(data, fieldName)
        {
            lineChart.setData(data);
            lineChart.options.labels = [fieldName];
            if (data.length === 1) {
                $('.morris-hover').css('display', 'none');
                $('#report-graph svg circle').attr('r', 3);
            }
        }

        function processData(response)
        {
            var field = response.field;
            var data = [];
            if (response.displayNoDataFoundMessageOnGraph) {
                if (response.status === 'showZero') {
                    response.data = [{day: response.data[0].day, data: 0}, {day: response.data[1].day, data: 0}];
                    if (response.data[0].day === response.data[1].day) {
                        response.data.pop();
                    }
                    $('.no-data-found-graph').addClass('hidden-no-data-found-message-graph');
                } else {
                    $('.no-data-found-graph.hidden-no-data-found-message-graph')
                        .removeClass('hidden-no-data-found-message-graph');
                }
            } else {
                $('.no-data-found-graph').addClass('hidden-no-data-found-message-graph');
            }
            if (response.data.length === 1) {
                lineChart.options.hideHover = false;
            } else {
                lineChart.options.hideHover = 'auto';
            }
            for(var i = 0; i < response.data.length; i++) {
                data.push({ "date" : response.data[i].day, "clicks" : response.data[i].data });
            }
            setMorris(data, field);
            global_graph_field_selected = response.field;
            setSelectedGraphColumn();
        }

        function updateMorris(columnName)
        {
            $.ajax({
                url : prefixRoute + '/display-graph',
                type : 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {
                    'graphColumnName' : columnName,
                    'windowName' : self.window.name,
                },
                beforeSend : function () {
                    $('.morris-hover').css('display', 'none');
                    sendingRequest();
                },
                success : function(response)
                {
                    processData(response);
                    global_graph_field_selected = columnName;
                    $('#time-period').html(response.timePeriodLayout);
                    $('#selectpickerGraph').selectpicker('val', columnName);
                    $('.selectionOnGraph li a[data-tokens="'+ columnName +'"]').parent().addClass('selected');
                    $('button[data-id=selectpickerGraph] span.filter-option').text(response.column);
                    setSelectedGraphColumn();
                },
                error : function (response) {
                    checkErrorAjax(response);
                },
                complete : function () {
                    completeRequest();
                }
            });
        }

        $('.selectpickerBreadCrumbs').on('change', function(){
            var curent_url = $(this).find("option:selected").data("url");
            let requestId = $(this).find("option:selected").data('breadcumbs');

            var str = curent_url.lastIndexOf('/');
            var url = curent_url.substring(str + 1);
            let engine = $(this).find("option:selected").data('engine');
            processRequestBreadcrumbs(url, requestId, engine);
        });

        function processRequestBreadcrumbs(url, requestId, engine) {
            switch (url) {
                case 'agency-report' :
                    var obj = new Object();
                    var urlReload = 'client-report';

                    obj['directClient'] = 'Client';
                    obj['id_agency'] = requestId;
                    obj['id_client'] = 'all';
                    obj['id_account'] = 'all';
                    obj['id_campaign'] = 'all';
                    obj['id_adgroup'] = 'all';
                    obj['id_adReport'] = 'all';
                    obj['id_keyword'] = 'all';
                    obj['engine'] = engine;
                    if (requestId === 'all') {
                        urlReload = 'agency-report';
                    }
                    sendRequestData(obj, url, urlReload);
                    break;
                case 'client-report' :
                    var obj = new Object();
                    var urlReload = 'account_report';
                    obj['id_agency'] = $('select.id_Agency').find(':selected').attr('data-breadcumbs');
                    obj['id_client'] = requestId;
                    obj['id_account'] = 'all';
                    obj['id_campaign'] = 'all';
                    obj['id_adgroup'] = 'all';
                    obj['id_adReport'] = 'all';
                    obj['id_keyword'] = 'all';
                    obj['engine'] = engine;
                    if (requestId === 'all') {
                        urlReload = 'client-report';
                    }
                    sendRequestData(obj, url, urlReload);
                    break;
                case 'direct-client' :
                    var obj = new Object();
                    url = 'direct-client-report';
                    var urlReload = 'direct-client-report';
                    obj['directClient'] = 'DirectClient';
                    obj['id_agency'] = 'all';
                    obj['id_client'] = requestId;
                    obj['id_account'] = 'all';
                    obj['id_campaign'] = 'all';
                    obj['id_adgroup'] = 'all';
                    obj['id_adReport'] = 'all';
                    obj['id_keyword'] = 'all';
                    obj['engine'] = engine;
                    sendRequestData(obj, url, urlReload);
                    break;
                case 'direct-client-report' :
                    var obj = new Object();
                    var urlReload = 'account_report';

                    obj['id_agency'] = 'all';
                    obj['id_client'] = requestId;
                    obj['id_account'] = 'all';
                    obj['id_campaign'] = 'all';
                    obj['id_adgroup'] = 'all';
                    obj['id_adReport'] = 'all';
                    obj['id_keyword'] = 'all';
                    obj['engine'] = engine;
                    if (requestId === 'all') {
                        urlReload = 'direct-client-report';
                    }
                    sendRequestData(obj, url, urlReload);
                    break;
                case 'account_report' :
                    var obj = new Object();
                    var urlReload = 'campaign-report';
                    obj['id_agency'] = $('select.id_Agency').find(':selected').attr('data-breadcumbs');
                    obj['id_client'] = $('select.id_Client').find(':selected').attr('data-breadcumbs');
                    obj['id_account'] = requestId;
                    obj['id_campaign'] = 'all';
                    obj['id_adgroup'] = 'all';
                    obj['id_adReport'] = 'all';
                    obj['id_keyword'] = 'all';
                    obj['engine'] = engine;
                    if (requestId === 'all') {
                        urlReload = 'account_report';
                    }
                    sendRequestData(obj, url, urlReload);
                    break;
                case 'campaign-report' :
                    var obj = new Object();
                    obj['id_agency'] = $('select.id_Agency').find(':selected').attr('data-breadcumbs');
                    obj['id_client'] = $('select.id_Client').find(':selected').attr('data-breadcumbs');
                    obj['id_account'] = $('select.id_Account').find(':selected').attr('data-breadcumbs');
                    obj['id_campaign'] = requestId;
                    obj['id_adgroup'] = 'all';
                    obj['id_adReport'] = 'all';
                    obj['id_keyword'] = 'all';
                    sendRequestData(obj, url, 'adgroup-report');
                    break;
                case 'adgroup-report' :
                    var obj = new Object();
                    obj['id_agency'] = $('select.id_Agency').find(':selected').attr('data-breadcumbs');
                    obj['id_client'] = $('select.id_Client').find(':selected').attr('data-breadcumbs');
                    obj['id_account'] = $('select.id_Account').find(':selected').attr('data-breadcumbs');
                    obj['id_campaign'] = $('select.id_Campaign').find(':selected').attr('data-breadcumbs');
                    obj['id_adgroup'] = requestId;
                    obj['id_adReport'] = 'all';
                    obj['id_keyword'] = 'all';
                    if (engine === 'yss' || engine === 'adw') {
                        sendRequestData(obj, url, 'keyword-report');
                    } else if (engine === 'ydn') {
                        sendRequestData(obj, url, 'ad-report');
                    }
                    break;
                case 'ad-report' :
                    var obj = new Object();
                    obj['id_agency'] = $('select.id_Agency').find(':selected').attr('data-breadcumbs');
                    obj['id_client'] = $('select.id_Client').find(':selected').attr('data-breadcumbs');
                    obj['id_account'] = $('select.id_Account').find(':selected').attr('data-breadcumbs');
                    obj['id_campaign'] = $('select.id_Campaign').find(':selected').attr('data-breadcumbs');
                    obj['id_adgroup'] = $('select.id_AdGroup').find(':selected').attr('data-breadcumbs');
                    obj['id_adReport'] = requestId;
                    obj['id_keyword'] = 'all';
                    sendRequestData(obj, url, 'ad-report');
                    break;
                case 'keyword-report' :
                    var obj = new Object();
                    obj['id_agency'] = $('select.id_Agency').find(':selected').attr('data-breadcumbs');
                    obj['id_client'] = $('select.id_Client').find(':selected').attr('data-breadcumbs');
                    obj['id_account'] = $('select.id_Account').find(':selected').attr('data-breadcumbs');
                    obj['id_campaign'] = $('select.id_Campaign').find(':selected').attr('data-breadcumbs');
                    obj['id_adgroup'] = $('select.id_AdGroup').find(':selected').attr('data-breadcumbs');
                    obj['id_adReport'] = 'all';
                    obj['id_keyword'] = requestId;
                    sendRequestData(obj, url, 'keyword-report');
                    break;
                default:
                    break;
            }
        }
        function sendRequestData(datas, route, redirect) {
            datas.windowName = self.window.name;
            $.ajax({
                url : route + '/updateSession',
                type : 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : datas,
                success : function(response)
                {
                    window.location = redirect;
                },
                error : function (response) {
                    checkErrorAjax(response);
                },
            });
        }

        $('.table_data_report').delegate('.table-redirect', 'click', function() {
            let tableName = $(this).attr('data-table');
            let engine = $(this).data('engine');
            let accountID = $(this).data('adgainerid')
            if (!engine) {
                engine = $('select.id_Account').find(':selected').attr('data-engine');
            }
            let requestId = $(this).data('id');
            if (!requestId) {
                requestId = accountID;
            }
            processRequestBreadcrumbs(tableName, requestId, engine);
        })

        $('.breadcrumb-item-detail .title a').click(function() {
            let title = $(this).attr('data-title');
            let url = '';
            let requestId = 'all';
            let engine = '';
            switch (title) {
                case 'Agency' :
                    url = 'agency-report';
                    break;
                case 'Client' :
                    url = 'client-report';
                    break;
                case 'Account' :
                    url = 'account_report';
                    break;
                case 'Campaign' :
                    url = 'account_report';
                    requestId = $('select.id_Account').find(':selected').attr('data-breadcumbs');
                    break;
                case 'AdGroup' :
                    url = 'campaign-report';
                    requestId = $('select.id_Campaign').find(':selected').attr('data-breadcumbs');
                    break;
                default:
                    break;
            }
            processRequestBreadcrumbs(url, requestId, engine);
        });

        $('li.panel-body').click(function() {
            let url = $(this).find('a').attr('href');
            if (url) {
                window.location.href = url;
            }
        });

    });

}();

function setSelectedGraphColumn() {
    if (global_graph_field_selected) {
        $('div.summary_report .fields').removeClass('active');
        $('div.summary_report .fields').find('.small-blue-stuff').removeClass('fa fa-circle');
        let selectedField = $('div.summary_report .fields[data-name='+global_graph_field_selected+']');
        $(selectedField).addClass('active');
        $(selectedField).find('.small-blue-stuff').addClass('fa fa-circle');
    }
}
