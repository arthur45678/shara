$(".show_modal_category" ).click(function() {
    var id = $(this).attr('alt');
    $('.delete_category').attr('href', '/admin/delete-category/'+id);
        
});