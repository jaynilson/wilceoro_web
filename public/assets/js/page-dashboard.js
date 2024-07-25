var lineChartType = 0;
var lineChartOptions = {
  vAxis: {
    format: '#'
  },
  titleTextStyle: {
    fontSize: 20,
    bold: true,
  },
  height: 385,
  chartArea: {
    width: '75%',
    height: '60%',
    left: 110,
    top: 97
  },
  title: 'Maintenance',
  curveType: '',//or 'function'
  colors: ["#FF0000","#D6D6D6"],
  // legend: {  position: 'top', alignment: 'end' },
  legend: 'none',
};
var lineChartData = [];
var overviewType = 0;
var paginationActivityLogs = {
  page: 1,
  pages: 0,
  size: 5,
  total: 0,
};
var tableVehicle = null;
var tableVehicleType = '';
var tableVehicleStatus = 'true';
var tableInventory = null;
var tableInventoryType = '';
var tableInventoryStatus = 'true';
var paginationWorkOrders = {
  page: 1,
  pages: 0,
  size: 10,
  total: 0,
};
$(window).resize(function(){
  // drawChart();
});

jQuery(document).ready(function() {
  google.charts.load('current', {'packages':['corechart']});
  // getLineChartData(0);
  google.charts.setOnLoadCallback(drawPieChart);
  showOverview(0);
  showActivityLogs();
  initVehiclesTable();
  initInventoriesTable();
  loadWorkOrders();
  $('#table_vehicles').on('click', 'td', function() {
    var currentTd = $(this);
    var currentTr = currentTd.closest('tr');
    // var columnIndex = currentTr.find('td').index(currentTd);
    location.href = `fleet_detail/${currentTr.prop('id')}`;
  });
  tableVehicle.on('draw', function () {
    var rows = tableVehicle.rows().nodes();
    $('td', rows).css('cursor', 'pointer');
  });
});

function initVehiclesTable() {
  tableVehicle = $('#table_vehicles').DataTable({
    lengthMenu: [[5, 10, 50, 100, -1], [5, 10, 50,100, -1]],
    dom: '<"top table-top-toolbar"ifrp>t',//<"bottom"lip>
    pageLength: 5,
    responsive: true,
    colReorder: true,
    searchDelay: 500,
    processing: true,
    serverSide: true,
    serverMethod: 'post',
    language: table_language,
    searching: false,
    // ordering: false,
    // "paging": false,
    // "info": false,
    ajax: {
      url:"fleet_dataTable",
      dataType: "json",
      type: "POST",
      data:{ _token:$('#token_ajax').val(), type:tableVehicleType }
    },
    columns: [
      {data: 'n'},
      {data: 'type'},
      {data: 'model'},
      {data: 'licence_plate'},
      {data: 'year'},
      {data: 'yard_location'},
      {data: 'department'},
      {data: 'status'},
    ],
    rowId: 'id',
    columnDefs: [
      {
        'targets': 1,
        'searchable': true,
        'orderable': true,
        'className': '',
        'render': function (data, type, full, meta){
          return `
            <input type="hidden" value="${full.id}" />
              ${data=='trucks_cars'?"Vehicle":(data=='trailers'?"Trailer":"Equipment")}
          `;
        }
      },{
        'targets': 7,
        'orderable': true,
        'className': 'dt-body-center text-center',
        'render': function (data, type, full, meta){
          if(data=='true'){
              return `
              <input type="hidden" value="${full.id}" />
              <div class="status-green p-1">Available</div>`;
          }else if(data=='in-service'){
              return `
              <input type="hidden" value="${full.id}" />
              <div class="status-yellow p-1">In Service</div>`;
          }else if(data=='check-out'){
              return `
              <input type="hidden" value="${full.id}" />
              <div class="status-gray p-1">In-Use</div>`;
          }else{//false
              return `
              <input type="hidden" value="${full.id}" />
              <div class="status-red p-1">Out of Service</div>`;
          }
        }
      }
    ],
    order: [[1, 'asc']],
    drawCallback: function() {
      $('#table_vehicles_wrapper .table-top-toolbar').css({
          'display': 'flex',
          'justify-content': 'space-between'
      });
    }
  });
};

