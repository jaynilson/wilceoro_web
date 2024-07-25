"use strict";
var table=null;
var table2=null;
var table3=null;
var table4=null;//reminders
var table5=null;//fleet record
var table6=null;//fleet tool
var table7=null;//select tool
var table8=null;//select fleet manager
var table9=null;//select fleet driver
var table10=null;//fleet custom rows
var table11=null;//accident
var table12=null;//incident
var filesAdd=[];
var tmpFullData=[];
var custom_fields = null;
var KTDatatablesDataSourceAjaxServer = function() {
	var initTable1 = function() {
		// begin first table
		table = $('#table_driver_history').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true,
           /* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: table_language,
            ajax: {
                url:"/driver_history_dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val(),id: $("#id").val() }
            },
			columns: [
                {data: 'driver_name'},
				{data: 'yard_out'},
                {data: 'out_date'},
                {data: 'out_miles'},
                {data: 'yard_in'},
                {data: 'in_date'},
                {data: 'in_miles'},
                {data: 'elapsed_time'},
			],
            columnDefs: [
                {
                    'targets': 2,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                    return formatDateOther(data);
                    }
                },
                {
                    'targets': 5,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                    return formatDateOther(data);
                    }
                }
            ],
            order: [[0, 'desc']]            
        });
	};
	var initTable2 = function() {
		// begin first table
		table2 = $('#table_maintenance').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true,
           /* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: table_language,
            ajax: {
                url:"/maintenance_dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val(),id: $("#id").val() }
            },
			columns: [
              
				{data: 'date'},
                {data: 'miles'},
                {data: 'type'},
                {data: 'next_service_date'},
                {data: 'next_service_miles'},
                {data: 'notes'},
			],
            order: [[0, 'desc']]
            
        });
        
        
	};
	var initTable3 = function() {
		// begin first table
		table3 = $('#table_dot_report_checkout').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true,
           /* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: table_language,
            ajax: {
                url:"/dot_report_checkout_dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val(),id: $("#id").val() }
            },
			columns: [
              
				{data: 'date',responsivePriority: 2},
                {data: 'driver',responsivePriority: 3},
				{data: 'actions',  responsivePriority: -1}
			],
			columnDefs: [
				{
                    'className': 'dt-body-center text-center',
                    targets: -1,
                    title: 'View report',
					orderable: false,
					render: function(data, type, full, meta) {
						return `
                        <button type="button" onclick="showReport(${meta.row})" class="btn btn-primary" >View report</button>
                        `;
					},
				}
            ],
            order: [[0, 'desc']]
            
        });  
        
	};
    var initTable4 = function() {
		// begin first table
		table4 = $('#table_reminder').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true,
           /* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: table_language,
            ajax: {
                url:"/reminder/dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val(),fleet_id: $("#id").val() }
            },
			columns: [
                {data: 'type'},
                {data: 'task'},
                {data: 'description'},
                {data: 'common_interval'},
                {data: 'time_interval_unit'},
                {data: 'watchers'},
				{data: 'created_at'},
                {data: 'actions',  responsivePriority: -1}
			],
			columnDefs: [
                {
                    'targets': 0,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return data=='service'?"SERVICE":(
                                data=='renewal'?'INSURANCE RENEWAL':(
                                    data=='electric'?'DI-ELECTRIC TEST':(
                                        data=='dot'?'DOT INSPECTION':'CUSTOM'
                                    )
                                )
                            );
                    }
                },
                {
                    'targets': 3,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return full.time_interval_unit==0?(data):data;//common_interval
                    }
                },
                {
                    'targets': 4,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return data==0?'DATE':data==1?'TIME':'ODOMETER';
                    }
                },
                {
                    'targets': 5,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        var display = '';
                        full.users.forEach(element => {
                            display+=(display==''?'':', ')+element.email
                        });
                        return `
                            ${display}
                        `;
                    }
                },
                {
                    'targets': 6,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return formatDateOther(data);
                    }
                },
                {
                    'className': 'dt-body-center text-center',
                    targets: -1,
                    title: 'Actions',
					orderable: false,
					render: function(data, type, full, meta) {
                        var ___p = JSON.parse($('#___p').val());
                        var _w = checkP(___p, 17, 1);
                        var _d = checkP(___p, 17, 3);
						return _w||_d?`
                            <span class="dropdown">
                                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    `+(_w?`<a class="dropdown-item btn-open-reminder-modal" href="/reminder/edit/${full.id}/${full.id_fleet}" ><i class="flaticon-edit"></i> See or edit </a>`:``)+`
                                    `+(_d?`<a class="dropdown-item" href="#" onclick="deleteReminder(${data.id})"><i class="flaticon-delete"></i> Delete</a>`:``)+`
                                </div>
                            </span>
                        `:``;
					},
				}
            ],
            order: [[0, 'asc']]
        });
	};
    var initTable5 = function() {
		// begin first table
		table5 = $('#table_records').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true,
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: table_language,
            ajax: {
                url:"/fleet_record/dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val(),id_fleet: $("#id").val() }
            },
			columns: [
                {data: 'type_name',responsivePriority: 1},
                {data: 'date',responsivePriority: 2},
                {data: 'note',responsivePriority: 3},
                {data: 'files',responsivePriority: 4},
                {data: 'actions',  responsivePriority: -1}
			],
			columnDefs: [
                {
                    'targets': 1,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                        return `${formatDate(data)}`;
                    }
                },{
                    'targets': 3,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                        return data.length>0?`<a onclick='openFiles(${JSON.stringify(data)})' style="background-color:black; color:white;" class="btn btn-default">
                                    <i class="icon-2x flaticon-arrows" style="color:white;"></i>
                                </a>`:``;
                    }
                },{
                    'className': 'dt-body-center text-center',
                    targets: -1,
                    title: 'Actions',
					orderable: false,
					render: function(data, type, full, meta) {
                        var ___p = JSON.parse($('#___p').val());
                        var _w = checkP(___p, 5, 1);
                        var _d = checkP(___p, 5, 3);
						return _w||_d?`
                            <span class="dropdown">
                                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    `+(_w?`<a class="dropdown-item btn-open-record-modal" href="#" onclick='openRecordModal(${data.id}, ${JSON.stringify(data)})'><i class="flaticon-edit"></i> See or edit </a>`:``)+`
                                    `+(_d?`<a class="dropdown-item" href="#" onclick="deleteRecord(${data.id})"><i class="flaticon-delete"></i> Delete</a>`:``)+`
                                </div>
                            </span>
                        `:``;
					},
				}
            ],
            order: [[0, 'asc']]
        });
	};
    var initTable6 = function() {
		// begin first table
		table6 = $('#table_tools').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true,
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: table_language,
            ajax: {
                url:"/fleet_tool/dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val(),id_fleet: $("#id").val() }
            },
			columns: [
                {data: 'tool_name',responsivePriority: 1},
                {data: 'assign_date',responsivePriority: 2},
                {data: 'return_date',responsivePriority: 3},
                {data: 'note',responsivePriority: 4},
                {data: 'actions',  responsivePriority: -1}
			],
			columnDefs: [
                {
                    'targets': 1,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                    return formatDate(data);
                    }
                },
                {
                    'targets': 2,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                        return full.required_return?formatDate(data):'';
                    }
                },{
                    'className': 'dt-body-center text-center',
                    targets: -1,
                    title: 'Actions',
					orderable: false,
					render: function(data, type, full, meta) {
                        var ___p = JSON.parse($('#___p').val());
                        var _w = checkP(___p, 6, 1);
                        var _d = checkP(___p, 6, 3);
                        return _w||_d?`
                        <span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                `+(_w?`<a class="dropdown-item btn-open-tool-modal" href="#" onclick='openToolModal(${data.id}, ${JSON.stringify(data)})'><i class="flaticon-eye"></i> See or edit </a>`:``)+`
                                `+(_d?`<a class="dropdown-item" href="#" onclick="deleteTool(${data.id})"><i class="flaticon-delete"></i> Delete</a>`:``)+`
                            </div>
                        </span>
                        `:``;
					},
				}
            ],
            order: [[0, 'asc']]
        });
	};
    var initTable7 = function() {
		// begin first table
        var columns = [
            {data: 'n'},
            {data: 'title'},
            {data: 'available_stock'},
            {data: 'type'},
            {data: 'required_return'},
            {data: 'status'},
            {data: 'actions',  responsivePriority: -1}
        ];
        var columnDefs = [
            {
                'targets': 2,
                'orderable': false,
                'render': function(data, type, full, meta) {
                    return full.available_stock<=0?`<font style="color:red;">${full.available_stock}</font>`:full.available_stock;
                },
            },{
                'targets': 4,
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
                'targets': 5,
                'orderable': true,
                'className': 'dt-body-center text-center',
                'render': function (data, type, full, meta){
                    if(data=='false'){
                        return `<div class="status-red p-1">Disable</div>`;
                    }else if(data=='true'){                            
                        return full.available_stock>0?`<div class="status-green p-1">Enable</div>`:`<div class="status-red p-1">Stock Of Out</div>`;
                    }
                }
            },{
                'targets': -1,
                'title': 'Select',
                'orderable': false,
                'render': function(data, type, full, meta) {
                    var old_ids = $("#tool_id_tool").val()==undefined?[]:$("#tool_id_tool").val().split(',');
                    var isIdIncluded = old_ids.includes(data.id.toString());
                    return full.available_stock<=0&&!isIdIncluded?``:`<a href="#" data-select="`+isIdIncluded+`" id="btn_tools_select_`+data.id+`" onclick="setToolForm(`+data.id+`,'`+data.tool_name+`',`+data.required_return+`);" class="btn btn-`+(isIdIncluded?`default`:`brand`)+` btn-elevate btn-icon-sm p-1">`+(isIdIncluded?`Cancel`:`Select`)+`</a>`;
                },
            }
        ];
        var ___p = JSON.parse($('#___p').val());
        var _p = checkP(___p, 14);
        if(_p){
            columns.splice(2, 0, {data: 'price'});
            columnDefs[0].targets = 3;
            columnDefs[1].targets = 5;
            columnDefs[2].targets = 6;
        }
		table7 = $('#kt_table_tool_selected').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true, 
           /* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: table_language,
            ajax: {
                url:"/tool_dataTable/fleet_assign",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val()}
            },
			columns: columns,
			columnDefs: columnDefs,
            order: [[0, 'desc']]
        });
	};
    var initTable8 = function() {
		// begin first table
		table8 = $('#kt_table_manager_selected').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true, 
           /* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: table_language,
            ajax: {
                url:"/user/dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val(), id_rol:3 }
            },
			columns: [
				{data: 'full_name',responsivePriority: 1},
                {data: 'email',responsivePriority: 2},
                {data: 'status' ,responsivePriority: 3},
				{data: 'actions',  responsivePriority: -1},
			],
			columnDefs: [
                {
                    targets: 2,
                    title: 'Status',
					orderable: true,
					render: function(data, type, full, meta) {
                        if(data!='Activo'){
                            return `<div class="status-red p-1 text-center">Disable</div>`;
                           }else {
                            return `<div class="status-green p-1 text-center">Enable</div>`;
                           }
                           }
					},
				{
                    targets: -1,
                    title: 'Select',
					orderable: false,
					render: function(data, type, full, meta) {
                        return `<a href="#" onclick="setFleetManager(`+data.id+`,'`+data.full_name+`');" class="btn btn-brand btn-elevate btn-icon-sm p-1">Select</a>`;
					},
				}
            ],
            order: [[0, 'desc']]
        });
	};
    var initTable9 = function() {
		// begin first table
		table9 = $('#kt_table_driver_selected').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true, 
           /* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: table_language,
            ajax: {
                url:"/user/dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val(), id_rol:4 }
            },
			columns: [
				{data: 'full_name',responsivePriority: 1},
                {data: 'email',responsivePriority: 2},
                {data: 'status' ,responsivePriority: 3},
				{data: 'actions',  responsivePriority: -1},
			],
			columnDefs: [
                {
                    targets: 2,
                    title: 'Status',
					orderable: true,
					render: function(data, type, full, meta) {
                        if(data!='Activo'){
                            return `<div class="status-red p-1 text-center">Disable</div>`;
                           }else {
                            return `<div class="status-green p-1 text-center">Enable</div>`;
                           }
                           }
					},
				{
                    targets: -1,
                    title: 'Select',
					orderable: false,
					render: function(data, type, full, meta) {
                        return `<a href="#" onclick="setFleetDriver(`+data.id+`,'`+data.full_name+`');" class="btn btn-brand btn-elevate btn-icon-sm p-1">Select</a>`;
					},
				}
            ],
            order: [[0, 'desc']]
        });
	};
    var initTable10 = function() {
        custom_fields = JSON.parse($("#custom_field_names").val());
        if(custom_fields.length==0) return;
        var columns = [];
        var columnDefs = [];
        for (var i = 0; i < custom_fields.length; i++) {
            columns.push({ 'data': custom_fields[i].name });
            columnDefs.push({
                'targets': i,
                orderable: true,
                render: function(data, type, full, meta) {
                    var i = meta.col;
                    if(custom_fields[i].type=='boolean'){
                        return data==1?`Yes`:`No`;
                    }else if(custom_fields[i].type=='date'){
                        return formatDate(data);
                    }else{
                        return data;
                    }
                }
            });
        }
        columns.push({ 'data': 'actions', responsivePriority: -1 });
        columnDefs.push({
            targets: -1,
            title: 'Action',
            orderable: false,
            render: function(data) {
                var ___p = JSON.parse($('#___p').val());
                var _w = checkP(___p, 4, 1);
                var _d = checkP(___p, 4, 3);
                return _w||_d?`
                    <span class="dropdown">
                        <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                            <i class="la la-ellipsis-h"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            `+(_w?`<a class="dropdown-item btn-open-fleetcustom-modal" href="#" onclick='openEditFleetCustomModal(${data.id}, ${JSON.stringify(data)})'><i class="flaticon-edit"></i> See or edit </a>`:``)+`
                            `+(_d?`<a class="dropdown-item" href="#" onclick="deleteFleetCustom(${data.id})"><i class="flaticon-delete"></i> Delete</a>`:``)+`
                        </div>
                    </span>
                `:``;
            },
        });
		// begin first table
		table10 = $('#table_custom_rows').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true, 
           /* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: table_language,
            ajax: {
                url:"/fleet_custom/dataTable/"+$("#id").val(),
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val() }
            },
			columns: columns,
			columnDefs: columnDefs,
            order: [[0, 'desc']]
        });
	};
    var initTable11 = function() {
		// begin first table
		table11 = $('#table_accidents').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true,
           /* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: table_language,
            ajax: {
                url:"/accident/dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val(), fid: $("#id").val() }
            },
			columns: [
				{data: 'id'},
                {data: 'type'},
                {data: 'answer_type'},
				{data: 'question_text'},
                {data: 'position'},
                {data: 'content'},
                {data: 'created_at'},
			],
            columnDefs: [
                {
                    'targets': 6,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                        return `${formatDate(data)}`;
                    }
                }
            ],
            order: [[0, 'desc']]
        });
	};
    var initTable12 = function() {
		// begin first table
		table12 = $('#table_incidents').DataTable({
            lengthMenu: [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]],
            dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
            pageLength: 25,
            responsive: true,
            colReorder: true,
           /* scrollY: false,
			scrollX: true,*/
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: table_language,
            ajax: {
                url:"/service_request_dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val(), uid: $("#id").val() }
            },
			columns: [
				{data: 'id'},
                {data: 'type'},
                {data: 'employee_name'},
				{data: 'category_name'},
                {data: 'place'},
                {data: 'description'},
                {data: 'status'},
                {data: 'created_at'},
			],
            columnDefs: [
                {
                    'targets': 7,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                        return `${formatDate(data)}`;
                    }
                }
            ],
            order: [[0, 'desc']]
        });
	};
    
	return {
		//main function to initiate the module
		init: function() {
			initTable1();
            initTable2(); 
            initTable3();
            initTable4();
            initTable5();
            initTable6();
            initTable7();
            initTable8();
            initTable9();
            initTable10();
            initTable11();
            initTable12();
		},
    };
}();

