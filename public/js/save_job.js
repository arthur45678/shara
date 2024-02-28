$(document).ready(function(){
	$(document).on('click','.save',function(){
		var oldValue = $('.old_restriction').val();
		console.log(oldValue);
		if(oldValue !== undefined){
			$('.is_published').val(oldValue); 
		}else{
			$('.is_published').val(1); 
		}
		
	})

	$(document).on('click', '.publish', function(){
		$('.is_published').val(''); 
	});

	$(document).on('click', '.unpublish', function(){
		$('.is_published').val('true'); 
	});
})