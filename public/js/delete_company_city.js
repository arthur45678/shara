$(".show_modal_company_city" ).click(function() {
    var id = $(this).attr('alt');
    $('.delete_company').attr('href', '/admin/delete-company-city/'+id);
        
});