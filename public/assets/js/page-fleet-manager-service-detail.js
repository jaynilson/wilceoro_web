"use strict";
var table=null;
var table2=null;
var table3=null;
var typeSelect="add";
var filesAdd=[];
var KTDatatablesDataSourceAjaxServer = function() {
	var initTable1 = function() {
		// begin first table
        var columns = [
            {data: 'id'},
            {data: 'category_name'},
            {data: 'date'},
            {data: 'mechanic_name'},
            {data: 'tool_name'},
            {data: 'hour_spend'},
            {data: 'files'},
            {data: 'actions',  responsivePriority: -1}
        ];
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
            },
            {
                'targets': 6,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center text-center',
                'render': function (data, type, full, meta){
                return `<a onclick='openFiles(${JSON.stringify(data)})' style="background-color:black; color:white;" class="btn btn-default ">
                            <i class="icon-2x flaticon-arrows" style="color:white;"></i>
                        </a>`;
                }
            },
            {
                'targets': -1,
                'title': 'Actions',
                'orderable': false,
                render: function(data, type, full, meta) {
                    var ___p = JSON.parse($('#___p').val());
                    var _w = checkP(___p, 12, 1);
                    var _d = checkP(___p, 12, 3);
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
        ];
        var ___p = JSON.parse($('#___p').val());
        var _p = checkP(___p, 14);
        if(_p){
            columns.splice(7, 0, {data: 'cost'});
            columns.splice(8, 0, {data: 'total_cost'});
        }
		table = $('#kt_table_records').DataTable({
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
                url:"/services_recordsDataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val(),
                id:$('#id_service').val() }
            },
			columns: columns,
            rowId: function(data) {
                return  data.uuid;
            }, 
			columnDefs: columnDefs,
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
                url:"/user/dataTableMechanic",
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
        var columns = [
            {data: 'n'},
            {data: 'title'},
            {data: 'available_stock'},//stock
            {data: 'type'},
            {data: 'required_return'},
            {data: 'status'},
            {data: 'quantity', class: 'no-padding'},
            {data: 'actions',  responsivePriority: -1}
        ];
        var columnDefs = [
            {
                'targets': 2,
                'orderable': false,
                'render': function(data, type, full, meta) {
                    return full.available_stock<=0?`<font style="color:red;">${full.available_stock}</font>`:full.available_stock;
                },
            },
            {
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
            },
            {
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
            },
            {
                'targets': 6,
                'orderable': false,
                'render': function(data, type, full, meta) {
                    return `<div class="input-wrapper">
                                <input 
                                    type="number" 
                                    value="1" 
                                    min="1" max="${full.available_stock}" 
                                    class="input-quantity"
                                    id="input_quantity_${full.id}"
                                    onKeyPress="onQuantityKeyEvent(event)"
                                    style="${full.available_stock<=0?`display:none;`:``}" 
                                    autocomplete="off"
                                />
                            </div>`;
                },
            },
            {
                'targets': -1,
                'title': 'Select',
                'orderable': false,
                'render': function(data, type, full, meta) {
                    return full.available_stock<=0?``:`<a 
                        href="#" 
                        data-value="0" 
                        data-id="${full.id}" 
                        data-fullname="${full.actions.tool_name}" 
                        data-price="${full.price}" 
                        onClick='selectToolAction(${full.id})' 
                        class="btn btn-secondary btn-elevate btn-icon-sm p-1 select-tool-action"
                    >Select</a>`;
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
            columnDefs[3].targets = 7;
        }
		// begin first table
		table3 = $('#kt_table_tool_selected').DataTable({
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
                url:"/tool_dataTable/shop",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val()}
            },
			columns: columns,
			columnDefs: columnDefs,
            order: [[0, 'desc']]
        });
	};
	return {
		//main function to initiate the module
		init: function() {
            initTable1();
            initTable2();
            initTable3();
		},
    };
}();

function onQuantityKeyEvent(event) {
    var input = event.target;
    var value = parseInt(input.value + event.key, 10);
    var min = parseInt(input.min, 10) || 0;
    var max = parseInt(input.max, 10) || 0;
  
    if (value < min || isNaN(value)) {
      event.preventDefault();
      input.value = min;
    } else if (value > max) {
      event.preventDefault();
      input.value = max;
    }
  }