function showReport(row){
    var data = table3.rows().data()[row];
    $("#modal_dot_report_detail").modal("show");
    var container= $("#table_body_dot_report_checkout");
    var tds="";

    for (let index = 0; index < data.checks.length; index++) {
        const element = data.checks[index].check;
        var statusImage=((data.checks[index].dot_report.status=="true")?"correcto_red.png" :"delete.webp");
        tds+= `
            <tr>
                <td>${element.title}</td>
                <td style="text-align:center;">  <img src="${routePublicStorageInterface}${element.picture}"  width="100px" alt=""></td>
                <td style="text-align:center;">  <img src="${routePublicImages}${statusImage}" width="50px" alt=""></td>
            </tr>
        `;
    }
    container.html(tds);
}

function setTab(tab){
    localStorage.setItem("tab", tab);
}

function loadTab(){
    var tab = localStorage.getItem("tab");
    if(tab!=null && tab!=""){
        document.querySelector('#'+tab+"-tab").click();
        window.scrollTo(0, document.body.scrollHeight);
    }
}

jQuery(document).ready(function() {
    custom_fields = JSON.parse($("#custom_field_names").val());
    KTDatatablesDataSourceAjaxServer.init();
    table4.on('draw', function () {
        var rows = table4.rows().nodes();
        $('td', rows).css('cursor', 'pointer');
    });
    table5.on('draw', function () {
        var rows = table5.rows().nodes();
        $('td', rows).css('cursor', 'pointer');
    });
    table6.on('draw', function () {
        var rows = table6.rows().nodes();
        $('td', rows).css('cursor', 'pointer');
    });
    table10.on('draw', function () {
        var rows = table10.rows().nodes();
        $('td', rows).css('cursor', 'pointer');
    });

    $('#table_reminder').on('click', 'td', function() {
        if(!checkP(JSON.parse($('#___p').val()), 17, 1)) return;
        var currentTd = $(this);
        var currentTr = currentTd.closest('tr');
        var columnIndex = currentTr.find('td').index(currentTd);
        if(columnIndex<7)
            currentTr.find('a.btn-open-reminder-modal')[0].click();
    });

    $('#table_records').on('click', 'td', function() {
        if(!checkP(JSON.parse($('#___p').val()), 5, 1)) return;
        var currentTd = $(this);
        var currentTr = currentTd.closest('tr');
        var columnIndex = currentTr.find('td').index(currentTd);
        if(columnIndex<3)
            currentTr.find('a.btn-open-record-modal')[0].click();
    });

    $('#table_tools').on('click', 'td', function() {
        if(!checkP(JSON.parse($('#___p').val()), 6, 1)) return;
        var currentTd = $(this);
        var currentTr = currentTd.closest('tr');
        var columnIndex = currentTr.find('td').index(currentTd);
        if(columnIndex<7)
            currentTr.find('a.btn-open-tool-modal')[0].click();
    });

    $('#table_custom_rows').on('click', 'td', function() {
        if(!checkP(JSON.parse($('#___p').val()), 4, 1)) return;
        var currentTd = $(this);
        var currentTr = currentTd.closest('tr');
        var columnIndex = currentTr.find('td').index(currentTd);
        if(columnIndex<custom_fields.length){
            if(columnIndex!=0||columnIndex==0&&custom_fields.length<11)
            currentTr.find('a.btn-open-fleetcustom-modal')[0].click();
        }
    });
    
    loadTab();

    $('.select2.form-control').select2({
        dropdownAutoWidth: true,
        width: '100%',
        tags: true
    });

    $('#_required_cdl').on('click', function(){
        $('#required_cdl').val($(this).prop('checked')==true?1:0);
    });

    $("#modal_fleet_custom input[type='checkbox']").on('click', function(){
        var escapedId = $.escapeSelector($(this).data('id'));
        $("#" + escapedId).val($(this).prop('checked') ? 1 : 0);
    });

    $.ajax({
        url: "/api/getLocationList",
        type: 'POST',
        data: {
            _token: $('#token_ajax').val()
        },
        success: function (res) {
            $('#current_yard_location').html('');
            $('#checkout_yard').html('');
            res.forEach(location => {
                var op = $("<option>", {
                    value: location,
                    text: location,
                    selected: ($('#default_current_yard_location').val() == location) ? true : false,
                });
                $('#current_yard_location').append(op);

                var op1 = $("<option>", {
                    value: location,
                    text: location,
                });
                $('#checkout_yard').append(op1);
            });
            $('#current_yard_location').select2({
                dropdownAutoWidth: true,
                width: '100%',
                tags: true
            });
            $('#checkout_yard').select2({
                dropdownAutoWidth: true,
                width: '100%',
                tags: true,
                dropdownParent: $('#checkout_yard').parent()
            });
            $('.select2.select2-container .select2-selection__arrow').html('<i class="kt-menu__ver-arrow la la-angle-down"></i>');
        },
        error: function () {//xhr, status, error
            //
        },
    });

    $.ajax({
        url: "/api/getDepartmentList",
        type: 'POST',
        data: {
            _token: $('#token_ajax').val()
        },
        success: function (res) {
            res.forEach(department => {
                var op = $("<option>", {
                    value: department,
                    text: department,
                    selected: ($('#default_department').val() == department) ? true : false,
                });
                $('#department').append(op);
            });
            $('#department').select2({
                dropdownAutoWidth: true,
                width: '100%',
                tags: true,
                dropdownParent: $('#department').parent()
            });
            $('.select2.select2-container .select2-selection__arrow').html('<i class="kt-menu__ver-arrow la la-angle-down"></i>');
        },
        error: function () {//xhr, status, error
            //
        },
    });

    $('#insurance_expiration_date').val(formatDate($('#insurance_expiration_date').val()));
    $('#registration_date').val(formatDate($('#registration_date').val()));
    $('#lease_rental_return_date').val(formatDate($('#lease_rental_return_date').val()));
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

    $('#prev_picture').on('change', function(){
        var input = this;
        if (input.files && input.files[0]){
            $('#picture_upload_submit').css('display','block');
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#img_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });

    $('#record_file_upload').on('change', function(){
        var input=this;
        if (input.files && input.files[0]) {
          filesAdd.push(input.files[0]);
          var container = $("#container-files-add");
          container.append(`<div class="file-service d-flex">
            <div class="file-service-name p-2">${input.files[0].name}</div>
            <div class="file-service-btn-delete d-flex align-items-center p-2">
                <i class="icon-2x text-dark-50 flaticon2-delete" onclick="deleteFileAdd(${filesAdd.length-1})"></i>
            </div>
            </div>`);
        }
    });

    $('#modal_record form').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: "/fleet_record/save",
            type: 'POST',
            data: {
                id: $('#record_id').val(),
                id_fleet: $("#id").val(),
                type: $('#record_type').val(),
                date: formatDecodeDate($('#record_date').val()),
                note: $('#record_note').val(),
                _token: $('#token_ajax').val()
            },
            success: function (res) {
                if(filesAdd.length){
                    addFiles(res.record.id);
                }else{
                    table5.ajax.reload(); 
                    hideOverlay();
                    $("#modal_record").modal("hide");
                }
            },
            error: function () {
                hideOverlay();
                $("#modal_record").modal("hide");
            },
        });
    });

    $("#modal_tool form").submit(function(e){
        e.preventDefault();
        var form = $(this);
        form.validate({
            rules: {
                id_tool: {
                    required: true
                },
                assign_date: {
                    required: true
                },
                return_date: {
                    required: function() {
                        return $('#tool_return_tool').val() > 0;
                    }
                },
            }
        });

        if (!form.valid()) {
            hideOverlay();
            return;
        }

        $.ajax({
            url: "/fleet_tool/save",
            type: 'POST',
            data: {
                id: $('#tool_id').val(),
                id_fleet: $("#id").val(),
                id_tool: $('#tool_id_tool').val(),
                assign_date: formatDecodeDate($('#tool_assign_date').val()),
                return_date: formatDecodeDate($('#tool_return_date').val()),
                note: $('#tool_note').val(),
                _token: $('#token_ajax').val()
            },
            success: function (res) {
                table6.ajax.reload(); 
                hideOverlay();
                $("#modal_tool").modal("hide");
            },
            error: function () {
                hideOverlay();
                $("#modal_tool").modal("hide");
            },
        });
    });

    $("#modal_checkout form").submit(function(e){
        e.preventDefault();
        var form = $(this);
        form.validate({
            rules: {
                id_driver: {
                    required: true
                },
                yard: {
                    required: true
                },
                date: {
                    required: true
                },
                odometer: {
                    required: true
                },
            }
        });

        if (!form.valid()) {
            hideOverlay();
            return;
        }

        var data = {
            place: $('#checkout_yard').val(),
            problem_found: false,
            id_employee: $('#checkout_id_driver').val(),
            id_fleet: $("#id").val(),
            lat: null,
            lng: null,
            manual_date: formatDecodeDate($("#checkout_date").val()),
            odometer_reading: $("#checkout_odometer").val(),
            _token: $('#token_ajax').val()
        };
        var url = "/check_out_employee_fleet";
        if($("#id_check_out").val()>0){
            url = "/check_in_employee_fleet";
            data.id_check_out = $("#id_check_out").val();
        }
        
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function (res) {
                location.reload();
                hideOverlay();
                $("#modal_checkout").modal("hide");
            },
            error: function () {
                location.reload();
                hideOverlay();
                $("#modal_checkout").modal("hide");
            },
        });
    });

    $("#modal_fleet_custom form").submit(function(e){
        e.preventDefault();
        var data = {
            custom_row_id: $('#custom_row_id').val(),
            _token: $('#token_ajax').val()
        };
        for (var i = 0; i < custom_fields.length; i++) {
            var val = $("input[id='edit_custom_"+custom_fields[i].name+"']").val();
            if(custom_fields[i].type=='date') val = formatDecodeDate(val);
            data[custom_fields[i].name] = val;
        }
        $.ajax({
            url: "/fleet_custom/save/"+$("#id").val(),
            type: 'POST',
            data: data,
            success: function (res) {
                table10.ajax.reload(); 
                hideOverlay();
                $("#modal_fleet_custom").modal("hide");
            },
            error: function () {
                hideOverlay();
                $("#modal_fleet_custom").modal("hide");
            },
        });
    });
});

