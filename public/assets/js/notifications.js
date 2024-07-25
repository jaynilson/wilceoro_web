//var socket = io.connect(apiNotifications,{'forceNew':true});
//socket.on(modeNotifications, function(data){processNotification(data);});

const audio = new Audio($("#path-p").val());
audio.play();

function processNotification(data){
    var htmlTemplateNotification=``;
    var containerNotifications=$("#container-notifications");
    if(parseInt(data.cod_receiver)==parseInt(cod_employee_session) && parseInt(data.type_receiver)==parseInt(cod_role_employee_session)){
        $("#default-notification-text").remove();
        var iconCode='';
        //html-class, image-public, image-profile
        if(data.type_icon=="html-class")iconCode=`<i class="${data.icon}"></i>`;
        if(data.type_icon=="image-public")iconCode=`<img src="${routePublicImagesConfig+data.icon}" class="image-notification">`;
        if(data.type_icon=="image-profile")iconCode=`<img src="${routePublicStorageProfilesConfig+data.icon}" class="image-notification">`;
        htmlTemplateNotification=`
            <a href="#"  onclick="readOpenNotification(${data.cod_n},this)" class="kt-notification__item ${data.status}">
                <div class="kt-notification__item-icon">${iconCode}</div>
                <div class="kt-notification__item-details">
                <div class="kt-notification__item-title">
                ${data.title_n}
                </div>
                <div class="kt-notification__item-time">
                ${data.msg_n}
                </div>
                <div class="kt-notification__item-time">
                ${data.date_n_formated}
                </div>
                </div>
            </a>
        `;
        $(htmlTemplateNotification).prependTo(containerNotifications);
        var countNotifications=parseInt($("#count-notifications").text())+1;
        $("#count-notifications").text(countNotifications);
        $("#notification-audio")[0].play();
    }
}

setInterval(function(){ 
    loadNotifications();
}, 7000);

loadNotifications();
function loadNotifications(){
    $.ajax({
        url: "/notification/last",
        type: 'POST',
        data: {
        _token: $('#token_ajax_notification').val()
        },
        success: function (res) {
            var htmlTemplateNotification=``;
            var containerNotifications=$("#container-notifications");
            res.notifications.forEach(data => {
                var iconCode='';
                //html-class, image-public, image-profile
                if(data.type_icon=="html-class")iconCode=`<i class="${data.icon}"></i>`;
                if(data.type_icon=="image-public")iconCode=`<img src="${routePublicImagesConfig+data.icon}" class="image-notification">`;
                if(data.type_icon=="image-profile")iconCode=`<img src="${routePublicStorageProfilesConfig+data.icon}" class="image-notification">`;
                htmlTemplateNotification+=`
                    <a href="#"  onclick="readOpenNotification(${data.id},this)" class="kt-notification__item ${data.status}">
                    <div class="kt-notification__item-details">
                    <div class="kt-notification__item-title">
                    ${data.title}
                    </div>
                    <div class="kt-notification__item-time">
                    ${data.message}
                    </div>
                    <div class="kt-notification__item-time">
                    ${data.created_at}
                    </div>
                    </div>
                    </a>
                `;
            });
            containerNotifications.html(htmlTemplateNotification);
            var countNotifications=parseInt($("#count-notifications").text());
            if(res.no_read>countNotifications){
                //sound
                $("#count-notifications").text(res.no_read)
                $("#notification-audio")[0].play();
            }else{
                $("#count-notifications").text(res.no_read)
            }
        },
        error: function (xhr, status, error) {
            console.log(JSON.stringify(xhr));
        },
    });
}

function readOpenNotification(id, element){
    showOverlay();
    $.ajax({
        url: "/notification/change_status_notification",
        type: 'POST',
        data: {
        id:id,
        _token: $('#token_ajax_notification').val()
        },
        success: function (res) {
            res=res[0];
            $("#menu-notifications").removeClass('show');
            $("#menu-notifications").addClass('hide');
            var countNotifications=parseInt($("#count-notifications").text())-1;
            if(countNotifications<=0)
            countNotifications=0;
            $("#count-notifications").text(countNotifications);
            $(element).removeClass('no_read');
            //redirect,message,modal_redirect,none
            switch (res.type_notification) {
            case 'redirect':
                openLinkNotification(res.path);
            break;
            case 'message':
                $("#title-notification-modal-message").text(res.title);
                $("#message-notification-modal-message").text(res.message);
                hideOverlay();
                $("#modal_message_notification").modal("show");
            break;
            case 'modal_redirect':
                $("#title-notification-modal").text(res.title);
                $("#message-notification-modal").text(res.message);
                $("#btn-notification-link").attr("onclick",`openLinkNotification('${res.path}')`);
                hideOverlay();
                $("#modal_redirect_notification").modal("show");
            break;
            case 'none':
                hideOverlay();
            break;
            }
        },
        error: function (xhr, status, error) {
            console.log(JSON.stringify(xhr));
        },
    });
}

function openLinkNotification(link){
    location.href = link;
}
