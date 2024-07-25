"use strict";

// Class Definition
var KTLoginGeneral = function() {

    var login = $('#kt_login');


    var displaySignInForm = function() {
        login.removeClass('kt-login--forgot');
        login.removeClass('kt-login--signup');

        login.addClass('kt-login--signin');
        KTUtil.animateClass(login.find('.kt-login__signin')[0], 'flipInX animated');
        //login.find('.kt-login__signin').animateClass('flipInX animated');
    }

    var displayForgotForm = function() {
        login.removeClass('kt-login--signin');
        login.removeClass('kt-login--signup');

        login.addClass('kt-login--forgot');
        //login.find('.kt-login--forgot').animateClass('flipInX animated');
        KTUtil.animateClass(login.find('.kt-login__forgot')[0], 'flipInX animated');

    }

    var handleFormSwitch = function() {
        $('#kt_login_forgot').click(function(e) {
            e.preventDefault();
            displayForgotForm();
        });

        $('#kt_login_forgot_cancel').click(function(e) {
            e.preventDefault();
            displaySignInForm();
        });

   
    }


    var handleSignInFormSubmit = function() {
     /*   $('#kt_login_signin_submit').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');           

            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');           

            form.validate({
          
            });

            if (!form.valid()) {
                return;
            }else{
                btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);
                form.submit();
            }
     
        });*/
    }

 

    var handleForgotFormSubmit = function() {
        $('#kt_login_forgot_submit').click(function(e) {
            e.preventDefault();

            var btn = $(this);
            var form = $(this).closest('form');

            form.validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }else{
                btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);
                form.submit();
            }
        
        });
    }
   
    $('#age').change(function(){

var age=parseFloat( $(this).find("option:selected").html());
        if(age<18){
        showFormTutor();
}else{
    hiddenFormTutor();
}
    });

    function checkFormTutor(){

    }
    // Public Functions
    return {
        // public functions
        init: function() {
            handleFormSwitch();
            handleSignInFormSubmit();
            handleForgotFormSubmit();

            if(resetForm){
                displayForgotForm();
            }else{
                displaySignInForm();
            }
            hiddenFormTutor();
        }
    };

  
}();


function hiddenFormTutor(){
    $("#tutor-part").hide();
    $('#tutor-part input').each(function(index){  
        var input = $(this);
        input.attr("type", "hidden");
        });
}
function showFormTutor(){
    

    $("#tutor-part").show();
    $('#tutor-part input').each(function(index){  
        var input = $(this);
       
        if(input.attr("name")!="tutor_tel"){
            input.attr("type", "text");
        }
        });
}
// Class Initialization
jQuery(document).ready(function() {
    KTLoginGeneral.init();
});
