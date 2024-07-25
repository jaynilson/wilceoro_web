"use strict";
var table=null;
var previousTd = null;
var previousTdHtml = '';
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
                url:"interface_dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val()}
            },
			columns: [
                {data: 'id'},
				{data: 'title',responsivePriority: 1},
                {data: 'type',responsivePriority: 2},
                {data: 'critical',responsivePriority: 3},
                {data: 'created_at' ,responsivePriority: -2},
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
                },
                {
                    'targets': 1,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                        return `<div class="data-col-wrapper" data-val="${data}">${data}</div>`;
                    }
                },
                {
                    'targets': 2,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                        // var options = ['equipment_trailer', 'trucks_cars'];
                        // var optionTexts = ['Equipment/Trailer', 'Vehicle'];
                        return `<div class="data-col-wrapper" data-val="${data}">${
                            data=='equipment_trailer'?'Equipment/Trailer':'Vehicle'
                        }</div>`;
                    }
                },
                {
                    'targets': 3,
                    'orderable': true,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                        if(data=='false'){
                            return `<div class="status-red p-1 data-col-wrapper" data-val="${data}">No</div>`;
                        }else if(data=='true'){
                            return `<div class="status-green p-1 data-col-wrapper" data-val="${data}">Yes</div>`;
                        }
                    }
                },{
                    targets: -1,
                    title: 'Actions',
					orderable: false,
					render: function(data, type, full, meta) {
						var ___p = JSON.parse($('#___p').val());
                        var _w = checkP(___p, 9, 1);
                        var _d = checkP(___p, 9, 3);
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
    $("#id-edit").val(data.type);
    $("#modal_edit_element").modal("show");
    if(data.picture=="" || data.picture==null){
        $('#img-change-element-edit').attr('src',"/assets/images/upload_picture.png");
    }else{
        $('#img-change-element-edit').attr('src',"/storage/images/interface/"+data.picture);
    }
    $("#id-edit").val(data.id);
    $("#modal_edit_element input[name=critical][value=" + data.critical + "]").prop('checked', true);
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

    $('#kt_table_1').on('click', 'td', function() {
        if(!checkP(JSON.parse($('#___p').val()), 9, 1)) return;
        var currentTd = $(this);
        var currentTr = currentTd.closest('tr');
        var columnIndex = currentTr.find('td').index(currentTd);
        if (previousTd && !previousTd.is(currentTd)) {
            removeInputOrSelect(previousTd);
        }
        if(!(columnIndex==1||columnIndex==2||columnIndex==3)){
            return;
        }
        previousTdHtml = currentTd.html();
        var dataId = currentTr.find('td:first-child input[type="checkbox"]').val();
        var dataTitle = currentTr.find('td:nth-child(2) .data-col-wrapper').data('val');
        var dataType = currentTr.find('td:nth-child(3) .data-col-wrapper').data('val');
        var dataCritical = currentTr.find('td:nth-child(4) .data-col-wrapper').data('val');
        if(columnIndex==1){
            if (currentTd.find('input').length > 0) {
                return;
            }
            var input = $(`<input type="text" class="form-control" value=${currentTd.text()}>`);
            currentTd.empty().append(input);
            input.focus();
            var inputLength = input.val().length;
            input[0].setSelectionRange(inputLength, inputLength);

            input.on('keyup', function(event) {
                if (event.keyCode === 13) {
                    var updatedContent = $(this).val();
                    if(dataTitle!=updatedContent)
                        saveCellData({
                            id:dataId,
                            title: updatedContent,
                            type: dataType,
                            critical: dataCritical,
                            _token: $('#token_ajax').val()
                        });
                }
            });

            input.on('blur', function() {
                var updatedContent = $(this).val();
                if(dataTitle!=updatedContent)
                    saveCellData({
                        id:dataId,
                        title: updatedContent,
                        type: dataType,
                        critical: dataCritical,
                        _token: $('#token_ajax').val()
                    });
            });
        }else if(columnIndex==2){
            if (currentTd.find('select').length > 0) {
                return;
            }
            var select = $('<select class="form-control"></select>');
            var options = ['equipment_trailer', 'trucks_cars'];
            var optionTexts = ['Equipment/Trailer', 'Vehicle'];
            for (var i = 0; i < options.length; i++) {
                var option = $('<option></option>').attr('value', options[i]).text(optionTexts[i]);
                if (options[i] === dataType) {
                    option.attr('selected', 'selected');
                }
                select.append(option);
            }
            currentTd.empty().append(select);
            select.on('change', function() {
                var updatedContent = $(this).val();
                if(dataType!=updatedContent)
                    saveCellData({
                        id: dataId,
                        title: dataTitle,
                        type: updatedContent,
                        critical: dataCritical,
                        _token: $('#token_ajax').val()
                    });
            });
        }else if(columnIndex==3){
            if (currentTd.find('select').length > 0) {
                return;
            }
            console.log(dataCritical);
            var select = $('<select class="form-control"></select>');
            var option1 = $('<option></option>').attr('value', true).text('Yes');
            if (dataCritical==true) {
                option1.attr('selected', 'selected');
            }
            select.append(option1);
            var option2 = $('<option></option>').attr('value', false).text('No');
            if (dataCritical==false) {
                option2.attr('selected', 'selected');
            }
            select.append(option2);

            currentTd.empty().append(select);
            select.on('change', function() {
                var updatedContent = $(this).val();
                if(dataCritical!=updatedContent)
                    saveCellData({
                        id: dataId,
                        title: dataTitle,
                        type: dataType,
                        critical: updatedContent,
                        _token: $('#token_ajax').val()
                    });
            });
        }
        previousTd = currentTd;
    });
});

function removeInputOrSelect(td) {
    var input = td.find('input');
    var select = td.find('select');
    if (input&&input.length > 0) {
        input.remove();
    }
    if (select&&select.length > 0) {
        select.remove();
    }
    td.html(previousTdHtml);
}

function saveCellData(data){
    $.ajax({
        url: "/interface/update",
        type: 'POST',
        data: data,
        success: function (data) {
            table.ajax.reload();
        },
        error: function (xhr, status, error) {
            console.log(JSON.stringify(xhr));
        },
    });
}

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