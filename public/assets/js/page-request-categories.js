"use strict";
var table=null;
var KTDatatablesDataSourceAjaxServer = function() {
	var initTable1 = function() {
		// begin first table
		table = $('#kt_table_1').DataTable({
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
                url:"request_category_dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val()}
            },
			columns: [
                {data: 'id'},
				{data: 'title',responsivePriority: 1},
                {data: 'type',responsivePriority: 2},
                {data: 'status',responsivePriority: 3},
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
                    'targets': 3,
                    'orderable': true,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                        if(data=='false'){
                            return `<div class="status-red p-1">No</div>`;
                           }else if(data=='true'){
                            return `<div class="status-green p-1">Yes</div>`;
                           }
                        }
                },{
                    targets: -1,
                    title: 'Actions',
					orderable: false,
					render: function(data, type, full, meta) {
                        var ___p = JSON.parse($('#___p').val());
                        var _w = checkP(___p, 10, 1);
                        var _d = checkP(___p, 10, 3);
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
		//main function to initiate the module
		init: function() {
			initTable1();
		},
    };
}();

function editElement(data){
    $("#title_edit").val(data.title);
    $("#type_edit").val(data.type);
    $("#id-edit").val(data.id);
    $("#modal_edit_element input[name=status][value=" + data.status + "]").prop('checked', true);
    $("#modal_edit_element").modal("show");
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
            $('#img-change-element-edit').attr('src', e.target.result);
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