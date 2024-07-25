"use strict";
var table1 = null;
var table2 = null;
var table3 = null;
var filesAdd = [];
var filesDelete = [];
var KTDatatablesDataSourceAjaxServer = function() {
	var initTable1 = function() {
		table1 = $('#kt_table_1').DataTable({
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
                url:"/rental/dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val() }
            },
			columns: [
                {data: 'id'},
				{data: 'tool_name'},
                {data: 'rental_date'},
                {data: 'needed_date'},
                {data: 'return_date'},
                {data: 'vendor_name'},
                {data: 'employee_name'},
                {data: 'notify'},
                {data: 'note'},
                {data: 'files'},
                {data: 'status'},
				{data: 'actions',  responsivePriority: -1}
			],
			columnDefs: [
                {
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                    return `<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                        <input type="checkbox" name="id[]" value="`+$('<div/>').text(data).html()+`" >
                        <span></span>
                    </label>`;
                    }
                },{
                    'targets': 2,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                    return formatDate(data);
                    }
                },{
                    'targets': 3,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                    return formatDate(data);
                    }
                },{
                    'targets': 4,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                    return formatDate(data);
                    }
                },{
                    'targets': 9,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                    return data.length?`<a onclick='openFiles(${JSON.stringify(data)})' style="background-color:black; color:white;" class="btn btn-default">
                                <i class="icon-2x flaticon-arrows" style="color:white;"></i>
                        </a>`:``;
                    }
                },{
                    'targets': 10,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                        if (type === 'display') {
                            return data.replace(/(^|-)(\w)/g, function(match, p1, p2) {
                                return p1 + p2.toUpperCase();
                            });
                        }
                        return data;
                    }
                },{
                    targets: -1,
                    title: 'Actions',
					orderable: false,
					render: function(data, type, full, meta) {
						return `
                        <span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" onclick='showRentalModal(${JSON.stringify(data)})'><i class="flaticon-edit"></i> See or edit </a>
                                `+(data.status=='check-out'?`<a class="dropdown-item" href="#" onclick='checkInRental(${JSON.stringify(data)})'><i class="flaticon-rotate"></i> Check-In </a>`:``)+`
                                <a class="dropdown-item" href="#" onclick='deleteRental(${JSON.stringify(data)})'><i class="flaticon-delete"></i> Delete</a>
                            </div>
                        </span>
                        `;
					},
				}
            ],
            order: [[0, 'desc']]
        });
	};
    var initTable2 = function() {
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
		table2 = $('#kt_table_tool_selected').DataTable({
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
                url:"/tool_dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val()}
            },
			columns: columns,
			columnDefs: columnDefs,
            order: [[0, 'desc']]
        });
	};
    var initTable3 = function() {
		// begin first table
		table3 = $('#kt_table_employee_selected').DataTable({
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
                        return `<a href="#" onclick="setFleetEmployee(`+data.id+`,'`+data.full_name+`');" class="btn btn-brand btn-elevate btn-icon-sm p-1">Select</a>`;
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

jQuery(document).ready(function() {
    KTDatatablesDataSourceAjaxServer.init();
    $('#select-all').on('click', function(){
        var rows = table1.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    table1.on( 'draw', function () {
        if($('#select-all').is(":checked")){
            var rows = table1.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', true);
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

    $("#rental_form").submit(function(e){
        e.preventDefault();
        var form = $(this);
        form.validate({
            //ignore: ":hidden",
            rules: {
                id_tool: {
                    required: true
                },
                return_date: {
                    required: function() {
                        return $('#rental_return_tool').val() === "1";
                    }
                },
                id_employee: {
                    required: true
                },
                rental_date: {
                    required: true
                },
                needed_date: {
                    required: true
                },
            }
        });
        if (!form.valid()) {
            hideOverlay();
            return;
        }

        var myFormData = new FormData();
        if($('#rental_status').val()=='check-in'){
            for (let index = 0; index < filesAdd.length; index++) {
                myFormData.append('myFiles[]', filesAdd[index]);
            }
        }
        myFormData.append('id', $("#rental_id").val());
        myFormData.append('id_tool', $("#rental_id_tool").val());
        myFormData.append('return_date', formatDecodeDate($("#rental_return_date").val()));
        myFormData.append('id_employee', $("#rental_id_employee").val());
        myFormData.append('vendor_name', $("#rental_vendor_name").val());
        myFormData.append('rental_date', formatDecodeDate($("#rental_rental_date").val()));
        myFormData.append('needed_date', formatDecodeDate($("#rental_needed_date").val()));
        myFormData.append('note', $("#rental_note").val());
        myFormData.append('notify', $("#rental_notify").val());
        myFormData.append('status', $("#rental_status").val());
        myFormData.append('_token', $('#token_ajax').val());
        $.ajax({
            url: "/rental/save",
            type: 'POST',
            processData: false,
            contentType: false,
            data: myFormData,
            success: function (res) {
                if(filesDelete.length && $('#rental_status').val()=='check-in'){
                    for(let index = 0; index < filesDelete.length; index ++){
                        $.ajax({
                            url: `/asset/delete/${filesDelete[index]}`,
                            type: 'delete',
                            data: {
                                '_token': $('#token_ajax').val()
                            },
                            success: function (res) {
                                if(index==filesDelete.length - 1){
                                    table1.ajax.reload();
                                    hideOverlay();
                                    $("#modal_rental").modal("hide");
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log(JSON.stringify(xhr));
                                hideOverlay();
                            },
                        });
                    }
                }else{
                    table1.ajax.reload(); 
                    hideOverlay();
                    $("#modal_rental").modal("hide");
                }
            },
            error: function (xhr, status, error) {
                console.log(JSON.stringify(xhr));
                hideOverlay();
            },
        });
    });

    $('#rental_file_upload').on('change', function(){
        var input=this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var filePreview = e.target.result;
                filesAdd.push(input.files[0]);
                var container = $("#container-files-add");
                container.append(`
                    <div class="file-preview-wrapper mr-4" id="file-add-${filesAdd.length-1}">
                        <img src="${filePreview}"/>
                        <div class="file-btn-delete">
                            <i class="icon-2x text-dark-50 flaticon2-delete" onclick="deleteFileAdd(${filesAdd.length-1});"></i>
                        </div>
                    </div>
                `);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
});

function deleteFileAdd(i){
    $(`#file-add-${i}`).remove();
    filesAdd.splice(i, 1);
}

function deleteFileEdit(id){
    $(`#file-edit-${id}`).remove();
    filesDelete.push(id);
}

function showRentalModal(data={
    id: 0,
    id_tool: 0,
    tool_name: '',
    required_return: false,
    return_date: null,
    id_employee: 0,
    employee_name: '',
    vendor_name: '',
    rental_date: null,
    needed_date: null,
    note: '',
    notify: null,
    status: 'check-out',
    files: [],
}){
    filesAdd = [];
    filesDelete = [];
    $("#container-files-add").html('');
    $('#rental_file_upload').val(null);
    $('.inventory-select-group button.btn-input').fadeIn();
    $('#text_tool').css('border-top-left-radius', '0px');
    $('#text_tool').css('border-bottom-left-radius', '0px');
    $('.employee-select-group button.btn-input').fadeIn();
    $('#text_employee').css('border-top-left-radius', '0px');
    $('#text_employee').css('border-bottom-left-radius', '0px');
    $('#rental_vendor_name').prop('disabled', false);
    $('#rental_rental_date').prop('disabled', false);
    $('#rental_needed_date').prop('disabled', false);
    $('#rental_notify').prop('disabled', false);

    $('#modal_rental').modal('show');
    if(data.id==0){
        $('#modal_rental .modal-title').html('Add New Rental');
    }else{
        $('#modal_rental .modal-title').html('Edit Rental');
    }
    if(data.required_return){
        $('.return-date-group').fadeIn();
    }else{
        $('.return-date-group').fadeOut();
        data.return_date = null;
    }
    if(data.status=='check-out'){
        $('.returned-picture-group').fadeOut();
    }else{
        $('.returned-picture-group').fadeIn();
    }
    $('#rental_id').val(data.id);
    $('#rental_id_tool').val(data.id_tool);
    $('#text_tool').val(data.tool_name);
    $('#rental_return_tool').val(data.required_return);
    $('#rental_return_date').val(formatDate(data.return_date));
    $('#rental_id_employee').val(data.id_employee);
    $('#text_employee').val(data.employee_name);
    $('#rental_vendor_name').val(data.vendor_name);
    $('#rental_rental_date').val(formatDate(data.rental_date));
    $('#rental_needed_date').val(formatDate(data.needed_date));
    $('#rental_note').val(data.note);
    $('#rental_notify').val(data.notify);
    $('#rental_status').val(data.status);

    if(data.status=='check-in' && data.files.length){
        var container =$("#container-files-add");
        for (let index = 0; index < data.files.length; index++) {
            container.append(`
                <div class="file-preview-wrapper mr-4" id="file-edit-${data.files[index].id}">
                    <a href="/storage/files_rental_returned/${data.files[index].picture}" target="_blank">
                        <img src="/storage/files_rental_returned/${data.files[index].picture}"/>
                    </a>
                    <div class="file-btn-delete">
                        <i class="icon-2x text-dark-50 flaticon2-delete" onclick="deleteFileEdit(${data.files[index].id});"></i>
                    </div>
                </div>
            `);
        }
    }
}

function checkInRental(data){
    data.status = 'check-in';
    showRentalModal(data);
    $('.inventory-select-group button.btn-input').fadeOut();
    $('#text_tool').css('border-radius', '50px');
    $('.employee-select-group button.btn-input').fadeOut();
    $('#text_employee').css('border-radius', '50px');
    $('#rental_vendor_name').prop('disabled', true);
    $('#rental_rental_date').prop('disabled', true);
    $('#rental_needed_date').prop('disabled', true);
    $('#rental_notify').prop('disabled', true);
}

function showModalSelectTool(){
    $('#modal_rental').modal('hide');
    $('#modal_select_tool').modal('show');
}

function setToolForm(id, full_name, required_return){
    $("#modal_rental").modal("show");
    $("#rental_id_tool").val(id);
    $("#rental_return_tool").val(required_return);
    if(required_return){
        $(".return-date-group").fadeIn();
    }else{
        $(".return-date-group").fadeOut();
        $('#rental_return_date').val(null);
    }
    $("#text_tool").val(full_name);
    $('#modal_select_tool').modal('hide');
}

function showModalSelectEmployee(){
    $('#modal_rental').modal('hide');
    $('#modal_select_employee').modal('show');  
}

function setFleetEmployee(id, full_name){
    $("#modal_rental").modal("show");
    $("#rental_id_employee").val(id);
    $("#text_employee").val(full_name);
    $('#modal_select_employee').modal('hide');
}

function openFiles(files){
    $("#container-modal-show-files").html("");
    var container =$("#container-modal-show-files");
    for (let index = 0; index < files.length; index++) {
        container.append(`
            <div class="file-preview-wrapper mr-4" id="file-edit-${files[index].id}">
                <a href="/storage/files_rental_returned/${files[index].picture}" target="_blank">
                    <img src="/storage/files_rental_returned/${files[index].picture}"/>
                </a>
            </div>
        `);
    }
    $("#modal_show_files").modal("show");
}

function deleteSelected(){
    $('#container-ids-delete').html('');
    var is_deletable = false;    
    table1.$('input[type="checkbox"]').each(function(){
        if(this.checked){
            is_deletable = true;
            $('#container-ids-delete').append(
                $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', this.name)
                    .val(this.value)
                );
        }
    });
    if(is_deletable)$('#modal_delete').modal('show');
}

function deleteRental(data){
    $('#container-ids-delete').append(
        $('<input>')
            .attr('type', 'hidden')
            .attr('name', 'id[]')
            .val(data.id)
        );
    $('#modal_delete').modal('show');
}