function openFiles(files){
    $("#container-modal-show-files").html("");
    var container =$("#container-modal-show-files");
    for (let index = 0; index < files.length; index++) {
        container.append(`<div class="file-service d-flex " id="file-div-${files[index].id}">
            <a class="file-service-name p-2" href="/storage/files_records/${files[index].picture}" target="_blank">${files[index].picture}</a>
            <div class="file-service-btn-delete p-2">
            </div>
        </div>`);
    }
    $("#modal_show_files").modal("show");
}

function editElement(data){
    $('#modal_edit').modal('show');     
    $("#id-edit").val(data.id);
    $("#id-category-edit").val(data.id_category);
    $("#description-edit").val(data.description);
    $("#hour_spend-edit").val(data.hour_spend);
    $("#cost-edit").val(data.cost);
    $("#text-mechanic-edit").val(data.mechanic_name);
    $("#id-mechanic-edit").val(data.id_mechanic);
    $("#text-tool-edit").val(data.tool_name);
    $("#price-tool-edit").val(data.tool_price);
    $("#quantity-tool-edit").val(data.tool_quantity);
    $("#id-tool-edit").val(data.tool_id);
    calcTotalCost('edit');
    $("#container-files-edit").html("");
    var container=$("#container-files-edit");
    for (let index = 0; index < data.files.length; index++) {
        container.append(`<div class="file-service d-flex " id="file-div-${data.files[index].id}">
            <a class="file-service-name p-2" href="/storage/files_records/${data.files[index].picture}" target="_blank">${data.files[index].picture}</a>
            <div class="file-service-btn-delete p-2" onclick="deleteFileDt(${data.files[index].id})">
            <i class="icon-2x text-dark-50 flaticon2-delete" ></i>
            </div>
        </div>`);
    }
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
        $("#modal_add").modal("show");
        $("#id-mechanic").val(id);
        $("#text-mechanic").val(full_name);
        $('#modal_select_employee').modal('hide');   
    }else{
        $("#modal_edit").modal("show");
        $("#id-mechanic-edit").val(id);
        $("#text-mechanic-edit").val(full_name);
        $('#modal_select_employee').modal('hide');   
    }
}

function OpenPrevModel(){
    if(typeSelect=="add"){
        $("#modal_add").modal("show");
    }else{
        $("#modal_edit").modal("show");
    }
}

function selectToolAction(id){
    var btn = $(`a[data-id="${id}"]`);
    var val = btn.data('value');
    btn.data('value', 1-val);
    btn.html(val>0?'Select':'Cancel');
    if(val==0){
        btn.removeClass('btn-secondary');
        btn.addClass('btn-primary');
    }else{
        btn.removeClass('btn-primary');
        btn.addClass('btn-secondary');
    }
}

