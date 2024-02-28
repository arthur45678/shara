$(".show_modal_city" ).click(function() {
    var id = $(this).attr('alt');
    $('.delete_city').attr('href', '/admin/delete-city/'+id);
        
});