function initInventoriesTable() {
  tableInventory = $('#table_inventories').DataTable({
    lengthMenu: [[5, 10, 50, 100, -1], [5, 10, 50,100, -1]],
    dom: '<"top table-top-toolbar"ifrp>t',//<"bottom"lip>
    pageLength: 5,
    responsive: true,
    colReorder: true,
    searchDelay: 500,
    processing: true,
    serverSide: true,
    serverMethod: 'post',
    language: table_language,
    searching: false,
    // ordering: false,
    // "paging": false,
    // "info": false,
    ajax: {
      url:"tool_dataTable?out_stock_type=false",
      dataType: "json",
      type: "POST",
      data:{ _token:$('#token_ajax').val() }
    },
    columns: [
      {data: 'n'},
      {data: 'department'},
      {data: 'title'},
      {data: 'price'},
      {data: 'available_stock'},//stock
      {data: 'type'},
      {data: 'required_return'},
      {data: 'status'},
    ],
    rowId: 'id',
    columnDefs: [
      {
        targets: 4,
        orderable: false,
        render: function(data, type, full, meta) {
          return full.available_stock<=0?`<font style="color:red;">${full.available_stock}</font>`:full.available_stock;
        },
      },{
        'targets': 6,
        'orderable': true,
        'className': 'dt-body-center text-center',
        'render': function (data, type, full, meta){
            if(data){
                return `<div class="p-1">Yes</div>`;
            }else{
                return `<div class="p-1">No</div>`;
            }
        }
      },{
        'targets': 7,
        'orderable': true,
        'className': 'dt-body-center text-center',
        'render': function (data, type, full, meta){
            if(data=='false'){
                return `<div class="status-red p-1">Disable</div>`;
            }else if(data=='true'){                            
                return full.available_stock>0?`<div class="status-green p-1">Enable</div>`:`<div class="status-red p-1">Stock Of Out</div>`;
            }
        }
      },
    ],
    order: [[1, 'asc']],
    drawCallback: function() {
      $('#table_inventories_wrapper .table-top-toolbar').css({
          'display': 'flex',
          'justify-content': 'space-between'
      });
    }
  });
};

function showVehicles(status){
  tableVehicleStatus = status;
  $('.vehicle-table-title').html(
    status=='true'?`Available Vehicles`:(
      status=='in-service'?`Vehicles In Service`:(
        status=='check-out'?`In-Use Vehicles`:(
          status=='false'?`Out of Service`:``
        )
      )
    )
  );
  var url = `/fleet_dataTable?status=`+tableVehicleStatus;
  tableVehicle.ajax.url(url).load();
}

function showInventories(status){
  tableInventoryStatus = status;
  $('.inventory-table-title').html(
    status==0?`Available Inventories`:`Stack Of Out Inventories`
  );
  var url = `/tool_dataTable?out_stock_type=`+(status==1);
  tableInventory.ajax.url(url).load();
}

function getLineChartData(type){
  var url = '';
  lineChartType = type;
  if(lineChartType==0){
    url = '/dashboard/getOperatedVehiclesCount';
    lineChartOptions.title = 'Operated Vehicles';
  }else if(lineChartType==1){
    url = '/dashboard/getServicesCount';
    lineChartOptions.title = 'Services';
  }else if(lineChartType==2){
    url = '/dashboard/getInventoriesInUseCount';
    lineChartOptions.title = 'Inventories in use';
  }
  showOverlay();
  $.ajax({
    url: url,
    type: 'POST',
    data: {
      _token: $('#token_ajax').val()
    },
    success: function (res) {
      hideOverlay();
      lineChartData = res.data;
      google.charts.setOnLoadCallback(drawChart);
    },
    error: function () {//xhr, status, error
      hideOverlay();
    },
  });
}