function setToolForm(){
    var ids = [];
    var full_names = [];
    var prices = [];
    var quantitys = [];

    $(`a.select-tool-action`).each(function() {
        var btn = $(this);
        var value = btn.data('value');
        if(value==1){
            var id = btn.data('id');
            var full_name = btn.data('fullname');
            var price = btn.data('price');
            var quantity = $(`#input_quantity_${id}`).val();
            
            ids.push(id);
            full_names.push(full_name);
            prices.push(price);
            quantitys.push(quantity);
        }
    });

    var idsText = ids.join(",");
    var fullNamesText = full_names.join(", ");
    var pricesText = prices.join(",");
    var quantitysText = quantitys.join(",");

    if (typeSelect == "add") {
        $("#modal_add").modal("show");
        $("#id-tool").val(idsText);
        $("#text-tool").val(fullNamesText);
        $("#price-tool").val(pricesText);
        $("#quantity-tool").val(quantitysText);
        $('#modal_select_tool').modal('hide');
        calcTotalCost('add');
      } else {
        $("#modal_edit").modal("show");
        $("#id-tool-edit").val(idsText);
        $("#text-tool-edit").val(fullNamesText);
        $("#price-tool-edit").val(pricesText);
        $("#quantity-tool-edit").val(quantitysText);
        $('#modal_select_tool').modal('hide');
        calcTotalCost('edit');
      }
}

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
    
    $('#kt_table_services').on('click', 'tbody td', function() {
        const hiddenVal = $(this).find('input:hidden').val();
        if(hiddenVal!="" && hiddenVal!=null){
            window.open("/fleet_manager_service/"+hiddenVal,"_self");
        }
    });

    /*
    $('#id-tool').select2({
        dropdownAutoWidth: true,
        width: '100%',
        dropdownParent: $('#id-tool').parent()
    });
    $('#id-tool-edit').select2({
        dropdownAutoWidth: true,
        width: '100%',
        dropdownParent: $('#id-tool-edit').parent()
    });
    */
    
    $("#img-change").change(function(){
        var input=this;
        if (input.files && input.files[0]) {
          filesAdd.push(input.files[0]);
          var container = $("#container-files-add");
          container.append(`<div class="file-service d-flex">
            <div class="file-service-name p-2">${input.files[0].name}</div>
            <div class="file-service-btn-delete p-2">
                <i class="icon-2x text-dark-50 flaticon2-delete" onclick="deleteFileAdd(${filesAdd.length-1})"></i>
            </div>
            </div>`);
        }
    });

    $("#img-change-edit").change(function(){
        var input=this;
        if (input.files && input.files[0]) {
            var filesTmp=[];
            filesTmp.push(input.files[0]);
            showOverlay();
            var myFormData = new FormData();
            for (let index = 0; index < filesTmp.length; index++) {
                myFormData.append('myFiles[]', filesTmp[index]);
            }
            myFormData.append('id_record', $("#id-edit").val());
            myFormData.append('_token', $('#token_ajax').val());
            $.ajax({
                url: "/record_file",
                type: 'POST',
                processData: false, // important
                contentType: false, // important
                data:myFormData,
                success: function (data) {
                    var container = $("#container-files-edit");
                    for (let index = 0; index < data.files.length; index++) {
                        container.append(`<div class="file-service d-flex" id="file-div-${data.files[index].id}">
                        <a class="file-service-name p-2" href="/storage/files_records/${data.files[index].picture}" target="_blank">${data.files[index].picture}</a>
                        <div class="file-service-btn-delete p-2">
                        <i class="icon-2x text-dark-50 flaticon2-delete" onclick="deleteFileDt(${data.files[index].id})"></i>
                        </div>
                        </div>`);
                    }
                    hideOverlay();
                    table.ajax.reload(); 
                },
                error: function (xhr, status, error) {
                    hideOverlay();
                },
            });
        }
    });

    $("#form-add").submit(function(e){
        e.preventDefault();
        var form = $(this);

        form.validate({
            rules: {
                hour_spend: {
                    required: true
                },
                cost: {
                    required: true
                },
                id_employee: {
                    required: true
                },
            }
        });

        if (!form.valid()) {
            hideOverlay();
            return;
        }

        // if (!form.checkValidity()) {
        //     form.reportValidity();
        //     return;
        // }

        var idMechanic=$("#id-mechanic").val();
        if(idMechanic=="" || idMechanic==null){
            alert("Select mechanic");
            hideOverlay();
        }else{
            addRecord();
        }
    });
    
    $("#form-edit").submit(function(e){
        e.preventDefault();
        var form = $(this);
        form.validate({
            rules: {
                hour_spend: {
                    required: true
                },
                cost: {
                    required: true
                },
                id_employee: {
                    required: true
                },
            }
        });

        if (!form.valid()) {
            hideOverlay();
            return;
        }

        //var form = this;
        // if (!form.checkValidity()) {
        //     form.reportValidity();
        //     return;
        // }

        showOverlay();
        $.ajax({
            url: "/service_record_update",
            type: 'POST',
            data: {
                id: $("#id-edit").val(),
                description: $("#description-edit").val(),
                hour_spend: $("#hour_spend-edit").val(),
                cost: $("#cost-edit").val(),
                id_mechanic: $("#id-mechanic-edit").val(),
                id_tool: $("#id-tool-edit").val(),
                id_category: $("#id-category-edit").val(),
                amounts: $("#quantity-tool-edit").val(),
                _token: $('#token_ajax').val()
            },
            success: function (res) {
                $("#modal_edit").modal("hide");
                    hideOverlay();
                    location.reload();
                    //table.ajax.reload();
            },
            error: function (xhr, status, error) {
                console.log(JSON.stringify(xhr));
                hideOverlay();
            },
        });
    });

    $("#cost").on('change', function() {
        calcTotalCost('add');
    });
    
    $("#cost-edit").on('change', function() {
        calcTotalCost('edit');
    });
});

