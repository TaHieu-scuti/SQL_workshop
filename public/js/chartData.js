var lineChartData = {
    labels: ["January", "February", "March", "April", "May", "June", "July"],
    datasets: [{
        label: "My first dataset",
        borderColor: [
            '0d88e0' 
        ],
        backgroundColor: [
            '#0d88e0'
        ],
        fill: false,
        data: [12, 19, 3, 5, 2, 3, 4],
        yAxisID: "y-axis-1",
    }, {
        label: "My second dataset",
        borderColor: [
            '#0d88e0'
        ],
        backgroundColor: [
            '#0d88e0' 
        ],
        fill: false,
        // data: [2, 9, 13, 15, 12, 3, 4],
        yAxisID: "y-axis-2",
    }],
}
var ctx = document.getElementById("myChart").getContext("2d");
var myChart = new Chart.Line(ctx, {
    data: lineChartData,
    options: {
        responsive: true,
        hoverMode: 'index',
        stacked: false,
        scales: {
            yAxes: [{
                type: "linear",
                display: true,
                position: "left",
                id: "y-axis-1",
            }, {
                type: "linear",
                display: true,
                position: "right",
                id: "y-axis-2",
                gridLines: {
                    drawOnChartArea: false,
                }
            }]
        }
    }
});