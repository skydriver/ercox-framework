function validateEmail(email){
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}



jQuery(document).ready(function($){
    
    var body = $('body');
    
    
    //MAGIC LINE BEGIN
    
    var magic_line            = $('#header .ca_magic_line');
    var magic_line_visibility = magic_line.is(':visible');
    var main_nav              = $('#header ul.menu li a');
    var main_nav_list         = $('#header ul.menu li.current-menu-item a');
    var magic_animation       = $('#header div.ca_mobile_animation');
    
    if(main_nav_list.length === 1 && magic_line_visibility === true){
       findActiveMenu();
    }

    main_nav.on('mouseover', function(){    
        var temp = $(this);
    
        setMagicLine(temp);
    });
    
    main_nav.on('mouseleave', function(){
        findActiveMenu();
    });
    
    main_nav.on('click', function(){
        if(magic_line_visibility === true){
            body.addClass('menu-click');
        }
    });
    
    function setMagicLine(elem){
        if(magic_line_visibility === true){
        
            var width = $(elem).outerWidth();
            var left  = $(elem).position().left;
            var color = $(elem).parent().css('color');
            
            magic_line.css({
                'background': color, 
                'width': width,
                'left': left
            });
        }
    }
    
    function findActiveMenu(){
        if(main_nav_list.length === 1 && magic_line_visibility === true){
            
            var width = main_nav_list.outerWidth();
            var left  = main_nav_list.position().left;
            var color = main_nav_list.parent().css('color');
            
            magic_line.css({
                'background': color,
                'width': width,
                'left': left
            });
            
        }
    }
    
    //MAGIC LINE END
    
    //RESIZE EVENT BEGIN
    
    $(window).on('resize', function(){
        if(magic_line.is(':visible') === true){
            magic_line_visibility = true;
        }
        else{
            magic_line_visibility = false;
        }
        
        if(menu_toggle.is(':visible') != true){
            body.removeClass('open_menu no_scroll');
        }
        
    });
    
    //RESIZE EVENT END
    
    //RATING HOVER BEGIN
    
    var rating_stars = $('body').find('.rating-stars div.star');
    
    rating_stars.on('mouseover', function(){

        if (!$('body').hasClass('single-topics')) { return; };

        var lenght = rating_stars.length;
        var hovered = $(this).attr('data-id')-1;
        
        rating_stars.removeClass('hovered');
        
        while(hovered >= 0){
            rating_stars.eq(hovered).addClass('hovered');
            hovered--;
        }
        
    });
    
    rating_stars.on('mouseleave', function(){

        if (!$('body').hasClass('single-topics')) { return; };
        
        if(rating_stars.parent().hasClass('is-rating')){
            return;
        }
        
        rating_stars.removeClass('hovered');
    });
    
    //RATING HOVER END
    
    //FUNCTION FOR CHECKING VISIBILITY IN VIEWPORT BEGIN

    function isElementInViewport(el) {
        
        if( !$('body').hasClass('home') )
            return;
        
        var window_height    = $(window).height();
        var window_position  = $(document).scrollTop();
        
        var element_position = (el.offset().top + (el.innerHeight()/2)) - window_height;
        
        if(window_position > element_position){
            el.addClass('arrived');
        }
        
    }
    
    //FUNCTION FOR CHECKING VISIBILITY IN VIEWPORT END
    
    //CHECK ELEMENT VISIBILITY BEGIN
    
    var element_to_check = $('body.home .features-wrap.more-features');
    
    if(isElementInViewport(element_to_check) === true){
        element_to_check.addClass('arrived');
    }
    
    $(window).on('scroll', function(){
        
        if( typeof element_to_check == 'undefined' || element_to_check.length == 0 )
            return;

        isElementInViewport(element_to_check);
    });
    
    //CHECK ELEMENT VISIBILITY END
    
    //RESPONSIVE MENU TOGGLE BEGIN
    
    var main_navigation = $('#header .widget_nav_menu');
    var menu_toggle     = $('#header div.ca_mobile_menu');
    var menu_close_tgl  = $('#header div.ca_mobile_menu_close');
    
    menu_toggle.on('click', function(){
        body.addClass('open_menu no_scroll');
    });
    
    menu_close_tgl.on('click', function(){
        
        if(body.hasClass('user_profile')){
            closeUserProfilePopUp();
        }
        
        body.removeClass('open_menu no_scroll');
    });
    
    //RESPONSIVE MENU TOGGLE END
    
    //REGISTER POPUP NAVIGATION BEGIN
    
    var register_popup_nav = $('.register-popup div.mini_nav a');
    
    register_popup_nav.on('click', function(e){
        e.preventDefault();
        
        var main_parent = $(this).closest('div.register-popup');
        var target      = $(this).attr('data-element');
        
        if(target === 'login'){
            main_parent.removeClass('register forgot_password').addClass('login');
        }
        
        if(target === 'register'){
            main_parent.removeClass('login forgot_password').addClass('register');
        }
        
    });
    
    var forgot_password_link = $('div.register-popup a.forgot_password');
    
    forgot_password_link.on('click', function(e){
        e.preventDefault();
        
        var main_parent = $(this).closest('div.register-popup');
        
        main_parent.removeClass('login').addClass('forgot_password');
        
    });
    
    //REGISTER POPUP NAVIGATION END


    var register_speed         = 300;
    var register_popup         = $('body').find('.popup-login-register-main');
    var register_popup_overlay = register_popup.find('.overlay');
    var register_popup_wrap    = register_popup.find('.entry');
    var register_popup_close   = register_popup.find('div.close-popup');

    $('body').find('a.register-button, ul.menu li.register-button').on('click', function(e) {
        if( $(this).hasClass('upgrade-watch') )
            return;

        e.preventDefault();
        
        if($(this).hasClass('login-button')){
            register_popup.addClass('login');
        }
        else{
            register_popup.addClass('register');
        }

        register_popup.fadeIn(register_speed);
        register_popup_overlay.fadeIn(register_speed);
        body.removeClass('open_menu');
        body.addClass('no_scroll user_profile');
    });

    register_popup_overlay.on('click', function(e) {
        e.preventDefault();
        closeUserProfilePopUp();
    });
    
    register_popup_close.on('click', function(){
        closeUserProfilePopUp();
    });
    
    function closeUserProfilePopUp(){
        
        register_popup_overlay.fadeOut(register_speed);
        
        register_popup.fadeOut('normal', function(){
            register_popup.removeClass('login register forgot_password');
        });
        
        body.removeClass('no_scroll user_profile');
    }
    
    //MAIN POPUP BEGIN
    
    var main_popup_trigger   = $('.ca_custom_main_popup_trigger');
    var main_popup_overlay   = $('div.ca_custom_popup_overlay');
    var main_popup_close_btn = $('div.ca_custom_popup_close');
    
    $('body').on('click', '.ca_custom_main_popup_trigger', function(e){
        
        e.preventDefault();
        
        var elem = $(e.target);
        
        var popup_target = elem.attr('data-popup');
        
        $(popup_target).fadeIn();
        main_popup_overlay.fadeIn().attr('data-active-popup', popup_target);
        body.addClass('no_scroll popup-is-open');
        
    });
    
    main_popup_trigger.on('click', function(e){
        e.preventDefault();
        
        var elem = $(this);

        
        var popup_target = elem.attr('data-popup');
        
        $(popup_target).fadeIn();
        main_popup_overlay.fadeIn().attr('data-active-popup', popup_target);
        body.addClass('no_scroll popup-is-open');
    });
    
    main_popup_overlay.on('click', function(e){
        e.preventDefault();
        
        var active_popup = $(this).attr('data-active-popup');
        
        $(this).fadeOut().removeAttr('data-active-popup');
        $(active_popup).fadeOut();
        body.removeClass('no_scroll popup-is-open');
    });
    
    main_popup_close_btn.on('click', function(e){
        e.preventDefault();
        var active_elem  = $(this).parent().find('div.ca_custom_popup_overlay');
        var active_popup = active_elem.attr('data-active-popup');
        
        
        $(active_popup).fadeOut();
        active_elem.removeAttr('data-active-popup');
        body.removeClass('no_scroll');
    });
    
    //MAIN POPUP END
    
    //PROFILE CHECK READ MORE BUTTON BEGIN
    
    var all_box_collapsable = $('body.codeart-mediator-profile').find('div.box-collapsable');
    
    all_box_collapsable.each(function(){
        $(this).attr('data-height', $(this).innerHeight());
    });
    
    for(var i = 0; i<= all_box_collapsable.length -1; i++){
        var elem        = all_box_collapsable[i];
        var full_height = elem.scrollHeight;
        
        if($(elem).data('height') != full_height){
            $(elem).addClass('hide-more-btn');
        }
    }
    
    //PROFILE CHECK READ MORE BUTTON END
    
    //LIVE SEARCH BEGIN
    
    var live_search_trigger  = $('.trigger-live-search');
    var live_search_trigger2 = $('div.form-live-search div.search-icon');
    
    live_search_trigger.on('click', function(){
        openSearch();
    });
    
    live_search_trigger2.on('click', function(){
        
        if($(this).hasClass('open')){
            closeSearch();
            $(this).removeClass('open');
            return;
        }
        
        openSearch();
    });
    
    function openSearch(){
        
        var position = $(window).scrollTop() - 100;
        
        $('div.form-live-search').css('top', position);
        
        body.addClass('ca-search-init no-scroll').delay('100').queue(function(i){
            body.addClass('ca-search-ready');
            i();
        });
        
        live_search_trigger.closest('.form-live-search').addClass('open-search').delay('200').queue(function(i){
            $(this).addClass('ready-search');
            i();
        });
        
        live_search_trigger.closest('.home-top-section').addClass('search-open');
        live_search_trigger2.addClass('open');
        
        setTimeout(function(){
            live_search_trigger.focus();
        },1000);
        
    }
    
    function closeSearch(){
        
        $('div.form-live-search').removeAttr('style');
        
        live_search_trigger.val('');
        
        body.removeClass('ca-search-ready no-scroll').delay('100').queue(function(i){
            body.removeClass('ca-search-init');
            i();
        });
        
        var main_parent = live_search_trigger.closest('.form-live-search');
        
        main_parent.addClass('closing close-mobile').delay('200').queue(function(i){
            main_parent.removeClass('ready-search').delay('210').queue(function(j){
                main_parent.removeClass('open-search closing');
                j();
            });
            
            i();
        });
        
        setTimeout(function(){
            main_parent.removeClass('close-mobile');
        },400);
        
        live_search_trigger.closest('.home-top-section').removeClass('search-open');
    }
    
    //LIVE SEARCH END
    
     //FIXED MENU BEGIN
    
    function checkFixMenu(){
        var position = $(document).scrollTop();
        
        if(position > 50){
            body.addClass('fixed-menu');
        }
        else{
            body.removeClass('fixed-menu');
        }
    }
    
    checkFixMenu();
    
    $(window).on('scroll', function(){
        checkFixMenu();
    });
    
    //FIXED MENU END
    
    //CHAPSTER SCROLL BEGIN
    var topic_chapster       = $('div.topic-chapters-height');
    var active_topic_chapter = $('div.topic-chapters-height div.box-item.active');
    var max_topic_chapsters  = $('div.topic-chapters-height div.box-item');
    
    var before_active_chaps  = 0;
    var final_active_chaps = active_topic_chapter.index()-1;

    if(topic_chapster.length > 0){
        
        if(final_active_chaps > 2){
            var scroll_to_active_pos = (final_active_chaps * max_topic_chapsters.height()) - (max_topic_chapsters.height() * 2);
            
            topic_chapster.scrollTop(scroll_to_active_pos);
        }

        console.log(scroll_to_active_pos);

        $("body.codeart-content-sidebar-leyout div.topic-chapters-height").mCustomScrollbar({
            setTop: scroll_to_active_pos + 'px',
            theme:'dark',
            callbacks:{
                onInit: function(){
                    console.log('testiranje');
                    topic_chapster.find('div.chapter-height-spinner').fadeOut('200');
                }
            }
        });
        
    }
    //CHAPSTER SCROLL END
    

    //CUSTOM SCROLLBAR BEGIN
    if(body.hasClass('single-topics')){
     
        $('div.transcript-wrap').mCustomScrollbar({
            theme:'dark'
        });
        
    }
    //CUSTOM SCROLLBAR END
    
    
    //SEARCH FILTER BEGIN
    var ca_search_filter_trigger = $('div.ca-mobile-filter-trigger');
    var ca_search_filter         = $('body.codeart-search .entry-content .search-wrap .left.ca-filters');
    
    ca_search_filter_trigger.on('click', function(){
        ca_search_filter.toggleClass('open');
        $(this).toggleClass('open');
    });
    //SEARCH FILTER END
    


    //CUSTOM SWITCH BEGIN
    var ca_custom_switch = $('div.ca-custom-switch div.button');
    var ca_custom_price_switch = $('div.new-pricing-temporary div.comp-box .ca-price-can-change span');
    var cheched_plans = 0;

    ca_custom_switch.on('click', function(){

        var upgrade_button = $('body').find('a.ca-upgrade-premium');
        cheched_plans = cheched_plans == 0 ? 1 : 0;

        var type = cheched_plans ? 'monthly' : 'yearly';
        upgrade_button.attr('href', upgrade_button.data('url') + '?type=' + type);

        var sale_price     = ca_custom_price_switch.data('promo-price');
        var original_price = ca_custom_price_switch.data('original-price');
        
        $(this).toggleClass('checked');
        
        if( ca_custom_price_switch.hasClass('sale-price') === true){
            // ca_custom_price_switch.text(original_price).removeClass('sale-price');
            ca_custom_price_switch.text(sale_price).removeClass('sale-price');
            return;
        }
        
        

        // ca_custom_price_switch.text(sale_price).addClass('sale-price');
        ca_custom_price_switch.text(original_price).addClass('sale-price');
    });
    //CUSTOM SWITCH END
    


    //CUSTOM TRIGGER FOR CONTENT BEGIN
    var ca_custom_content_trigger = $('body.single-topics a.topic-content-button');
    
    ca_custom_content_trigger.on('click', function(e){
        e.preventDefault();
        
        var parent = $(this).closest('div.topic-content-wrap');
        
        if( parent.hasClass('open') ){
            $(this).text('See More');
            parent.find('div.inner-topic-content').stop().slideUp('normal', function(){
               parent.removeClass('open'); 
            });
            return;
        }
        
        parent.addClass('open');
        
        parent.find('div.inner-topic-content').stop().slideDown();
        $(this).text('See Less');
    });
    
    //CUSTOM TRIGGER FOR CONTENT END

});