function openRecordModal(id, data={
    type: 0,
    date: '',
    note: '',
    files: [],
}){
    $("#nav-record-tab").trigger('click');
    $("#modal_record .modal-title").html(id?'Edit':'Add');
    $('#record_id').val(id);
    $('#record_type').val(data.type);
    $('#record_date').val(formatDate(data.date));
    $('#record_note').val(data.note);
    $("#modal_record").modal("show");
    if(!id) filesAdd = [];
    $("#container-files-add").html("");
    var container=$("#container-files-add");
    for (let index = 0; index < data.files.length; index++) {
        container.append(`<div class="file-service d-flex " id="file-div-${data.files[index].id}">
            <a class="file-service-name p-2" href="/storage/files_fleet_records/${data.files[index].picture}" target="_blank">${data.files[index].picture}</a>
                <div class="file-service-btn-delete d-flex align-items-center p-2" onclick="deleteFileAjax(${data.files[index].id})">
            <i class="icon-2x text-dark-50 flaticon2-delete" ></i>
            </div>
        </div>`);
    }
}

function deleteFileAdd(i){
    filesAdd.splice(i, 1);
    $("#container-files-add").html("");
    var container = $("#container-files-add");
    for (let index = 0; index < filesAdd.length; index++) {
        container.append(`<div class="file-service d-flex">
            <div class="file-service-name p-2">${filesAdd[index].name}</div>
            <div class="file-service-btn-delete d-flex align-items-center p-2">
            <i class="icon-2x text-dark-50 flaticon2-delete" onclick="deleteFileAdd(${index})"></i>
            </div>
        </div>`);
    }
}

