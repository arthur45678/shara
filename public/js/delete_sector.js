$(".show_modal_sector" ).click(function() {
    var id = $(this).attr('alt');
    $('.delete_sector').attr('href', '/admin/delete-sector/'+id);
        
});