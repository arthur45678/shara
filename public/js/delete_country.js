$(".show_modal_country" ).click(function() {
    var id = $(this).attr('alt');
    $('.delete_country').attr('href', '/admin/delete-country/'+id);
        
});