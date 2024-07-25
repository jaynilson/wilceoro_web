"use strict";
var table=null;
var KTDatatablesDataSourceAjaxServer = function() {
	var initTable1 = function() {
        var ___p = JSON.parse($('#___p').val());
        var _p = checkP(___p, 14);
        var columns = [
            {data: 'id'},
            {data: 'n'},
            {data: 'title'},
            {data: 'stock'},
            {data: 'stock'},
            {data: 'type'},
            {data: 'required_return'},
            {data: 'status'},
            {data: 'actions', responsivePriority: -1}
        ];

        if (_p) {
            columns.splice(3, 0, {data: 'price'});
        }

        var columnDefs = [
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
                    return full.available_stock>0?full.available_stock:`<font style="color:red;">${full.available_stock}</font>`;
                }
            },{
                'targets': 4,
                'orderable': true,
                'className': 'dt-body-center text-center',
                'render': function (data, type, full, meta){
                    return full.stock - full.available_stock;
                }
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
            },{
                targets: -1,
                title: 'Actions',
                orderable: false,
                render: function(data, type, full, meta) {
                    var ___p = JSON.parse($('#___p').val());
                    var _w = checkP(___p, 13, 1);
                    var _d = checkP(___p, 13, 3);
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
        ]

        if (_p) {
            columnDefs[1].targets = 4;
            columnDefs[2].targets = 5;
            columnDefs[3].targets = 7;
            columnDefs[4].targets = 8;
        }

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
                url:"/tool_dataTable/"+$('#_department').val(),
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val() }
            },
			columns: columns,
			columnDefs: columnDefs,
            order: [[0, 'desc']]
        });
	};
	return {
		init: function() {
			initTable1();
		},
    };
}();

function editElement(data){
    $("#n_edit").val(data.n);
    $("#title_edit").val(data.title);
    $("#price_edit").val(data.price);
    $("#stock_edit").val(data.stock);
    $("#type_edit").val(data.type);
    $("#modal_edit_element").modal("show");
    if(data.picture=="" || data.picture==null){
        $('#img-change-element-edit').attr('src',"/assets/images/upload_picture.png");
    }else{
        $('#img-change-element-edit').attr('src',"/storage/images/tools/"+data.picture);
    }
    $("#id-edit").val(data.id);
    $("#modal_edit_element input[name=status][value=" + data.status + "]").prop('checked', true);
    $("#modal_edit_element input[name=required_return]").prop('checked', data.required_return?true:false);
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

    $('#filter_type').on('change', function(){
        // table.ajax.params({
        //     _token:$('#token_ajax').val(),
        //     type:$('#filter_type').val()
        // });
        // table.ajax.reload();
        var url = `/tool_dataTable/`+$('#_department').val()+`?type=`+$('#filter_type').val();
        table.ajax.url(url).load();
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