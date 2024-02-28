$(".show_modal_company" ).click(function() {
    var id = $(this).attr('alt');
    var sub_type = $(this).attr('data-type');
    $('.delete_company').attr('href', '/admin/delete-company/'+id+'/'+sub_type);
        
});