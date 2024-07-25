"use strict";
var table=null;
var table2=null;
var table3=null;
var typeSelect="add";
var KTDatatablesDataSourceAjaxServer = function() {
	var initTable1 = function() {
		table = $('#kt_table_fleet_selected').DataTable({
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
                url:"fleet_all_dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val() }
            },
			columns: [
				{data: 'n',responsivePriority: 2},
                {data: 'model',responsivePriority: 3},
                {data: 'licence_plate',responsivePriority: 4},
                {data: 'year',responsivePriority: 5},
				{data: 'yard_location'},
                {data: 'department'},
                {data: 'status' , responsivePriority: -2},
				{data: 'actions', responsivePriority: -1}
			],
			columnDefs: [
                {
                    'targets': 6,
                    'orderable': true,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                        if(data=='true'){
                            return `<div class="status-green p-1">Available</div>`;
                        }else if(data=='in-service'){
                            return `<div class="status-yellow p-1">In Service</div>`;
                        }else if(data=='check-out'){
                            return `<div class="status-gray p-1">Working</div>`;
                        }else{//false
                            return `<div class="status-red p-1">Out of Service</div>`;
                        }
                    }
                },{
                    targets: -1,
                    title: 'Select',
					orderable: false,
					render: function(data, type, full, meta) {
                        return `<a href="#" onclick="setVehicleForm(`+data.id+`,'`+data.n+`','`+data.model+`');" class="btn btn-brand btn-elevate btn-icon-sm p-1">Select</a>`;
					},
				}
            ],
            order: [[0, 'desc']]
        });
	};

	var initTable2 = function() {
		// begin first table
		table2 = $('#kt_table_employee_selected').DataTable({
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
                url:"user/dataTableEmployee",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val()}
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
                        return `<a href="#" onclick="setEmployeeForm(`+data.id+`,'`+data.full_name+`');" class="btn btn-brand btn-elevate btn-icon-sm p-1">Select</a>`;
					},
				}
            ],
            order: [[0, 'desc']]
        });
	};

	var initTable3 = function() {
		// begin first table
		table3 = $('#kt_table_services').DataTable({
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
                url:"services_dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val() }
            },
			columns: [
                {data: 'id'},
				{data: 'created_at'},
                {data: 'needed_date'},
                {data: 'completed_date'},
                {data: 'description'},
                {data: 'type',responsivePriority: 4},
                {data: 'vehicle_name',responsivePriority: 5},
				{data: 'driver_name'},
                {data: 'cost'},
                {data: 'status'},
				{data: 'actions',  responsivePriority: -1}
			],
            rowId: function(data) {
                return  data.uuid;
            }, 
			columnDefs: [
                {
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                        return ` <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                                <input type="checkbox" name="id[]" value="`+$('<div/>').text(data).html()+`"/>
                                <span></span>
                            </label>`;
                    }
                },
                {
                    'targets': 1,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return `
                            <input type="hidden" value="${full.id}" />
                            <div>${formatDate(data)}</div>
                        `;
                    }
                },
                {
                    'targets': 2,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return `
                            <input type="hidden" value="${full.id}" />
                            <div>${formatDate(data)}</div>
                        `;
                    }
                },
                {
                    'targets': 3,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return `
                            <input type="hidden" value="${full.id}" />
                            <div>${formatDate(data)}</div>
                        `;
                    }
                },
                {
                    'targets': 4,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return `
                            <input type="hidden" value="${full.id}" />
                            ${data}
                            `;
                        }
                },
                {
                    'targets': 5,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return `
                            <input type="hidden" value="${full.id}" />
                            <div>Service: ${full.type=='corrective'?'Corrective':full.type=='preventive'?'Preventive':''}</div>
                            <div>${full.working=='in-house'?'Work: In-House':full.working=='outsourced'?'Work: Outsourced':''}</div>
                            `;
                        }
                },
                {
                    'targets': 6,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                    return `
                        <input type="hidden" value="${full.id}" />
                        <div class="row">
                            <div class="col-4 d-flex align-items-center justify-content-center">
                                <img src="/storage/images/fleet/${full.full_fleet.picture}" width="100%">
                            </div>
                            <div class="col-8">
                                <span class="font-weight-bold">N°: ${full.full_fleet.n}</span>
                                <div>${full.full_fleet.model}</div>
                            </div>
                        </div>`;
                    }
                },
                {
                    'targets': 7,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return `
                            <input type="hidden" value="${full.id}" />
                            <div>${full.driver_name}</div>
                            <div>${full.working=='in-house'?'In-House':full.working=='outsourced'?'OutSourced':''}</div>
                        `;
                    }
                },
                {
                    'targets': 8,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        var ___p = JSON.parse($('#___p').val());
                        var _p = checkP(___p, 14);
                        return `
                            <input type="hidden" value="${full.id}" />
                            `+(_p?`<div>Cost: $${full.cost}</div>`:``)+`
                            <div>Hours: ${full.hours}</div>
                        `;
                    }
                },
                {
                    'targets': 9,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return `
                            <input type="hidden" value="${full.id}" />
                            <div>${full.status?full.status:'Unassigned'}</div>
                            <div>${full.records.length?full.records.length+' records':''}</div>
                        `;
                    }
                },
				{
                    targets: -1,
                    title: 'Actions',
					orderable: false,
					render: function(data, type, full, meta) {
                        var ___p = JSON.parse($('#___p').val());
                        var _w = checkP(___p, 11, 1);
                        var _d = checkP(___p, 11, 3);
                        var _v = checkP(___p, 4);
                        return _w||_d||_v?`
                            <span class="dropdown">
                                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                `+(_w?`<a class="dropdown-item" href="#" onclick='editElement(${JSON.stringify(data)})'><i class="flaticon-edit"></i> Edit Service </a>`:``)+`
                                `+(_v?`<a class="dropdown-item" href="/fleet_detail/${full.id_fleet}"><i class="flaticon-eye"></i> Open Fleet </a>`:``)+`
                                `+(_d?`<a class="dropdown-item" href="#" onclick="deleteElement(`+data.id+`)"><i class="flaticon-delete"></i> Delete</a>`:``)+`
                                </div>
                            </span>
                        `:``;
					},
				}
            ],
            order: [[0, 'desc']]
        });
	};
	return {
		init: function() {
			initTable1();
            initTable2();
            initTable3();
		},
    };
}();

