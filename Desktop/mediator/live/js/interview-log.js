jQuery(document).ready(function($) {
	wVideo_event = $('body').find("#mediator-iframe")[0].wistiaApi;

	wVideo_event.bind("play", function() {
		// alert('Please register ...');
    }); // End Video Event

    wVideo_event.bind("end", function() {

    	// var pid 		= '<?php the_ID(); ?>';
        var pid         = 1;
    	var type 		= 'finished';
    	var vid 		= $('body').find('.wistia-video').data('id');
    	var duration 	= wVideo_event.duration();

        var data = {
            'action': 'interveiw_log',
            'pid': pid,
            'vid' : vid,
            'type': type,
            'duration' : duration
        };

        $.post(ajaxurl, data, function(response) {
        	console.log( response );
            console.log( 'responseeeeee' );
        });
    }); // End Video Event

});