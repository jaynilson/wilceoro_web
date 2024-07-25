jQuery(document).ready(function() {
    changeType();
    loadFleetList();
    loadInterfaceList();
    loadWatcherList();

    $('#edit_id_fleet').on('change', function(){
        //
    });

    $('#edit_type').on('change', function(){
        changeType();
    });

    $('#edit_time_interval_unit').on('change', function(){
        $('#edit_common_interval').val('');
        changeUnit();
    });

    var val = $('#edit_common_interval').val();
    changeUnit();
    $('#edit_common_interval').val(val);
});

function changeUnit(){
    var type = $('#edit_time_interval_unit').val();
    $('#edit_common_interval').remove();
    var html = `<input 
            type="`+(type==2?`number`:`text`)+`" 
            name="common_interval" 
            class="form-control `+(type==0?`form-datepicker`:(
                type==1?`form-timepicker`:``
            ))+`" 
            id="edit_common_interval" 
            required
        />`;
    $('#edit_common_interval_wrapper').append(html);
    if(type==0){
        $('#edit_common_interval').datepicker({
            autoclose: true,
            format: 'mm/dd/yyyy',
            todayHighlight: true,
            language: 'es',
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>',
            },
        }).on('show.datepicker', function(e) {
            
        });
    }else if(type==1){
        $('#edit_common_interval').timepicker({
            defaultTime: 'current',
            minuteStep: 1,
            showSeconds: true,
            showMeridian: true,
            // disableFocus: false,
            modalBackdrop: false,
        }).on('show.timepicker', function(e) {
            $('.glyphicon.glyphicon-chevron-up').html('<i class="fa fa-angle-up p-1"></i>');
            $('.glyphicon.glyphicon-chevron-down').html('<i class="fa fa-angle-down p-1"></i>');
        });
    }
}

function changeType(){
    if($('#edit_type').val()=='service'){
        $('.select-service-wrapper').fadeIn();
        $('.select-interface-wrapper').fadeOut();
    }else if($('#edit_type').val()=='renewal'){
        $('.select-service-wrapper').fadeOut();
        $('.select-interface-wrapper').fadeIn();
    }else{
        $('.select-service-wrapper').fadeOut();
        $('.select-interface-wrapper').fadeOut();
    }
}

function loadFleetList(){
    //$('#edit_id_fleet').html('');
    $.ajax({
        url: "/api/getFleetList",
        type: 'POST',
        data: {
            _token: $('#token_ajax').val()
        },
        success: function (res) {
            res.forEach(fleet => {
                var op = $("<option>", {
                    value: fleet.id,
                    text: `N ` + fleet.n + `, ` + fleet.model,
                    selected: ($('#reminder_id_fleet').val() == fleet.id) ? true : false,
                });
                if($('#prev_id_fleet').val()){
                    if($('#reminder_id_fleet').val() == fleet.id) $('#edit_id_fleet').append(op);
                }else{
                    $('#edit_id_fleet').append(op);
                }
            });
            $('#edit_id_fleet').select2({
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: $('#edit_id_fleet').parent()
            });
            $('.select2.select2-container .select2-selection__arrow').html('<i class="kt-menu__ver-arrow la la-angle-down"></i>');
            loadService();
        },
        error: function () {//xhr, status, error
            //
        },
    });
}

function loadService(){
    $.ajax({
        url: "/api/getServiceList",
        type: 'POST',
        data: {
            _token: $('#token_ajax').val(),
            id_fleet: $('#edit_id_fleet').val()
        },
        success: function (res) {
            res.forEach(service => {
                var op = $("<option>", {
                    value: service.id,
                    text: service.description,
                    selected: ($('#reminder_id_service').val() == service.id) ? true : false,
                });
                $('#edit_id_service').append(op);
            });
            $('#edit_id_service').select2({
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: $('#edit_id_service').parent()
            });
            $('.select2.select2-container .select2-selection__arrow').html('<i class="kt-menu__ver-arrow la la-angle-down"></i>');
        },
        error: function () {//xhr, status, error
            //
        },
    });
}

function loadInterfaceList(){
    $.ajax({
        url: "/api/getInterfaceList",
        type: 'POST',
        data: {
            _token: $('#token_ajax').val(),
        },
        success: function (res) {
            res.forEach(interafce => {
                var op = $("<option>", {
                    value: interafce.id,
                    text: interafce.title,
                    selected: ($('#reminder_id_interface').val() == interafce.id) ? true : false,
                });
                $('#edit_id_interface').append(op);
            });
            $('#edit_id_interface').select2({
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: $('#edit_id_interface').parent()
            });
            $('.select2.select2-container .select2-selection__arrow').html('<i class="kt-menu__ver-arrow la la-angle-down"></i>');
        },
        error: function () {//xhr, status, error
            //
        },
    });
}

function loadWatcherList(){
    $.ajax({
        url: "/api/getRoleList",
        type: 'POST',
        data: {
            _token: $('#token_ajax').val(),
        },
        success: function (res) {
            res.forEach(item => {
                var op = $("<option>", {
                    value: -item.id,
                    text: 'All ' + item.name.replace(/\b\w/g, function(l) { return l.toUpperCase(); }) + ' (' + (item.users_count? item.users_count + ' users':'empty') + ')',
                });
                //$('#edit_id_watchers').append(op);
            });

            $.ajax({
                url: "/api/getUserList",
                type: 'POST',
                data: {
                    _token: $('#token_ajax').val(),
                },
                success: function (res) {
                    res.forEach(item => {
                        var op = $("<option>", {
                            value: item.id,
                            //text: item.name + ' ' + item.last_name + ' (' + item.role.name.replace(/\b\w/g, function(l) { return l.toUpperCase(); }) + ')',
                            text: item.email,
                        });
                        $('#edit_id_watchers').append(op);
                    });
                    $('#edit_id_watchers').select2({
                        dropdownAutoWidth: true,
                        width: '100%',
                        dropdownParent: $('#edit_id_watchers').parent(),
                        multiple: true
                    });
                    $('.select2.select2-container .select2-selection__arrow').html('<i class="kt-menu__ver-arrow la la-angle-down"></i>');
                },
                error: function () {//xhr, status, error
                    //
                },
            });
        },
        error: function () {//xhr, status, error
            //
        },
    });
}