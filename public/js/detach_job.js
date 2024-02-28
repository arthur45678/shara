$(".show_modal_job_detach" ).click(function() {
    var id = $(this).attr('alt');
    $('.delete_job').attr('href', '/admin/delete-job/'+id);
        
});