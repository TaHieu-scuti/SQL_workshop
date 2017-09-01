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
            var milestone = getFilterDate(option);
            $.ajax({
                url : "/display-graph",
                type : "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {
                    'startDay' : milestone['startDay'],
                    'endDay' : milestone['endDay'],
                    'timePeriodTitle' : milestone['timePeriodTitle'],
                },
                success : function (response) {
                    var data = [];
                    for(var i = 0; i < response.data.length; i++) {
                        data.push({ "date" : response.data[i].day, "clicks" : response.data[i].data });
                    }
                    setMorris(data);
                    $('#time-period').html(response.timePeriodLayout);
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
