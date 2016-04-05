jQuery(document).ready(function($){
    
    //WORDPRESS FIELDS BEGIN
    
    var wp_input_fields = $('input.wpvalidate, textarea.wpvalidate');
    
    wp_input_fields.each(function(){
        var temp = $(this).val();
        $(this).data('original', temp);
    })
    
    //WORDPRESS FIELDS END
    
    //DATAPICKER BEGIN
    
    $('body').on('focus', 'input', function(e) {
        if( $(this).parent('div').hasClass('item-experience_period_from') || $(this).parent('div').hasClass('item-experience_period_to') || $(this).parent('div').hasClass('item-education_period_from') || $(this).parent('div').hasClass('item-education_period_to') ){
            
            function humanize(diff){
                var str = '';
                var values = [[' year', 365], [' month', 30], [' day', 1]];
                
                for (var i=0;i<values.length;i++) {        
                    var amount = Math.floor(diff / values[i][1]);
                    if (amount >= 1) {
                       str += amount + values[i][0] + (amount > 1 ? 's' : '') + ' ';
                       diff -= amount * values[i][1];
                    }
                  }
                
                return str;
            }
            
            var from_exp = $(this).closest('div.box_can_edit').find('.item-experience_period_from input');
            var to_exp = $(this).closest('div.box_can_edit').find('.item-experience_period_to input');
            
            var from_edu = $(this).closest('div.box_can_edit').find('.field-education_period_from input');
            var to_edu = $(this).closest('div.box_can_edit').find('.field-education_period_to input');
            
            from_exp.datepicker({
                onClose:function(selectedDate){
                    to_exp.datepicker('option', 'minDate', selectedDate);
                },
                changeMonth: true,
                changeYear: true
            });
            
            to_exp.datepicker({
                onClose:function(selectedDate){
                    from_exp.datepicker('option', 'maxDate', selectedDate);
                },
                onSelect:function(dateText, inst){
                    var oneDay = 24*60*60*1000;
                    var firstDate = new Date(from_exp.val().replace('/', ','));
                    var secondDate = new Date(dateText);

                    var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));
                },
                changeMonth: true,
                changeYear: true
            });
            
            from_edu.datepicker({
                onClose:function(selectedDate){
                    to_edu.datepicker('option', 'minDate', selectedDate);
                },
                changeMonth: true,
                changeYear: true
            });
            
            to_edu.datepicker({
                onClose:function(selectedDate){
                    from_edu.datepicker('option', 'maxDate', selectedDate);
                },
                changeMonth: true,
                changeYear: true
            });
            
            $(this).attr('readonly', 'readonly');
        }
    });

    
    //DATAPICKER END
    
    var editable_input_box  = $('div.box_can_edit');
    var editable_save_input = editable_input_box.find('a.update_fields');
    var form_submit_trigger = $('body.codeart-profile-edit div.bottom-buttons input[type=submit]');
    
    $('body').on('click', 'div.box_can_edit, div.box_can_edit a.mobile-edit-button', function(e) {
        var current = $(this);
        
        if(current.hasClass('editing')){
            return;
        }
        
        current.addClass('editing');
        
        $(this).find('.text_item').hide();
        current.find('.field-item').fadeIn('100');
        
    });

    
    $('body').on('click', 'a.update_fields', function(e) {
        e.preventDefault();
        
        var main_parent = $(this).closest('div.box_can_edit.editing');
        var all_inputs  = main_parent.find('input, textarea');
        
        main_parent.removeClass('error');
        
        all_inputs.each(function(){
            var temp = $(this);
            
            if( temp.val() != '' ){
                temp.removeClass('error');
            }
            else{
                temp.addClass('error');
                main_parent.addClass('error');
            }
        })
        
        if(main_parent.hasClass('error')){
            return;
        }
        
        var inputs = main_parent.find('div.field-item');
        
        inputs.each(function(){
            var data_key   = $(this).data('key');
            var temp_input = main_parent.find('.text_item[data-key="'+ data_key +'"]');
            
            temp_input.text($(this).find('input, textarea').val());  
        })
        
        
        main_parent.find('.field-item').hide();
        main_parent.find('.text_item').fadeIn('200').delay('100').queue(function(i){
            main_parent.removeClass('editing initial-repeater');
            i();
        });
        
        form_submit_trigger.click();
    });
    
    
    //VALIDATION BEGIN
    
    
    
    //VALIDATION END
});