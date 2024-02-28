$(".show_modal_alert" ).click(function() {
    var id = $(this).attr('alt');
    $('.delete_alert').attr('href', '/admin/remove-alert/'+id);
        
});