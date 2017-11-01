var prefixRoute = getRoutePrefix();
var Script = function () {
    //morris chart
    $(function () {
        var lineChart;
        initMorris();
        getMorris();
        $('.statistic .col-md-2').click(function() {
            var $active = $('.statistic .col-md-2.active');
            labels = $(this).data('name');
            $(this).addClass('active');
            $active.removeClass('active');
            var columnName = $(this).data('name');
            updateMorris(columnName);
        });

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
                    alert('Something went wrong!');
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
                    alert('Something went wrong!');
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
                    alert('Something went wrong!');
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
        $('#listSearch').delegate('li', 'click', function() {
            $.ajax({
                url : prefixRoute + "/display-graph",
                type : "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {
                    'graphColumnName' : $(this).text(),
                },
                beforeSend : function () {
                    sendingRequest();
                },
                success : function (response) {
                    processData(response);
                    $('#time-period').html(response.timePeriodLayout);
                    $('#graph-column').html(response.graphColumnLayout);
                },
                error : function (response) {
                    alert('Something went wrong!');
                },
                complete : function () {
                    completeRequest();
                }
            });
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
                hideHover:false,
                lineWidth:'3px',
                pointSize: 0,
                smooth: false,
                redraw: true,
                hideHover: 'auto',
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
        // set graph with `click` for y-axis
        function getMorris()
        {
            $.ajax({
                url : prefixRoute + '/display-graph',
                type : 'GET',
                beforeSend : function () {
                    sendingRequest();
                },
                success: function(response) {
                    processData(response);
                    $('#time-period').html(response.timePeriodLayout);
                    $('#graph-column').html(response.graphColumnLayout);
                },
                error : function (response) {
                    alert('Something went wrong!');
                },
                complete : function () {
                    completeRequest();
                }
            });
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
                },
                error : function (response) {
                    alert('Something went wrong!');
                },
                complete : function () {
                    completeRequest();
                }
            });
        }
        $('.selectpicker').on('change', function(){
            switch (prefixRoute) {
                case '/account_report':
                    var obj = new Object();
                    obj['id_account'] = $(this).find("option:selected").data("breadcumbs");
                    sendRequestDataGraph(obj);
                    sendRequestDataTable(obj);
                    break;
                case '/campaign-report' :
                    var obj = new Object();
                    obj['id_campaign'] = $(this).find("option:selected").data("breadcumbs");
                    obj['id_account'] = $('#id_Account').val();
                    sendRequestDataGraph(obj);
                    sendRequestDataTable(obj);
                    break;
                case '/adgroup-report' :
                    var obj = new Object();
                    obj['id_adgroup'] = $(this).find("option:selected").data("breadcumbs");
                    obj['id_account'] = $('#id_Account').val();
                    obj['id_campaign'] = $('#id_Campaign').val();
                    sendRequestDataGraph(obj);
                    sendRequestDataTable(obj);
                    break;
                case '/ad-report' :
                    var obj = new Object();
                    obj['id_adReport'] = $(this).find("option:selected").data("breadcumbs");
                    obj['id_account'] = $('#id_Account').val();
                    obj['id_campaign'] = $('#id_Campaign').val();
                    obj['id_adgroup'] = $('#id_AdGroup').val();
                    sendRequestDataGraph(obj);
                    sendRequestDataTable(obj);
                    break;

                default:
                    // code...
                    break;
            }
        });

        function sendRequestDataGraph(datas) {
            $.ajax({
                url : prefixRoute + '/display-graph',
                type : 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : datas,
                beforeSend : function () {
                    sendingRequest();
                },
                success : function(response)
                {
                    processData(response);
                    $('#time-period').html(response.timePeriodLayout);
                    $('#graph-column').html(response.graphColumnLayout);
                },
                error : function (response) {
                    alert('Something went wrong!');
                },
                complete : function () {
                    completeRequest();
                }
            });
        }

        function sendRequestDataTable(datas) {
            $.ajax({
                url : prefixRoute + '/update-table',
                type : 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : datas,
                beforeSend : function () {
                    sendingRequestTable();
                },
                success : function(response)
                {
                    processDataTable(response);
                    $('.table_data_report').html(response.tableDataLayout);
                    $('.summary_report').html(response.summaryReportLayout);
                    history.pushState("", "", link);
                },
                error : function (response) {
                    alert('Something went wrong!');
                },
                complete : function () {
                    completeRequestTable();
                }
            });
        }
    });

}();
