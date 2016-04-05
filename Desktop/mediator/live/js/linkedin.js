jQuery(document).ready(function($){
    var form_box  = $('div.box form');
    var error_msg = $('div.box p.error');
    
    form_box.find('input[type=text], input[type=email]').on('click', function(){
        $(this).removeClass('validation-error');
        error_msg.stop().slideUp();
    });
});