function drawChart(){
  var data = google.visualization.arrayToDataTable(lineChartData);
  var container = document.getElementById('chart');
  var chart = new google.visualization.LineChart(container);
  var observer = new MutationObserver(setBorderRadius);
  google.visualization.events.addListener(chart, 'ready', function () {
    setBorderRadius();
    // $('.chart-girl').fadeIn();
    observer.observe(container, {
      childList: true,
      subtree: true
    });
    $("text:contains(" + lineChartOptions.title + ")").attr({'x':'75', 'y':'57', 'fill': '#433c50', 'font-size': '1.25rem'});
  });
  chart.draw(data, lineChartOptions);

  function setBorderRadius() {
    Array.prototype.forEach.call(container.getElementsByTagName('rect'), function (rect) {
      if (rect.getAttribute('x') == 0) {
      rect.setAttribute('rx', 14);
      rect.setAttribute('ry', 14);
      }
    });
  }
}

function showOverview(type){
  overviewType = type;
  refreshOverview();
}

function refreshOverview(){
  var today = new Date();
  var year = today.getFullYear();
  var month = today.getMonth() + 1;
  var startDate = new Date(year, month - 1, 1);
  var endDate = new Date(year, month, 1);
  if(overviewType==1){
    startDate = new Date(year, 1, 1);
    endDate = new Date(year, month, 1);
  }
  var _from = startDate.toISOString();
  var _to = endDate.toISOString();
  showOverlay();
  $.ajax({
    url: "/dashboard/getOverviewData",
    type: 'POST',
    data: {
      from: _from,
      to: _to,
      _token: $('#token_ajax').val()
    },
    success: function (res) {
      hideOverlay();
      $("#service_count").html(res.services);
      $("#rental_count").html(res.rentals);
      $("#accident_count").html(res.accidents);
      $("#user_count").html(res.users);
    },
    error: function () {//xhr, status, error
        //
    },
  });
}

function drawPieChart(){
  var today = new Date();
  var year = today.getFullYear();
  var month = today.getMonth() + 1;
  var startDate = new Date(year, month - 1, 1);
  var endDate = new Date(year, month, 1);
  var _from = startDate.toISOString();
  var _to = endDate.toISOString();

  $.ajax({
    url: "/reports/getPieChartData",
    type: 'POST',
    data: {
      from: _from,
      to: _to,
      _token: $('#token_ajax').val()
    },
    success: function (res) {
      var container = document.getElementById('chart_fleet_status');
      var chart = new google.visualization.PieChart(container);
      var dataArray = Object.entries(res.fleet_status_data).map(([key, value]) => [
        (key=='true'?'Available':(key=='in-service'?'In Service':(key=='check-out'?'In-Use':'Out of Service')))+': '+value,
         value
      ]);
      dataArray.unshift(['Vehicle Status', 'Count']);
      var data = google.visualization.arrayToDataTable(dataArray);
      var options = {
        // legend: 'none',
        // pieSliceText: 'label',
        title: 'Vehicle Status',
        titleTextStyle: {},
        legend: { position: 'right' },
        // pieStartAngle: 100,
        is3D: true,
        backgroundColor: 'transparent',
        chartArea:{
          left:10, top:15, width:'100%', height:'100%'
        }
      };
      chart.draw(data, options);

      container = document.getElementById('chart_fleet_department');
      chart = new google.visualization.PieChart(container);
      dataArray = Object.entries(res.fleet_department_data).map(([key, value]) => [
        key + ': ' + value, value
      ]);
      dataArray.unshift(['Vehicle Status', 'Count']);
      data = google.visualization.arrayToDataTable(dataArray);
      options = {
        is3D: true,
        title: 'Department',
        backgroundColor: 'transparent',
        chartArea:{
          left:30, top:15, width:'100%', height:'100%'
        }
      };
      chart.draw(data, options);

      container = document.getElementById('chart_tool_status');
      chart = new google.visualization.PieChart(container);
      dataArray = Object.entries(res.tool_status_data).map(([key, value]) => [
        key + ': ' + value, value
      ]);
      dataArray.unshift(['Vehicle Status', 'Count']);
      data = google.visualization.arrayToDataTable(dataArray);
      options = {
        is3D: true,
        title: 'Inventory',
        backgroundColor: 'transparent',
        chartArea:{
          left:30, top:15, width:'100%', height:'100%'
        }
      };
      chart.draw(data, options);

      container = document.getElementById('chart_tool_department');
      chart = new google.visualization.PieChart(container);
      dataArray = Object.entries(res.tool_department_data).map(([key, value]) => [
        key + ': ' + value, value
      ]);
      dataArray.unshift(['Inventory Department', 'Count']);
      data = google.visualization.arrayToDataTable(dataArray);
      options = {
        is3D: true,
        title: 'Department',
        backgroundColor: 'transparent',
        chartArea:{
          left:30, top:15, width:'100%', height:'100%'
        }
      };
      chart.draw(data, options);
    },
    error: function () {//xhr, status, error
        //
    },
  });
}

