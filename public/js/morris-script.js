var prefixRoute = getVariable();
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
                case 'all' :
                    statusTitle = "all";
                    status = "all";
                    break;
                case 'disabled' :
                    statusTitle = 'disabled';
                    status = 'disabled';
                    break;
                case 'enabled' :
                    statusTitle = 'enabled';
                    status = 'enabled';
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
    });

}();
