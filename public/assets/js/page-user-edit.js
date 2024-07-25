"use strict";
var table1=null;
var table2=null;
var table3=null;
var table4=null;
var filesAdd=[];
var tmpFullData=[];
var custom_fields = null;
var KTDatatablesDataSourceAjaxServer = function() {
	var initTable1 = function() {
		// begin first table
		table1 = $('#table_driver_history').DataTable({
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
                data:{ _token:$('#token_ajax').val(),uid: $("#id").val() }
            },
			columns: [
                {data: 'fleet_name'},
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
                url:"/services_recordsDataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val(), uid: $("#id").val() }
            },
			columns: [
				{data: 'id'},
                {data: 'service_desc'},
                {data: 'category_name'},
				{data: 'date'},
                {data: 'tool_name'},
                {data: 'hour_spend'},
				{data: 'files'},
                {data: 'cost'},
                {data: 'tool_price'},
			],
            columnDefs: [
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
            ],
            order: [[0, 'desc']]
        });
        
        
	};
    
    var initTable3 = function() {
		// begin first table
		table3 = $('#table_accidents').DataTable({
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
                data:{ _token:$('#token_ajax').val(), uid: $("#id").val() }
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

    var initTable4 = function() {
		// begin first table
		table4 = $('#table_incidents').DataTable({
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
                {data: 'fleet_name'},
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
		},
    };
}();

function setTab(tab){
    localStorage.setItem("user-tab", tab);
}

function loadTab(){
    var tab = localStorage.getItem("user-tab");
    if(tab!=null && tab!=""){
        document.querySelector('#'+tab+"-tab").click();
        window.scrollTo(0, document.body.scrollHeight);
    }
}

jQuery(document).ready(function() {
    KTDatatablesDataSourceAjaxServer.init();
    loadTab();

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

    $('#user_id_rol').change(function(){
        checkRol();
    });
    checkRol();

    $("#edit_avatar_icon").on('click', function(){
        $('#avatar_upload').trigger('click');
    });

    $('#avatar_upload').on('change', function(){
        var input = this;
        if (input.files && input.files[0]){
            // $('#picture_upload_submit').css('display','block');
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#img-change-profile').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });

    $("#upload_cdl_btn").on('click', function(){
        $('#user_cdl').trigger('click');
    });

    $('#user_cdl').on('change', function(){
        // $("#upload_cdl_btn").fadeOut();
        $("#upload_cdl_btn").html('Change file');
        var input = this;
        if (input.files && input.files[0]){
            $('.cdl-file-name').html(input.files[0].name);
        }
    });
});

function checkRol(){
    var idRol= $($('#user_id_rol')).find("option:selected").attr('value');
    $("#user_pin").val('');
    $("#user_password").val('');
    $("#user_password_confirmation").val('');
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