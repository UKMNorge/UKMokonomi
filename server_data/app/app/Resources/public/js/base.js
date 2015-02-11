var APP_PATH = '/';

$(document).on('click','#UKMeco_menu_button',function(e){
	e.preventDefault();
	$('#UKMeco_menu').fadeToggle(function(){
		$('#UKMeco_content').toggle();
	});
});

/*
// MODERNIZER DATEPICKER
$(document).ready(function(){
	if (!Modernizr.touch || !Modernizr.inputtypes.date) {
	    $('input[type=date]')
	        .attr('type', 'text')
	        .datepicker({
	            // Consistent format with the HTML5 picker
	            dateFormat: 'yy-mm-dd'
	        });
	}
});
*/

window.onbeforeunload = function() {
	$('#UKMeco_content').fadeOut(400, function(){$('#UKMeco_loader').fadeIn();});
};