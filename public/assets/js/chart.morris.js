$(document).ready(function () {
    lineChart();
    donutChart();
    pieChart();
    $(window).resize(function () {
        window.lineChart.redraw();
        window.donutChart.redraw();
        window.pieChart.redraw();
    });
});
function lineChart() {
    window.lineChart = Morris.Bar({
        element: "line-chart",
        data: JSON.parse(roomStatsJson),
        xkey: "y",
        ykeys: ["a"],
        labels: ["Total Bookings"],
        barColors: ["#FFBF00"],
        hideHover: "auto",
        gridLineColor: "#eef0f2",
        resize: true,
        barSizeRatio: 0.4,
        xLabelAngle: 35,
        gridTextSize: 10,
    });
}
function pieChart() {
    var paper = Raphael("pie-chart");
    paper.piechart(
        100,
        100,
        90,
        [18.373, 18.686, 2.867, 23.991, 9.592, 0.213],
        {
            legend: [
                "Windows/Windows Live",
                "Server/Tools",
                "Online Services",
                "Business",
                "Entertainment/Devices",
                "Unallocated/Other",
            ],
        }
    );
}
