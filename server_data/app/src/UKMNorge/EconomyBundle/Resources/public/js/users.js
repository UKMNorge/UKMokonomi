$(document).on('click', '#userDeactivate', function( e ){
	e.preventDefault();
	
	var userDeactivate = confirm('Er du sikker på at du vil deaktivere denne brukeren?');
	
	if( userDeactivate ) {
		window.location.href = APP_PATH + 'admin/users/deactivate_do/' + $('#username').val();
	}
});