function deleteRecord(id){
    showOverlay();
    $.ajax({
        url: `/fleet_record/delete/${id}`,
        type: 'DELETE',
        data:{
            _token: $('#token_ajax').val()
        },
        success: function () {
            hideOverlay();
            table5.ajax.reload(); 
        },
        error: function (xhr, status, error) {
            console.log(JSON.stringify(xhr));
            hideOverlay();
        },
    });
}

function deleteFileAjax(id){
    showOverlay();
    $.ajax({
        url: `/asset/delete/${id}`,
        type: 'DELETE',
        data:{
            _token: $('#token_ajax').val()
        },
        success: function () {
            const elem = document.getElementById("file-div-"+id);
            elem.remove();
            hideOverlay();
            table5.ajax.reload(); 
        },
        error: function (xhr, status, error) {
            console.log(JSON.stringify(xhr));
            hideOverlay();
        },
    });
}

function openFiles(files){
    $("#container-modal-show-files").html("");
    var container =$("#container-modal-show-files");
    for (let index = 0; index < files.length; index++) {
        container.append(`<div class="file-service d-flex " id="file-div-${files[index].id}">
            <a class="file-service-name p-2" href="/storage/files_fleet_records/${files[index].picture}" target="_blank">${files[index].picture}</a>
            <div class="file-service-btn-delete p-2">
            </div>
        </div>`);
    }
    $("#modal_show_files").modal("show");
}

