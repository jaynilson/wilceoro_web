var table_fleetcustom=null;
var table_permission=null;
var KTDatatablesDataSourceAjaxServer = function() {
	var initTable1 = function() {
		table_fleetcustom = $('#kt_fleetcustom_table').DataTable({
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
                url:"settings/fleet_custom/dataTable",
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val() }
            },
			columns: [
				{data: 'title',responsivePriority: 1},
                {data: 'type',responsivePriority: 2},
                {data: 'created_at',responsivePriority: 3},
                {data: 'status',responsivePriority: 4},
				{data: 'actions',  responsivePriority: -1}
			],
			columnDefs: [
                {
                    'targets': 1,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return data=='string'?"Text":(
                            data=='integer'?"Number without Decimals":(
                                data=='double'?"Number with Decimals":(
                                    data=='boolean'?"YES/NO":(
                                        data=='date'?"Date":""
                                    )
                                )
                            )
                        );
                    }
                },{
                    'targets': 3,
                    'orderable': true,
                    'className': 'dt-body-center text-center',
                    'render': function (data, type, full, meta){
                        if(data=='true'){
                            return `
                            <input type="hidden" value="${full.id}" />
                            <div class="status-green p-1">Available</div>`;
                        }else {
                            return `
                            <input type="hidden" value="${full.id}" />
                            <div class="status-gray p-1">Disabled</div>`;
                        }
                    }
                },{
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        var ___p = JSON.parse($('#___p').val());
                        var _w = checkP(___p, 8, 1);
                        var _d = checkP(___p, 8, 3);
                        return _w||_d?`
                            <span class="dropdown">
                                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                    <i class="la la-ellipsis-h"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    `+(_w?`<a class="dropdown-item" href="#" onclick='editFleetCustom(${JSON.stringify(data)})'><i class="flaticon-edit"></i> See or edit </a>`:``)+`
                                    `+(_d?`<a class="dropdown-item" href="#" onclick="deleteFleetCustom(`+data.id+`)"><i class="flaticon-delete"></i> Delete</a>`:``)+`
                                </div>
                            </span>
                        `:``;
                    },
                }
            ],
            //order: [[0, 'asc']]
        });
    };

    var initTable2 = function() {
		table_permission = $('#kt_permission_table').DataTable({
            dom: 't',
            pageLength: -1,
            responsive: true,
            colReorder: true,
			searchDelay: 500,
			processing: true,
            serverSide: true,
            serverMethod: 'post',
            language: table_language,
            ajax: {
                url:`settings/permission/dataTable?id_rol=${$("#filter_role").val()}`,
                dataType: "json",
                type: "POST",
                data:{ _token:$('#token_ajax').val() }
            },
			columns: [
                {data: 'name'},
                {data: 'module'},
                {data: 'read'},
				{data: 'write'},
				{data: 'create'},
				{data: 'delete'}
			],
			columnDefs: [
                {
                    'targets': 2,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return `
                            <input 
                                type="checkbox" 
                                class="form-control" 
                                ${data==1?'checked':''} 
                                name="checkbox-permission" 
                                data-page="${full.id}" 
                                data-type="0"
                                style="width: 100%; height: 20px;"
                            />
                        `;
                    }
                },{
                    'targets': 3,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return `
                            <input 
                                type="checkbox" 
                                class="form-control" 
                                ${data==1?'checked':''} 
                                name="checkbox-permission" 
                                data-page="${full.id}" 
                                data-type="1"
                                style="width: 100%; height: 20px;"
                            />
                        `;
                    }
                },{
                    'targets': 4,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return `
                            <input 
                                type="checkbox" 
                                class="form-control" 
                                ${data==1?'checked':''} 
                                name="checkbox-permission" 
                                data-page="${full.id}" 
                                data-type="2"
                                style="width: 100%; height: 20px;"
                            />
                        `;
                    }
                },{
                    'targets': 5,
                    'searchable': true,
                    'orderable': true,
                    'className': '',
                    'render': function (data, type, full, meta){
                        return `
                            <input 
                                type="checkbox" 
                                class="form-control" 
                                ${data==1?'checked':''} 
                                name="checkbox-permission" 
                                data-page="${full.id}" 
                                data-type="3"
                                style="width: 100%; height: 20px;"
                            />
                        `;
                    }
                },
            ],
            order: [],
        });
    };

	return {
		//main function to initiate the module
		init: function() {
			initTable1();
            initTable2();
		},
    };
}();

jQuery(document).ready(function() {
    KTDatatablesDataSourceAjaxServer.init();
    loadTab();

    $('#filter_role').on('change', function(){
        var url = `settings/permission/dataTable?id_rol=${$(this).val()}`;
        table_permission.ajax.url(url).load();
    });
});
function addFleetCustom(){
    $('#nav-fleetcustom-tab').trigger('click');
    $('#modalLabelFleetfield').html('Add New Fleet Custom');
    $('#edit_fleetcustom_id').val(-1);
    $('#edit_fleetcustom_title').val('');
    $('#edit_fleetcustom_type').val('string');
    $("#modal_edit_fleetfield input[name=status][value=true]").prop('checked', true);
}
function editFleetCustom(data){
    $('#modalLabelFleetfield').html('Edit Fleet Custom');
    $('#edit_fleetcustom_id').val(data.id);
    $('#edit_fleetcustom_title').val(data.title);
    $('#edit_fleetcustom_type').val(data.type);
    $("#modal_edit_fleetfield input[name=status][value=" + data.status + "]").prop('checked', true);
    $("#modal_edit_fleetfield").modal("show");
}
function deleteFleetCustom(id){
    $('#id_delete').val(id);
    $('#modal_delete_fleetfield').modal('show');
}

function setTab(tab){
    localStorage.setItem("tab-setting", tab);
}

function loadTab(){
    var tab = localStorage.getItem("tab-setting");
    if(tab!=null && tab!=""){
        document.querySelector('#'+tab+"-tab").click();
        window.scrollTo(0, document.body.scrollHeight);
    }
}

function savePermission(){
    var permissions = [];//this need list of data with 5 numbers for example, 1, 0, 0, 0, 0 
    $('input[name="checkbox-permission"]').each(function(){
        if (this.checked) {
            var page = $(this).data('page');
            var type = $(this).data('type');
            
            var found = false;
            permissions.forEach(function(permission, index) {
                if (permission[0] === page) {
                    permission[type + 1] = 1;
                    found = true;
                    return false; // Exit the loop
                }
            });

            if (!found) {
                var newRow = [page, 0, 0, 0, 0];
                newRow[type + 1] = 1;
                permissions.push(newRow);
            }
        }
    });
    $.ajax({
        url: "settings/permission/save",
        type: 'POST',
        data: {
            id_rol: $('#filter_role').val(),
            permissions: JSON.stringify(permissions),
            _token: $('#token_ajax').val()
        },
        success: function (res) {
            var url = `settings/permission/dataTable?id_rol=${$('#filter_role').val()}`;
            table_permission.ajax.url(url).load();
        },
        error: function () {
        },
    });
}