function editElement(data){
    $('#modal_edit').modal('show');     
    $("#id-edit").val(data.id);
    $("#description-edit").val(data.description);
    $("#type-edit").val(data.type);
    $("#id-employee-edit").val(data.id_employee);
    $("#id-fleet-edit").val(data.id_fleet);
    $("#text-vehicle-edit").val(data.vehicle_name);
    $("#text-employee-edit").val(data.driver_name);
    $("#needed-date-edit").val(formatDate(data.needed_date));
    $("#completed-date-edit").val(formatDate(data.completed_date));
    $("#engine-hours-edit").val(data.engine_hours);
    $("#working-edit").val(data.working);
    $("#notes-edit").val(data.notes);
    $("#status-edit").val(data.status);
}

function setVehicleForm(id, n, model){
    if(typeSelect=="add"){
        $("#id-fleet").val(id);
        $("#text-vehicle").val(n+" - "+model);
        
        $('#modal_select_fleet').modal('hide');  
    }else{
        $("#id-fleet-edit").val(id);
        $("#text-vehicle-edit").val(n+" - "+model);
        
        $('#modal_select_fleet').modal('hide'); 
    }
}

function setEmployeeForm(id, full_name){
    if(typeSelect=="add"){
        $("#id-employee").val(id);
        $("#text-employee").val(full_name);
        $('#modal_select_employee').modal('hide');   
    }else{
        $("#id-employee-edit").val(id);
        $("#text-employee-edit").val(full_name);
        $('#modal_select_employee').modal('hide');   
    }
}

jQuery(document).ready(function() {
    KTDatatablesDataSourceAjaxServer.init();
    $('#select-all').on('click', function(){
        // Get all rows with search applied
        var rows = table3.rows({ 'search': 'applied' }).nodes();
        // Check/uncheck checkboxes for all rows in the table
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
        
        table3.on( 'draw', function () {
        if($('#select-all').is(":checked")){
        var rows = table3.rows({ 'search': 'applied' }).nodes();
        // Check/uncheck checkboxes for all rows in the table
        $('input[type="checkbox"]', rows).prop('checked', true);
        }
    });

    $('#kt_table_services').on('click', 'tbody td', function() {
        if(!checkP(JSON.parse($('#___p').val()), 12)) return;
        const hiddenVal = $(this).find('input:hidden').val();
        if(hiddenVal!="" && hiddenVal!=null){
            window.open("/fleet_manager_service/"+hiddenVal,"_self");
        }
    });

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

    $('#filter_status').on('change', function(){
        var url = `/services_dataTable?status=`+$('#filter_status').val();
        table3.ajax.url(url).load();
    });

    $("#modal_add form").submit(function(e){
        var form = $(this);
        form.validate({
            rules: {
                description: {
                    required: true
                },
                type: {
                    required: true
                },
                id_employee: {
                    required: true
                },
                id_fleet: {
                    required: true
                },
            }
        });
        if (!form.valid()) {
            e.preventDefault();
            hideOverlay();
            return;
        }
    });

    $("#modal_edit form").submit(function(e){
        var form = $(this);
        form.validate({
            rules: {
                description: {
                    required: true
                },
                type: {
                    required: true
                },
                id_employee: {
                    required: true
                },
                id_fleet: {
                    required: true
                },
            }
        });
        if (!form.valid()) {
            e.preventDefault();
            hideOverlay();
            return;
        }
    });
});

function openService(){
    window.open("/fleet_manager_service/12","_self")
}

function showModalSelectEmployee(type){
    $('#modal_select_employee').modal('show');  
    typeSelect=type;   
}
function showModalSelectFleet(type){
    $('#modal_select_fleet').modal('show');     
    typeSelect=type; 
}

function openAddModal(){
  $('#modal_add').modal('show');     
}

function deleteSelected(){
    var form = '#form_delete';
    $('#container-ids-delete').html('');
    // Iterate over all checkboxes in the table
    table3.$('input[type="checkbox"]').each(function(){
       // If checkbox doesn't exist in DOM
       //if(!$.contains(document, this)){
          // If checkbox is checked
          if(this.checked){
             // Create a hidden element
             $('#container-ids-delete').append(
                $('<input>')
                   .attr('type', 'hidden')
                   .attr('name', this.name)
                   .val(this.value)
             );
          }
       //}
    });

$('#modal_delete').modal('show');
}

function deleteElement(id){
    $('#id_delete').val(id);
    $('#modal_delete_element').modal('show');
}
