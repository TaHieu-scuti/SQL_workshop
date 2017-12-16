var prefixRoute = getRoutePrefix();
var Script = function () {
    //morris chart
    $(function () {
        var lineChart;
        initMorris();

        $('.summary_report').delegate('.fields', 'click', function() {
            var $active = $('.statistic .fields.active');
            labels = $(this).data('name');
            var columnName = $(this).data('name');
            updateMorris(columnName);
            //remove and add blue dot in summary boxes
            if (!$(this).hasClass('active')) {
                $(this).addClass('active');
                $active.removeClass('active');
                $(this).find('.small-blue-stuff').addClass('fa fa-circle');
                $active.find('.small-blue-stuff').removeClass('fa fa-circle');
            }
            //remove and add class active,selected in dropdown select option
            $('li.active').removeClass('active');
            $('li.selected').removeClass('selected');
            if(!$('a[data-tokens = "' + columnName + '"]').parent().hasClass('active')){
                $('a[data-tokens = "' + columnName + '"]').parent().addClass('active');
                $('a[data-tokens = "' + columnName + '"]').parent().addClass('selected');
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
            $('.summary_report .fields').removeClass('active');
            $('.summary_report .fields').find('.small-blue-stuff').removeClass('fa fa-circle');
            $('.summary_report [data-name="'+ columnName +'"]').addClass('active');
            $('.summary_report [data-name="'+ columnName +'"]').find('.small-blue-stuff').addClass('fa fa-circle');
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
                },
                beforeSend : function () {
                    sendingRequest();
                },
                success : function (response) {
                    $('.selected-time').removeClass('open');
                    $('.date-option').removeClass('activeBlock');
                    processData(response);
                    $('#time-period').html(response.timePeriodLayout);
                },
                error : function (response) {
                    if (response.status === 403) {
                        if (isJson(response.responseText)) {
                            let obj = JSON.parse(response.responseText);
                            if (obj.error === 'session_expired') {
                                alert('Session expired');
                                window.location.href = obj.redirect_url;
                            }
                        }
                    } else {
                        alert('Something went wrong!');
                    }
                },
                complete : function () {
                    completeRequest();
                }
            });
        });

        $('.apply-custom-period').click(function() {
            var option = $('.custom-li').data('date');
            var startDay = $('.dpd1').val();
            var endDay = $('.dpd2').val();
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
                },
                beforeSend : function () {
                    sendingRequest();
                },
                success : function (response) {
                    $('.selected-time').removeClass('open');
                    $('.date-option').removeClass('activeBlock');
                    processData(response);
                    $('#time-period').html(response.timePeriodLayout);
                },
                error : function (response) {
                    if (response.status === 403) {
                        if (isJson(response.responseText)) {
                            let obj = JSON.parse(response.responseText);
                            if (obj.error === 'session_expired') {
                                alert('Session expired');
                                window.location.href = obj.redirect_url;
                            }
                        }
                    } else {
                        alert('Something went wrong!');
                    }
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
            var option = $(this).data('status');
            var status;
            switch(option) {
                case 'showZero' :
                    statusTitle = 'Show 0';
                    status = 'showZero';
                    break;
                case 'hideZero' :
                    statusTitle = 'Hide 0';
                    status = 'hideZero';
                    break;
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
                },
                beforeSend : function () {
                    sendingRequest();
                },
                success : function (response) {
                    processData(response);
                    $('#time-period').html(response.timePeriodLayout);
                    $('#status-label').html(response.statusLayout);
                },
                error : function (response) {
                    if (response.status === 403) {
                        if (isJson(response.responseText)) {
                            let obj = JSON.parse(response.responseText);
                            if (obj.error === 'session_expired') {
                                alert('Session expired');
                                window.location.href = obj.redirect_url;
                            }
                        }
                    } else {
                        alert('Something went wrong!');
                    }
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
        $('#selectpickerGraph').on('change', function() {
            let columnName = $(this).find("option:selected").data('column');
            updateMorris(columnName);
            $('.summary_report .fields').removeClass('active');
            $('.summary_report .fields').find('.small-blue-stuff').removeClass('fa fa-circle');
            $('.summary_report [data-name="'+ columnName +'"]').addClass('active');
            $('.summary_report [data-name="'+ columnName +'"]').find('.small-blue-stuff').addClass('fa fa-circle');
        });

        $('.specific-filter-item').click(function(){
            var value = $(this).data('value');
            $('.panel .panel-body').removeClass('active');
            $('.specific-filter-item[data-value = "'+ value +'" ]').addClass('active');
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
        });

        function setMorris(data, fieldName)
        {
            lineChart.setData(data);
            lineChart.options.labels = [fieldName];
        }

        function processData(response)
        {
            var field = response.field;
            var data = [];
            if(response.displayNoDataFoundMessageOnGraph) {
                $('.no-data-found-graph.hidden-no-data-found-message-graph')
                    .removeClass('hidden-no-data-found-message-graph');
            } else {
                $('.no-data-found-graph')
                    .addClass('hidden-no-data-found-message-graph');
            }
            for(var i = 0; i < response.data.length; i++) {
                data.push({ "date" : response.data[i].day, "clicks" : response.data[i].data });
            }
            setMorris(data, field);
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
                },
                beforeSend : function () {
                    sendingRequest();
                },
                success : function(response)
                {
                    processData(response);
                    $('#time-period').html(response.timePeriodLayout);
                    $('.summary_report fields active').removeClass('active');
                    $('#selectpickerGraph').find("option:selected").attr('selected', false);
                    $('#selectpickerGraph option[data-column="'+ columnName +'"]').attr('selected',true);
                    $('button[data-id=selectpickerGraph] span.filter-option').text(columnName);
                },
                error : function (response) {
                    if (response.status === 403) {
                        if (isJson(response.responseText)) {
                            let obj = JSON.parse(response.responseText);
                            if (obj.error === 'session_expired') {
                                alert('Session expired');
                                window.location.href = obj.redirect_url;
                            }
                        }
                    } else {
                        alert('Something went wrong!');
                    }
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
                case 'client-list' :
                    var obj = new Object();
                    var urlReload = 'account_report';

                    obj['id_adgainer'] = requestId;
                    obj['id_account'] = 'all';
                    obj['id_campaign'] = 'all';
                    obj['id_adgroup'] = 'all';
                    obj['id_adReport'] = 'all';
                    obj['id_keyword'] = 'all';
                    obj['engine'] = engine;
                    if (requestId === 'all') {
                        urlReload = 'client-list';
                    }
                    sendRequestData(obj, url, urlReload);
                    break;
                case 'account_report' :
                    var obj = new Object();
                    var urlReload = 'campaign-report';
                    obj['id_adgainer'] = $('select.id_Client').find(':selected').attr('data-breadcumbs');;
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
                    obj['id_adgainer'] = $('select.id_Client').find(':selected').attr('data-adgainerid');
                    obj['id_account'] = $('select.id_Account').find(':selected').attr('data-breadcumbs');
                    obj['id_campaign'] = requestId;
                    obj['id_adgroup'] = 'all';
                    obj['id_adReport'] = 'all';
                    obj['id_keyword'] = 'all';
                    sendRequestData(obj, url, 'adgroup-report');
                    break;
                case 'adgroup-report' :
                    var obj = new Object();
                    obj['id_adgainer'] = $('select.id_Client').find(':selected').attr('data-adgainerid');
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
                    obj['id_adgainer'] = $('select.id_Client').find(':selected').attr('data-adgainerid');
                    obj['id_account'] = $('select.id_Account').find(':selected').attr('data-breadcumbs');
                    obj['id_campaign'] = $('select.id_Campaign').find(':selected').attr('data-breadcumbs');
                    obj['id_adgroup'] = $('select.id_AdGroup').find(':selected').attr('data-breadcumbs');
                    obj['id_adReport'] = requestId;
                    obj['id_keyword'] = 'all';
                    sendRequestData(obj, url, 'ad-report');
                    break;
                case 'keyword-report' :
                    var obj = new Object();
                    obj['id_adgainer'] = $('select.id_Client').find(':selected').attr('data-adgainerid');
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
                    if (response.status === 403) {
                        if (isJson(response.responseText)) {
                            let obj = JSON.parse(response.responseText);
                            if (obj.error === 'session_expired') {
                                alert('Session expired');
                                window.location.href = obj.redirect_url;
                            }
                        }
                    } else {
                        alert('Something went wrong!');
                    }
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
                case 'Client' :
                    url = 'client-list';
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
