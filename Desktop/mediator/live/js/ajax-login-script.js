
jQuery(document).ready(function($) {

    var login_form = $('form#login');
    var popup      = $('div.register-popup');
    
    login_form.find('input').on('click', function() {
        $(this).removeClass('validation-error')
    });

    // Perform AJAX login on form submit
    login_form.on('submit', function(e){
        
        $('form#login p.status').show().addClass('sending').text(ajax_login_object.loadingmessage);
        
        popup.addClass('error');
        
        login_form.find('input').removeClass('validation-error');

        var username  = login_form.find('#username');
        var password   = login_form.find('#password');
        var security  = login_form.find('#security');

        var error = false;

        if( !validateEmail(username.val()) )
        {
            username.addClass('validation-error');
            error = true;
        }

        if( password.val().length < 2 )
        {
            password.addClass('validation-error');
            error = true;
        }

        

        if( error )
        {
            $('form#login p.status').text('Enter the correct details').removeClass('sending');
            $('body').find('div.register-popup').addClass('error');
            return false;
        }

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl,
            data: { 
                'action': 'ajaxlogin',
                'username': username.val(), 
                'password': password.val(), 
                'security': security.val() },
                success: function(data){
                    
                    if(data.message === 'Wrong username or password.'){
                        $('form#login p.status').text(data.message).removeClass('sending');
                    }
                    
                    $('form#login p.status').text(data.message);
                    if (data.loggedin == true){
                        document.location.href = ajax_login_object.redirecturl;
                    }
                }
            });

        e.preventDefault();
    });

});