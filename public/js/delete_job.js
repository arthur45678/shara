$(".show_modal_job" ).click(function() {
    var id = $(this).attr('alt');
    $('.delete_job').attr('href', '/admin/delete-job/'+id);
        
});