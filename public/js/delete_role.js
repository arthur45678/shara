$(".show_modal_role" ).click(function() {
    $('.modal-inner').html('');
	$('.modal-title').text("Delete Role");
	$('.delete_role').show();
    var id = $(this).attr('alt');
    $.ajax({
        url:'/admin/get-users-role/'+id,
        method:'GET',
        data:'',
        success:function(data)
        {
            var users = data.role_users;
            if(users.length > 0)
            {
                $('.modal-inner').append("<p class = 'role-delete-warning'>Be careful, this role is attributed to the following users:</p>");
                for(var i = 0; i < users.length; i++)
                {
                    $('.modal-inner').append("<li class = 'role-user-warning'>"+users[i]+"</li>");
                }
            }
        }
    })
    // var slug = $(this).data('slug');
    // if(slug == 'superadmin' || slug == 'admin')
    // {
    // 	$('.modal-title').text("You can not delete this role!");
    // 	$('.delete_role').hide();  
    // }else{
    // 	$('.delete_role').attr('href', '/admin/delete-role/'+id);
    // }
        
});