function addFiles(id){
    showOverlay();
    var myFormData = new FormData();
    for (let index = 0; index < filesAdd.length; index++) {
        myFormData.append('myFiles[]', filesAdd[index]);
    }
    myFormData.append('id_record', id);
    myFormData.append('_token', $('#token_ajax').val());
    $.ajax({
        url: "/fleet_record/saveFile",
        type: 'POST',
        processData: false,
        contentType: false,
        data:myFormData,
        success: function (res) {
            table5.ajax.reload(); 
            hideOverlay();
            $("#modal_record").modal("hide");
            filesAdd=[];
        },
        error: function (xhr, status, error) {
            console.log(JSON.stringify(xhr));
            hideOverlay();
        },
    });
}

function openToolModal(id, data={
    id_tool: '',
    tool_name: '',
    assign_date: null,
    required_return: 0,
    return_date: null,
    note: '',
}){
    $("#nav-tool-tab").trigger('click');
    $("#modal_tool .modal-title").html(id?'Edit':'Add');
    $('#tool_id').val(id);
    $('#tool_id_tool').val(data.id_tool);
    $('#text_tool').val(data.tool_name);
    $('#tool_assign_date').val(formatDate(data.assign_date));
    $('#tool_return_tool').val(data.required_return);
    if(data.required_return){
        $(".return-date-input").fadeIn();
        $('#tool_return_date').val(formatDate(data.return_date));
    }else{
        $(".return-date-input").fadeOut();
        $('#tool_return_date').val(null);
    }
    $('#tool_note').val(data.note);
    table7.ajax.reload();
    $("#modal_tool").modal("show");
}

