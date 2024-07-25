"use strict";
var table=null;
var KTDatatablesDataSourceAjaxServer = function() {
	var initTable1 = function() {
		table = $('#kt_table_1').DataTable({
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
                url:"fleet_dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val(), type:$('#type_fleet').val() }
            },
			columns: [
                {data: 'id'},
				{data: 'n',responsivePriority: 2},
                {data: 'model',responsivePriority: 3},
                {data: 'licence_plate',responsivePriority: 4},
                {data: 'year',responsivePriority: 5},
				{data: 'yard_location'},
                {data: 'department'},
                {data: 'status' ,responsivePriority: -2},
				{data: 'actions',  responsivePriority: -1}
			],
			columnDefs: [
                {
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){

                    return ` <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                        <input type="checkbox" name="id[]" value="`+$('<div/>').text(data).html()+`" >
                        <span></span>
                    </label>`;
                    }
                },{
                    'targets': 1,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){

                    return `
                    <input type="hidden" value="${full.id}" />
                    ${data}
                    `;
                    }
                },{
                    'targets': 2,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return `
                            <input type="hidden" value="${full.id}" />
                            ${data}
                        `;
                    }
                },{
                    'targets': 3,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return `
                            <input type="hidden" value="${full.id}" />
                            ${data}
                        `;
                    }
                },{
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
                },{
                    'targets': 5,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return `
                        <input type="hidden" value="${full.id}" />
                        ${full.current_yard_location==null||full.current_yard_location==''?full.yard_location:full.current_yard_location}
                        `;
                    }
                },{
                    'targets': 6,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return `
                            <input type="hidden" value="${full.id}" />
                            ${data}
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
                } ,{
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        var ___p = JSON.parse($('#___p').val());
                        var _w = checkP(___p, 3, 1);
                        var _d = checkP(___p, 3, 3);
                        return _w||_d?`
                            <span class="dropdown">
                                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                    <i class="la la-ellipsis-h"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    `+(_w?`<a class="dropdown-item" href="#" onclick='editElement(${JSON.stringify(data)})'><i class="flaticon-edit"></i> See or edit </a>`:``)+`
                                    `+(_d?`<a class="dropdown-item" href="#" onclick="deleteElement(`+data.id+`)"><i class="flaticon-delete"></i> Delete</a>`:``)+`
                                </div>
                            </span>
                        `:``;
                    },
                }
            ],
            order: [[0, 'asc']]
        });
    };

	return {
		//main function to initiate the module
		init: function() {
			initTable1();
		},
    };
}();

function editElement(data){
    $("#n_edit").val(data.n);
    $("#model_edit").val(data.model);
    $("#licence_plate_edit").val(data.licence_plate);
    $("#year_edit").val(data.year);
    $("#yard_location_edit").val(data.current_yard_locationyard_location==''||data.current_yard_location==null?data.yard_location:data.current_yard_location);
    $("#yard_location_edit").trigger('change');
    $("#yard_location_edit_hidden").val(data.yard_location);
    $("#department_edit").val(data.department);
    $("#department_edit").trigger('change');
    $("#modal_edit_element").modal("show");
    if(data.picture=="" || data.picture==null){
        $('#img-change-fleet-edit').attr('src',"/assets/images/upload_picture.png");
    }else{
        $('#img-change-fleet-edit').attr('src',"/storage/images/fleet/"+data.picture);
    }
    $("#id-edit").val(data.id);
    $("#modal_edit_element input[name=status][value=" + data.status + "]").prop('checked', true);
}

jQuery(document).ready(function() {
    KTDatatablesDataSourceAjaxServer.init();
    // Handle click on "Select all" control
    $('#select-all').on('click', function(){
        // Get all rows with search applied
        var rows = table.rows({ 'search': 'applied' }).nodes();
        // Check/uncheck checkboxes for all rows in the table
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    table.on( 'draw', function () {
        if($('#select-all').is(":checked")){
            var rows = table.rows({ 'search': 'applied' }).nodes();
            // Check/uncheck checkboxes for all rows in the table
            $('input[type="checkbox"]', rows).prop('checked', true);
        }
    });

    $('#filter_location').on('change', function(){
        var url = `/fleet_dataTable?location=`+$('#filter_location').val()+`&department=`+$('#filter_department').val()+`&status=`+$('#filter_status').val();
        table.ajax.url(url).load();
    });
    
    $('#filter_department').on('change', function(){
        var url = `/fleet_dataTable?location=`+$('#filter_location').val()+`&department=`+$('#filter_department').val()+`&status=`+$('#filter_status').val();
        table.ajax.url(url).load();
    });

    $('#filter_status').on('change', function(){
        var url = `/fleet_dataTable?location=`+$('#filter_location').val()+`&department=`+$('#filter_department').val()+`&status=`+$('#filter_status').val();
        table.ajax.url(url).load();
    });

    $('.select2.form-control').select2({
        dropdownAutoWidth: true,
        width: '100%',
        tags: true
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
                });
                $('#department').append(op);
                $("#department_edit").append(op.clone());
            });
            $('#department').select2({
                dropdownAutoWidth: true,
                width: '100%',
                tags: true,
                dropdownParent: $('#department').parent()
            });
            $('#department_edit').select2({
                dropdownAutoWidth: true,
                width: '100%',
                tags: true,
                dropdownParent: $('#department_edit').parent()
            });
            $('.select2.select2-container .select2-selection__arrow').html('<i class="kt-menu__ver-arrow la la-angle-down"></i>');
        },
        error: function () {//xhr, status, error
            //
        },
    });

    $.ajax({
        url: "/api/getLocationList",
        type: 'POST',
        data: {
            _token: $('#token_ajax').val()
        },
        success: function (res) {
            res.forEach(location => {
                var op = $("<option>", {
                    value: location,
                    text: location,
                });
                $('#yard_location').append(op);
                $('#yard_location_edit').append(op.clone());
            });
            $('#yard_location').select2({
                dropdownAutoWidth: true,
                width: '100%',
                tags: true,
                dropdownParent: $('#yard_location').parent()
            });
            $('#yard_location_edit').select2({
                dropdownAutoWidth: true,
                width: '100%',
                tags: true,
                dropdownParent: $('#yard_location_edit').parent()
            });
            $('.select2.select2-container .select2-selection__arrow').html('<i class="kt-menu__ver-arrow la la-angle-down"></i>');
        },
        error: function () {//xhr, status, error
            //
        },
    });
});

function showInfoCellInModal(title,content){
    $('#modal-info-cell-title').text(((title)? title  : ''));
    $('#modal-info-cell-content').text(((content)? content  : ''));
    $('#modal-info-cell').modal('show');
}

function deleteSelected(){
    var form = '#form_delete';
    $('#container-ids-delete').html('');
    // Iterate over all checkboxes in the table
    table.$('input[type="checkbox"]').each(function(){
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

function readURL2(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#img-change-profile').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#img-change").change(function(){
    readURL2(this);
});

function readURLEdit(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#img-change-fleet-edit').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#img-change-edit").change(function(){
    readURLEdit(this);
});

function showToast(type,msg,time=1500){
    var types=['success','info','warning','error'];
    toastr.options = {
    closeButton: true,
    debug: false,
    newestOnTop:true,
    progressBar: true,
    positionClass: 'toast-top-right',
    preventDuplicates: false,
    onclick: null,
    timeOut: time
    };
    var $toast = toastr[types[type]](msg, ''); // Wire up an event handler to a button in the toast, if it exists
    var $toastlast = $toast;
    if(typeof $toast === 'undefined'){
        return;
    }
}

$('#kt_table_1').on('click', 'tbody td', function() {
    if(!checkP(JSON.parse($('#___p').val()), 4)) return;
    const hiddenVal = $(this).find('input:hidden').val();
    if(hiddenVal!="" && hiddenVal!=null){
        window.open("/fleet_detail/"+hiddenVal,"_self");
    }
});