function showActivityLogs(){
  showOverlay();
  $('#more_activity_log').fadeOut();
  if(paginationActivityLogs.page==1){
    $('#ul_activity_log').html('');
  }
  $.ajax({
    url: "/dashboard/getActivityLogsData",
    type: 'POST',
    data: {
      draw: paginationActivityLogs.page,
      search: {
        value: '',
        regex: false
      },
      columns: [
        {
          data: 'id',
          name: '',
          searchable: false,
          orderable: false,
          search: {
            value: '',
            regex: false
          }
        },
        {
          data: 'type',
          name: '',
          searchable: true,
          orderable: true,
          search: {
            value: '',
            regex: false
          }
        },
        {
          data: 'title',
          name: '',
          searchable: true,
          orderable: true,
          search: {
            value: '',
            regex: false
          }
        },
        {
          data: 'desc',
          name: '',
          searchable: true,
          orderable: true,
          search: {
            value: '',
            regex: false
          }
        },
        {
          data: 'href',
          name: '',
          searchable: true,
          orderable: true,
          search: {
            value: '',
            regex: false
          }
        },
        {
          data: 'created_at',
          name: '',
          searchable: true,
          orderable: true,
          search: {
            value: '',
            regex: false
          }
        },
      ],
      order: [
        {
          column: 5,
          dir: 'desc'
        }
      ],
      start: (paginationActivityLogs.page - 1) * paginationActivityLogs.size,
      length: paginationActivityLogs.size,
      _token: $('#token_ajax').val()
    },
    success: function (res) {
      hideOverlay();
      paginationActivityLogs.total = res.iTotalRecords;
      paginationActivityLogs.pages = Math.ceil(paginationActivityLogs.total/paginationActivityLogs.size);
      if(paginationActivityLogs.page==paginationActivityLogs.pages || paginationActivityLogs.total==0){
        $('#more_activity_log').fadeOut();
      }else{
        $('#more_activity_log').fadeIn();
      }
      res.aaData.forEach(element => {
        var color = element.type%3==0? 'primary': (element.type%3==1?'success':'info');
        if(element.type_sql==2) color = 'danger';
        var html = `<li class="timeline-item timeline-item-transparent">
                      <span class="timeline-point timeline-point-${color}"></span>
                      <div class="timeline-event">
                        <div class="timeline-header mb-3">
                          <a href="${element.href}"><h6 class="mb-0">${element.title}</h6></a>
                          <small class="text-muted">${frendlyPastDate(element._created_at)}</small>
                        </div>
                        <p class="mb-2">
                          ${element.desc}
                        </p>`;
        if (element.file_name !== null && element.file_name !== '') {
          var fileExtension = element.file_name.split('.').pop().toLowerCase();
          if (['png', 'jpg', 'jpeg', 'bmp'].includes(fileExtension)) {
            html += `<img src="${element.file_url}" style="max-width:50px;" alt="${element.file_name}" />`;
          } else {
            html += `<a href="${element.file_url}" target="_blank">${element.file_name}</a>`;
          }
        }
        html += `</div>
            </li>`;
        $('#ul_activity_log').append(html);
      });
    },
    error: function () {//xhr, status, error
        //
    },
  });
}