function openNewFleetCustomModal(){
    $('#nav-specifications-tab').trigger('click');
    $("#modal_fleet_custom .modal-title").html('Add');
    $('#custom_row_id').val(0);
    $("#modal_fleet_custom input[type='text'], #modal_fleet_custom input[type='number']").val('');
    $("#modal_fleet_custom input[type='checkbox']").prop('checked', false);
    $("#modal_fleet_custom input.form-control.hidden-checkbox").prop('value', 0);
    $("#modal_fleet_custom").modal("show");
}

function openEditFleetCustomModal(id, data){
    $("#modal_fleet_custom .modal-title").html('Edit');
    $('#custom_row_id').val(id);
    for (var i = 0; i < custom_fields.length; i++) {
        $("#modal_fleet_custom input[name='"+custom_fields[i].name+"']").val(data[custom_fields[i].name]);
        if(custom_fields[i].type=='boolean'){
            $("#modal_fleet_custom input[data-id='edit_custom_"+custom_fields[i].name+"']").prop('checked',data[custom_fields[i].name]==1?true:false);
        }else if(custom_fields[i].type=='date'){
            $("#modal_fleet_custom input[name='"+custom_fields[i].name+"']").val(formatDate(data[custom_fields[i].name]));
        }
    }
    $("#modal_fleet_custom").modal("show");
}

