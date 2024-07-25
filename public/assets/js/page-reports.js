var isLoaded = false;
var activated_tab = 'nav-fleetchart';
var options = {
    // legend: 'none',
    // pieSliceText: 'label',
    // pieStartAngle: 100,
    titleTextStyle: {},
    legend: { position: 'right' },
    is3D: true,
    backgroundColor: 'transparent',
    chartArea:{
        left:10, top:25, width:'90%', height:'90%'
    }
};
jQuery(document).ready(function() {
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(initFleetChart);
    $('.form-datepicker').datepicker({
        autoclose:true,
        format: 'mm/dd/yyyy',
        todayHighlight: true,
        language: 'es',
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>',
        },
    });

    loadTab();

    $('.form-datepicker').on('change', function(){
        initFleetChart();
    });
});

var initFleetChart = function() {
    if(!isLoaded) isLoaded = true;
    showOverlay();
    $.ajax({
        url: "/reports/getPieChartData",
        type: 'POST',
        data: {
            from: formatDecodeDate($("#filter_from").val()),
            to: formatDecodeDate($("#filter_to").val()),
            _token: $('#token_ajax').val()
        },
        success: function (res) {
            hideOverlay();
            if(activated_tab == 'nav-fleetchart'){
                var container = document.getElementById('chart_fleet_status');
                var chart = new google.visualization.PieChart(container);
                var dataArray = Object.entries(res.fleet_status_data).map(([key, value]) => [
                    (key=='true'?'Available':(key=='in-service'?'In Service':(key=='check-out'?'In-Use':'Out of Service')))+': '+value,
                    value
                ]);
                options.title = 'Vehicle Status';
                dataArray.unshift([options.title, 'Count']);
                var data = google.visualization.arrayToDataTable(dataArray);
                chart.draw(data, options);

                container = document.getElementById('chart_fleet_type');
                chart = new google.visualization.PieChart(container);
                dataArray = Object.entries(res.fleet_type_data).map(([key, value]) => [
                    key + ': ' + value, value
                ]);
                options.title = 'Vehicle Type';
                dataArray.unshift([options.title, 'Count']);
                data = google.visualization.arrayToDataTable(dataArray);
                chart.draw(data, options);

                container = document.getElementById('chart_fleet_department');
                chart = new google.visualization.PieChart(container);
                dataArray = Object.entries(res.fleet_department_data).map(([key, value]) => [
                    key + ': ' + value, value
                ]);
                options.title = 'Vehicle Department';
                dataArray.unshift([options.title, 'Count']);
                data = google.visualization.arrayToDataTable(dataArray);
                chart.draw(data, options);
            }else if(activated_tab == 'nav-costchart'){
                var container = document.getElementById('chart_cost_status');
                var chart = new google.visualization.PieChart(container);
                var dataArray = Object.entries(res.fleet_status_cost).map(([key, value]) => [
                    (key=='true'?'Available':(key=='in-service'?'In Service':(key=='check-out'?'In-Use':'Out of Service'))) + ' Vehicles: ' + value + '$', value
                ]);
                options.title = 'Vehicle Cost by Status';
                dataArray.unshift([options.title, 'Count']);
                var data = google.visualization.arrayToDataTable(dataArray);
                chart.draw(data, options);

                container = document.getElementById('chart_cost_type');
                chart = new google.visualization.PieChart(container);
                dataArray = Object.entries(res.fleet_type_cost).map(([key, value]) => [
                    key + ': ' + value + '$', value
                ]);
                options.title = 'Vehicle Cost by Type';
                dataArray.unshift([options.title, 'Count']);
                data = google.visualization.arrayToDataTable(dataArray);
                chart.draw(data, options);

                container = document.getElementById('chart_cost_department');
                chart = new google.visualization.PieChart(container);
                dataArray = Object.entries(res.fleet_department_cost).map(([key, value]) => [
                    key + ': ' + value + '$', value
                ]);
                options.title = 'Vehicle Cost by Department';
                dataArray.unshift([options.title, 'Count']);
                data = google.visualization.arrayToDataTable(dataArray);
                chart.draw(data, options);
            }else if(activated_tab == 'nav-servicechart'){
                var container = document.getElementById('chart_service_status');
                var chart = new google.visualization.PieChart(container);
                dataArray = Object.entries(res.service_data).map(([key, value]) => [
                    key + ': ' + value, value
                ]);
                options.title = 'Service Status';
                dataArray.unshift([options.title, 'Count']);
                var data = google.visualization.arrayToDataTable(dataArray);
                chart.draw(data, options);

                container = document.getElementById('chart_service_cost');
                chart = new google.visualization.PieChart(container);
                dataArray = Object.entries(res.service_cost).map(([key, value]) => [
                    key + ': ' + value, value
                ]);
                options.title = 'Service Status';
                dataArray.unshift([options.title, 'Count']);
                data = google.visualization.arrayToDataTable(dataArray);
                chart.draw(data, options);
            }else if(activated_tab == 'nav-toolchart'){
                var container = document.getElementById('chart_tool_status');
                var chart = new google.visualization.PieChart(container);
                dataArray = Object.entries(res.tool_status_data).map(([key, value]) => [
                    key + ': ' + value, value
                ]);
                options.title = 'Inventory Chart';
                dataArray.unshift([options.title, 'Count']);
                var data = google.visualization.arrayToDataTable(dataArray);
                chart.draw(data, options);

                container = document.getElementById('chart_tool_department');
                chart = new google.visualization.PieChart(container);
                dataArray = Object.entries(res.tool_department_data).map(([key, value]) => [
                    key + ': ' + value, value
                ]);
                options.title = 'Inventory Departments';
                dataArray.unshift([options.title, 'Count']);
                data = google.visualization.arrayToDataTable(dataArray);
                chart.draw(data, options);
            }
        },
        error: function () {//xhr, status, error
            //
        },
    });
}

function setTab(tab){
    activated_tab = tab;
    localStorage.setItem("report_tab", tab);
    if(isLoaded) initFleetChart();
}

function loadTab(){
    var tab = localStorage.getItem("report_tab");
    if(tab!=null && tab!=""){
        document.querySelector('#'+tab+"-tab").click();
        window.scrollTo(0, document.body.scrollHeight);
    }
}