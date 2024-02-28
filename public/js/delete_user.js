$(".show_modal_user" ).click(function() {
	$('.modal-title').text("Delete User");
	$('.delete_user').show();
    var id = $(this).attr('alt');
    var roles = $(this).data('roles');
    if(roles)
    {
        roles.forEach(function(role, key, roles)
        {
            if(role.slug == 'superadmin')
            {
                $('.modal-title').text("You can not delete this user!");
                $('.delete_user').hide();
            }
        });
    }
    
    $('.delete_user').attr('href', '/admin/delete-user/'+id);    
});