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

        $('.date-option li').click(function() {
            var option = $(this).data('date');
            var today = moment();
            var startDay;
            var endDay;
            switch(option) {
                case 'today' : 
                    endDay = today.format("YYYY-MM-DD");
                    startDay = endDay;
                    break;
                case 'yesterday' : 
                    endDay = today.format("YYYY-MM-DD");
                    startDay = endDay;
                    break;
                case 'last7days' :
                    endDay = today.format("YYYY-MM-DD");
                    startDay = today.subtract(7, 'd').format("YYYY-MM-DD");
                    break;
                case 'last30days' :
                    endDay = today.format("YYYY-MM-DD");
                    startDay = today.subtract(30, 'd').format("YYYY-MM-DD");
                    break;
                case 'last90days' :
                    endDay = today.format("YYYY-MM-DD");
                    startDay = today.subtract(90, 'd').format("YYYY-MM-DD");
                    break;
                case 'thisWeek' :
                    endDay = today.format("YYYY-MM-DD");
                    startDay = today.startOf('isoweek').format("YYYY-MM-DD");
                    break;
                case 'thisMonth' :
                    endDay = today.format("YYYY-MM-DD");
                    startDay = today.startOf('month').format("YYYY-MM-DD");
                    break;
                case 'thisQuarter' :
                    endDay = today.format("YYYY-MM-DD");
                    startDay = today.startOf('quarter').format("YYYY-MM-DD");
                    break;
                case 'thisYear' :
                    endDay = today.format("YYYY-MM-DD");
                    startDay = today.startOf('year').format("YYYY-MM-DD");
                    break;
                case 'lastBusinessWeek' :
                    endDay = moment(moment().subtract(1, 'weeks')).day(3).format("YYYY-MM-DD");
                    startDay = moment().subtract(1, 'weeks').startOf('isoWeek').format("YYYY-MM-DD");
                    break;
                case 'last7DaysToday' :
                    endDay = today.add(1, 'days').format("YYYY-MM-DD");
                    startDay = today.subtract(7, 'd').format("YYYY-MM-DD");
                    break;
                case 'lastFullWeek' :
                    endDay = moment().subtract(1, 'weeks').endOf('isoWeek').format("YYYY-MM-DD");
                    startDay = moment().subtract(1, 'weeks').startOf('isoWeek').format("YYYY-MM-DD");
                    break;
                case 'lastMonth' :
                    endDay = moment().subtract(1, 'months').endOf('month').format("YYYY-MM-DD");
                    startDay = moment().subtract(1, 'months').startOf('month').format("YYYY-MM-DD");
                    break;
                case 'lastQuarter' :
                    endDay = moment().subtract(1, 'quarters').endOf('quarter').format("YYYY-MM-DD");
                    startDay = moment().subtract(1, 'quarters').startOf('quarter').format("YYYY-MM-DD");
                    break;
                case 'lastYear' :
                    endDay = moment().subtract(1, 'years').endOf('year').format("YYYY-MM-DD");
                    startDay = moment().subtract(1, 'years').startOf('year').format("YYYY-MM-DD");
                    break;
            }
            $.ajax({
                url : "/display-graph",
                type : "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {
                    'startDay' : startDay,
                    'endDay' : endDay
                },
                success : function (response) {
                    var data = [];
                    for(var i = 0; i < response.length; i++) {
                        data.push({ "date" : response[i].day, "clicks" : response[i].data });
                    }
                    setMorris(data);
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
                    status = '';
                    break;
                case 'disabled' : 
                    status = 'disabled';
                    break;
                case 'enabled' :
                    status = 'enabled';
                    break;
            }
            $.ajax({
                url : "/display-graph",
                type : "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {
                    'status' : status,
                },
                success : function (response) {
                    var data = [];
                    for(var i = 0; i < response.length; i++) {
                        data.push({ "date" : response[i].day, "clicks" : response[i].data });
                    }
                    setMorris(data);
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
                url : "/display-graph",
                type : "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {
                    'graphColumnName' : $(this).text(),
                },
                success : function (response) {
                    var data = [];
                    for(var i = 0; i < response.length; i++) {
                        data.push({ "date" : response[i].day, "clicks" : response[i].data });
                    }
                    setMorris(data);
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
                labels: ['Clicks'],
                lineColors:['#0d88e0'],
                parseTime:false,
                hideHover:false,
                lineWidth:'3px',
                pointSize: 0,
                smooth: false,
                redraw: true,
            });
            }

        function setMorris(data)
        {
            lineChart.setData(data);
        }
        // set graph with `click` for y-axis
        function getMorris()
        {
            $.ajax({
                url : '/display-graph',
                type : 'GET',
                success: function(response) {
                    var data = [];
                    for(var i = 0; i < response.length; i++) {
                        data.push({ "date" : response[i].day, "clicks" : response[i].data });
                    }
                    setMorris(data);
                },
            });
        }

        function updateMorris(columnName)
        {
            $.ajax({
                url : '/display-graph',
                type : 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {
                    'columnName' : columnName,
                },
                success : function(response)
                {
                    var data = [];
                    for(var i = 0; i < response.length; i++) {
                        data.push({ "date" : response[i].day, "clicks" : response[i].data });
                    }
                    setMorris(data);
                },
            });
        }
    });

}();