function calcTotalCost(type) {
    var totalCost = 0;
    var prices;
    var quantities;

    if (type === 'add') {
        prices = $("#price-tool").val().split(',').map(parseFloat);
        quantities = $("#quantity-tool").val().split(',').map(parseFloat);
    } else {
        prices = $("#price-tool-edit").val().split(',').map(parseFloat);
        quantities = $("#quantity-tool-edit").val().split(',').map(parseFloat);
    }

    for (var i = 0; i < prices.length; i++) {
        var price = prices[i] || 0;
        var quantity = quantities[i] || 0;
        totalCost += price * quantity;
    }

    if (type === 'add') {
        $("#total-cost").val(totalCost);
    } else {
        $("#total-cost-edit").val(totalCost);
    }
}

function openService(){
    window.open("/fleet_manager_service/12","_self")
}

function showModalSelectEmployee(type){
    if(type=='add'){
        $("#modal_add").modal("hide");
    }else{
        $('#modal_edit').modal("hide");
    }
    $('#modal_select_employee').modal('show');  
    typeSelect=type;
}

function showModalSelectTool(type){
    if(type=='add'){
        $("#modal_add").modal("hide");
    }else{
        $('#modal_edit').modal("hide");
    }
    $('#modal_select_tool').modal('show');  
    typeSelect=type;
}

function openAddModal(){
  $('#modal_add').modal('show');     
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

function deleteFileDt(id){
    showOverlay();
    const elem = document.getElementById("file-div-"+id);
    elem.remove();
    $.ajax({
        url: "/delete_record_file",
        type: 'POST',
        data:{
            id_delete:id,
            _token: $('#token_ajax').val()
        },
        success: function (data) {
            hideOverlay();
            table.ajax.reload(); 
        },
        error: function (xhr, status, error) {
            console.log(JSON.stringify(xhr));
            hideOverlay();
        },
    });
}

function deleteFileAdd(i){
    filesAdd.splice(i, 1);
    $("#container-files-add").html("");
    var container = $("#container-files-add");
    for (let index = 0; index < filesAdd.length; index++) {
        container.append(`<div class="file-service d-flex">
            <div class="file-service-name p-2">${filesAdd[index].name}</div>
            <div class="file-service-btn-delete p-2">
            <i class="icon-2x text-dark-50 flaticon2-delete" onclick="deleteFileAdd(${index})"></i>
            </div>
        </div>`);
    }
}

function addRecord(){
    showOverlay();
    $.ajax({
        url: "/service_record",
        type: 'POST',
        data: {
            description: $("#description").val(),
            hour_spend: $("#hour_spend").val(),
            cost: $("#cost").val(),
            id_mechanic: $("#id-mechanic").val(),
            id_service: $("#id_service").val(),
            id_tool: $("#id-tool").val(),
            id_category: $("#id-category").val(),
            amounts: $("#quantity-tool").val(),
            _token: $('#token_ajax').val()
        },
        success: function (res) {
            if(filesAdd.length ==0){
                $("#modal_add").modal("hide");
                hideOverlay();
                location.reload();
            }else{
                addFiles(res.record.id);
            }
        },
        error: function (xhr, status, error) {
            console.log(JSON.stringify(xhr));
            hideOverlay();
        },
    });
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
        url: "/record_file",
        type: 'POST',
        processData: false, // important
        contentType: false, // important
        data:myFormData,
        success: function (res) {
            $("#modal_add").modal("hide");
            hideOverlay();
            location.reload();
            filesAdd=[];
        },
        error: function (xhr, status, error) {
            console.log(JSON.stringify(xhr));
            hideOverlay();
        },
    });
 }

function goBack(){
    window.open('/fleet_manager_services',"_self");
}

function openModalAdd(){
    filesAdd = [];
    $("#modal_add").modal("show");
}