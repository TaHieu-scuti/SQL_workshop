var Script = function () {

    //morris chart

    $(function () {
      var data = [
           {"date": "Jan 1th", "clicks": 100},
           {"date": "Jan 2th", "clicks": 21},
           {"date": "Jan 5th", "clicks": 20},
           {"date": "Feb 1th", "clicks": 23},
           {"date": "Feb 2th", "clicks": 60},
           {"date": "Feb 5th", "clicks": 6},
           {"date": "Mar 1th", "clicks": 20},
           {"date": "Mar 2th", "clicks": 12},
           {"date": "Mar 5th", "clicks": 23}
      ];
      var lineChart = Morris.Line({
        element: 'report-graph',
        data: data,
        xkey: 'date',
        ykeys: ['clicks'],
        labels: ['Clicks'],
        lineColors:['#0d88e0'],
        parseTime:false,
        hideHover:true,
        lineWidth:'3px',
        pointSize: 0,
        smooth: false,
        redraw: true,
      });
      $(window).on('resize', function() { 
        lineChart.redraw();
         });
    });

}();