function showMoreActivityLogs(){
  paginationActivityLogs.page ++;
  showActivityLogs();
}

function loadWorkOrders(){
  showOverlay();
  $.ajax({
    url: "services_dataTable?status_type=working",
    type: 'POST',
    data: {
      draw: paginationWorkOrders.page,
      search: {
        value: '',
        regex: false
      },
      columns: [
        {
          data: 'id',
          name: '',
          searchable: false,
          orderable: false,
          search: {
            value: '',
            regex: false
          }
        },
      ],
      order: [
        {
          column: 0,
          dir: 'asc'
        }
      ],
      start: (paginationWorkOrders.page - 1) * paginationWorkOrders.size,
      length: paginationWorkOrders.size,
      _token: $('#token_ajax').val()
    },
    success: function (res) {
      hideOverlay();
      paginationWorkOrders.total = res.iTotalRecords;
      paginationWorkOrders.pages = Math.ceil(paginationWorkOrders.total/paginationWorkOrders.size);
      if(paginationWorkOrders.page==paginationWorkOrders.pages || paginationWorkOrders.total==0){
        $('#more_work_orders').fadeOut();
      }else{
        $('#more_work_orders').fadeIn();
      }
      $('.work-orders-count').html(paginationWorkOrders.total);
      res.aaData.forEach(element => {
        var color = 'success';
        if(element.status=='Unassigned') color = 'warning';//'secondary';
        else if(element.status=='In progress') color = 'danger';
        else if(element.status=='Scheduled') color = 'success';
        var initials = element.driver_name.split(' ')
          .map(function(word) {
            return word.charAt(0);
          })
          .join('')
          .toUpperCase();
        var avatar = initials.substr(0, Math.min(2, initials.length));
        var truncatedDescription = element.description.length > 40 ? element.description.slice(0, 40) + '...' : element.description;
        var html = `<div class="d-flex flex-wrap justify-content-between align-items-center py-2 work-order-item-wrapper"
                      onclick="location.href='/fleet_manager_service/${element.id}';"
                    >
                      <div class="d-flex align-items-center" style="max-width: calc(100% - 70px);">
                        <div class="avatar mr-3">
                          <div class="avatar-initial bg-label-${color} rounded-circle">`+
                            (element.driver_avatar==null?avatar:`<img alt="driver avatar" src="/storage/images/profiles/${element.driver_avatar}"/>`)
                          +`</div>
                        </div>
                        <div>
                          <div class="d-flex align-items-center gap-1">
                            <h6 class="mb-0" style="position:relative;">N°: ${element.full_fleet.n} ${element.full_fleet.model} ${element.records.length>0?`<small class="record-mark">${element.records.length}</small>`:``}</h6>
                            
                          </div>
                          <small>${truncatedDescription}</small>
                        </div>
                      </div>
                      <div class="text-end">
                        <div class="text-muted"><small>${frendlyPastDate(element.created_at)}</small></div>
                        <small class="text-${color}">${element.status}</small>
                      </div>
                    </div>`;
        $('.work-orders-wrapper').append(html);
      });
    },
    error: function () {//xhr, status, error
        //
    },
  });
}

function showMoreWorkOrders(){
  paginationWorkOrders.page ++;
  loadWorkOrders();
}