function deleteFleetCustom(id){
    showOverlay();
    $.ajax({
        url: "/fleet_custom/delete/"+$("#id").val()+'/'+id,
        type: 'DELETE',
        data:{
            _token: $('#token_ajax').val()
        },
        success: function () {
            hideOverlay();
            table10.ajax.reload(); 
        },
        error: function (xhr, status, error) {
            console.log(JSON.stringify(xhr));
            hideOverlay();
        },
    });
}

function deleteTool(id){
    showOverlay();
    $.ajax({
        url: `/fleet_tool/delete/${id}`,
        type: 'DELETE',
        data:{
            _token: $('#token_ajax').val()
        },
        success: function () {
            hideOverlay();
            table6.ajax.reload(); 
        },
        error: function (xhr, status, error) {
            console.log(JSON.stringify(xhr));
            hideOverlay();
        },
    });
}

function showModalSelectTool(){
    $("#modal_tool").modal("hide");
    $('#modal_select_tool').modal('show');  
}

function setToolForm(id, full_name, required_return){
    // $("#modal_tool").modal("show");
    var is_select = $('#btn_tools_select_'+id).data('select');
    var old_ids = $("#tool_id_tool").val()==undefined||$("#tool_id_tool").val()==''?[]:$("#tool_id_tool").val().split(',');
    var old_names = $("#text_tool").val()==''?[]:$("#text_tool").val().split(', ');
    var requires = $("#tool_return_tool").val() == '' ? 0 : parseInt($("#tool_return_tool").val());
    $('#btn_tools_select_'+id).data('select', !is_select);
    $('#btn_tools_select_'+id).html(is_select?'Select':'Cancel');
    if(is_select){
        $('#btn_tools_select_'+id).addClass('btn-brand');
        $('#btn_tools_select_'+id).removeClass('btn-default');
        var index = old_ids.indexOf(id.toString());
        old_ids.splice(index, 1);
        old_names.splice(index, 1);
        if(required_return) requires--;
    }else{
        $('#btn_tools_select_'+id).addClass('btn-default');
        $('#btn_tools_select_'+id).removeClass('btn-brand');
        old_ids.push(id.toString());
        old_names.push(full_name.replace(',',' '));
        if(required_return) requires++;
    }
    if(requires){
        $(".return-date-input").fadeIn();
    }else{
        $(".return-date-input").fadeOut();
        $('#tool_return_date').val(null);
    }
    $("#tool_return_tool").val(requires);
    $("#tool_id_tool").val(old_ids.join(','));
    $("#text_tool").val(old_names.join(', '));
    // $('#modal_select_tool').modal('hide');
}

function setFleetManager(id, full_name){
    $("#id_manager").val(id);
    $("#text_manager").val(full_name);
    $('#modal_select_manager').modal('hide');
    $("#btn_delete_manager").fadeIn();
}

function deleteManager(){
    $("#id_manager").val(null);
    $("#text_manager").val('');
    $("#btn_delete_manager").fadeOut();
}

function openCheckoutModal(){
    $("#modal_checkout").modal("show");
    $("#nav-diver-history-tab").trigger('click');
}

function showModalSelectDriver(){
    $("#modal_select_driver").modal("show");
}

function setFleetDriver(id, full_name){
    $("#checkout_id_driver").val(id);
    $("#text_driver").val(full_name);
    $('#modal_select_driver').modal('hide');
}

function deleteReminder(id){
    showOverlay();
    $.ajax({
        url: `/reminder/delete/${id}`,
        type: 'DELETE',
        data:{
            _token: $('#token_ajax').val()
        },
        success: function () {
            hideOverlay();
            table4.ajax.reload(); 
        },
        error: function (xhr, status, error) {
            console.log(JSON.stringify(xhr));
            hideOverlay();
        },
    });
}