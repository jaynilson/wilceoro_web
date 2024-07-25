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
                url:"user/dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val()}
            },
			columns: [
                {data: 'id'},
				{data: 'full_name',responsivePriority: 2},
                {data: 'email',responsivePriority: 3},
                {data: 'rol_name',responsivePriority: 4},
                {data: 'pin',responsivePriority: 5},
				{data: 'tel'},
                {data: 'status' ,responsivePriority: -2},
				{data: 'actions',  responsivePriority: -1},
			],
            rowId: 'id',
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
                    targets: -1,
                    title: 'Actions',
					orderable: false,
					render: function(data, type, full, meta) {
                        var ___p = JSON.parse($('#___p').val());
                        var _w = checkP(___p, 1, 1);
                        var _d = checkP(___p, 1, 3);
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
            order: [[0, 'desc']]
        });
	};
	return {
		init: function() {
			initTable1();
		},
    };
}();

jQuery(document).ready(function() {
    KTDatatablesDataSourceAjaxServer.init();
    $('#select-all').on('click', function(){
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });
    table.on( 'draw', function () {
        if($('#select-all').is(":checked")){
            var rows = table.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', true);
        }
    });
    table.on('click', 'td', function() {
        if(!checkP(JSON.parse($('#___p').val()), 2)) return;

        var currentTd = $(this);
        var currentTr = currentTd.closest('tr');
        var columnIndex = currentTr.find('td').index(currentTd);
        console.log(currentTr.prop('id'));
        if(columnIndex>0&&columnIndex<7)
            location.href = `/user/edit/${currentTr.prop('id')}`;//currentTr.find('a.btn-open-reminder-modal')[0]
    });
    $('#modal_add_employee .btn-form-submit').on('click', function(){
        //var form = $('#modal_add_employee form');
        //form.submit();
    });
    checkRol();

    $("#import_file_upload").on('change', function(){
        var filename = "";
        var input=this;
        if (input.files && input.files[0]) {
            filename = input.files[0].name;
        }
        $('.import-uploaded-filename').html(filename);
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
    table.$('input[type="checkbox"]').each(function(){
        if(this.checked){
            // Create a hidden element
            $('#container-ids-delete').append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name', this.name)
                .val(this.value)
            );
        }
    });
    $('#modal_delete').modal('show');
}

function deleteElement(id){
    $('#id_delete').val(id);
    $('#modal_delete_element').modal('show');
}

function editElement(data){
    $('#modal_edit_employee').modal('show');     
    $("#name-edit").val(data.name);
    $("#last-name-edit").val(data.last_name);
    $("#department-edit").val(data.department);
    $("#yard_location-edit").val(data.yard_location);
    $("#email-edit").val(data.email);
    $("#id_rol_edit").val(data.id_rol);
    $("#pin-edit").val(data.pin);
    $("#recipient-tel-edit").val(data.tel);
    $("#id-edit").val(data.id);
    if(data.picture=="" || data.picture==null){
        $('#img-change-profile-edit').attr('src',"/assets/images/user_default.png");
    }else{
        $('#img-change-profile-edit').attr('src',"/storage/images/profiles/"+data.picture);
    }
    checkRolEdit();
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

function readURL3(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#img-change-profile-edit').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#img-change-edit").change(function(){
    readURL3(this);
});

$('#id_rol').change(function(){
    checkRol();
});

function checkRol(){
    var idRol= $($('#id_rol')).find("option:selected").attr('value');
    $("#pin").val('');
    $("#password").val('');
    $("#password_confirmation").val('');
    if(idRol=="4" || idRol=="5"){
        $("#part-password").hide();
        $("#part-pin").show();
    }else if(idRol==""){
        $("#part-password").hide();
        $("#part-pin").hide();
    }else{
        $("#part-password").show();
        $("#part-pin").hide();
    }
}

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

checkRolEdit();

$('#id_rol_edit').change(function(){
    checkRolEdit();
});

function checkRolEdit(){
    var idRol= $($('#id_rol_edit')).find("option:selected").attr('value');
    if(idRol=="4" || idRol=="5"){
        $("#part-password-edit").hide();
        $("#part-pin-edit").show();
    }else if(idRol==""){
        $("#part-password-edit").hide();
        $("#part-pin-edit").hide();
    }else{
        $("#part-password-edit").show();
        $("#part-pin-edit").hide();
    }
}