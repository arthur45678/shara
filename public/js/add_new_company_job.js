$(document).ready(function(){
	$('.add-new-job').on('click', function(){
		var categoryId = $('#company-id').val();
		var companyType = $('#company-type').val();
		data = {
				categoryId:categoryId,
				companyType:companyType
		};
		$.ajax({
	        url: '/admin/create-job',
	        type: 'GET',
	        data: data,
	        success: function(data)
	        {
	        	 window.location.replace('/admin/create-job');
	        }
	    })
	})
})