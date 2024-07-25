Pace.on("start", function(){
    $("#overlay-pilates").show();
});
$.hasAjaxRunning = function() {
    return $.active != 0;
 }
Pace.on("done", function(){
    if($.hasAjaxRunning()) {
    $(document).ajaxStop(function(){
        $('#overlay-pilates').delay(100).fadeOut(200);
      });
    }else{
        $('#overlay-pilates').delay(100).fadeOut(200);
    }
    
});

function showOverlay(){
$("#overlay-pilates").show();
}

function hideOverlay(){
$("#overlay-pilates").delay(100).fadeOut(200);
}
$("form").submit(function (e) {
showOverlay();
});

$(document).ready(function(){
    $( document ).on( 'focus', ':input', function(){
        $( this ).attr( 'autocomplete', 'off' );
    });
});

$('.modal').on('show.bs.modal', function(){
    $('html').css('overflow', 'hidden');
}).on('hide.bs.modal', function(){
    $('html').css('overflow', 'auto');
})


function getFormatDate(d){
    if(d==null) return ``;
    if(d.split('-').length>2){
        d = d.split('-');
        return `${d[1]}/${d[2]}/${d[0]}`;
    }
    return d;
}