$(document).ready(function(){
console.log(1);
	$('input#textfield').on('keyup',function(){
	   var charCount = $(this).val().replace(/\s/g, '').length;
		$(".result").text(charCount + " chars");
	});


	//JOB FIELDS CHAR COUNTS

	//Job:description
	if($('textarea#job-desc').val())
	{
		var oldJObDesc = $('textarea#job-desc').val().length;
		$(".job-desc-result").text(oldJObDesc + " chars");
	}
	
	$('textarea#job-desc').on('keyup',function(){
	   var charCount = $(this).val().length;
		$(".job-desc-result").text(charCount + " chars");
	});

	//Job:about company
	if($('textarea#about-company').val())
	{
		var oldAboutComp = $('textarea#about-company').val().length;
		$(".about-comp-result").text(oldAboutComp + " chars");
	}
	
	$('textarea#about-company').on('keyup',function(){
	   var charCount = $(this).val().length;
		$(".about-comp-result").text(charCount + " chars");
	});

	//Job:requirements
	if($('textarea#job-requirements').val())
	{
		var oldRequiremets = $('textarea#job-requirements').val().length;
		$(".job-requirements-result").text(oldRequiremets + " chars");
	}
	
	$('textarea#job-requirements').on('keyup',function(){
	   var charCount = $(this).val().length;
		$(".job-requirements-result").text(charCount + " chars");
	});

	//Job:why us
	if($('textarea#why-us').val())
	{
		var oldAboutComp = $('textarea#why-us').val().length;
		$(".why-us-result").text(oldAboutComp + " chars");
	}
	
	$('textarea#why-us').on('keyup',function(){
	   var charCount = $(this).val().length;
		$(".why-us-result").text(charCount + " chars");
	});

	//Job:benefits
	if($('textarea#job-benefits').val())
	{
		var oldRequiremets = $('textarea#job-benefits').val().length;
		$(".job-benefits-result").text(oldRequiremets + " chars");
	}
	
	$('textarea#job-benefits').on('keyup',function(){
	   var charCount = $(this).val().length;
		$(".job-benefits-result").text(charCount + " chars");
	});


	//COMPANY FIELDS CHAR COUNTS

	//Company:description
	if($('textarea#company-desc').val())
	{
		var oldCompanyDesc = $('textarea#company-desc').val().length;
		$(".company-desc-result").text(oldCompanyDesc + " chars");
	}
	
	$('textarea#company-desc').on('keyup',function(){
	   var charCount = $(this).val().length;
		$(".company-desc-result").text(charCount + " chars");
	});

	//Company:looking for
	if($('textarea#looking-for').val())
	{
		var oldLookingFor = $('textarea#looking-for').val().length;
		$(".looking-for-result").text(oldLookingFor + " chars");
	}
	
	$('textarea#looking-for').on('keyup',function(){
	   var charCount = $(this).val().length;
		$(".looking-for-result").text(charCount + " chars");
	});

	//Company:requirements
	if($('textarea#company-requirements').val())
	{
		var oldCompRequiremets = $('textarea#company-requirements').val().length;
		$(".company-requirements-result").text(oldCompRequiremets + " chars");
	}
	
	$('textarea#company-requirements').on('keyup',function(){
	   var charCount = $(this).val().length;
		$(".company-requirements-result").text(charCount + " chars");
	});

	//Company:short description
	if($('textarea#short-description').val())
	{
		var oldLookingFor = $('textarea#short-description').val().length;
		$(".short-description-result").text(oldLookingFor + " chars");
	}
	
	$('textarea#short-description').on('keyup',function(){
	   var charCount = $(this).val().length;
		$(".short-description-result").text(charCount + " chars");
	});

	//Company:why us
	if($('textarea#comp-why-us').val())
	{
		var oldCompRequiremets = $('textarea#comp-why-us').val().length;
		$(".comp-why-us-result").text(oldCompRequiremets + " chars");
	}
	
	$('textarea#comp-why-us').on('keyup',function(){
	   var charCount = $(this).val().length;
		$(".comp-why-us-result").text(charCount + " chars");
	});


    $('#company-desc').keydown(
        function(event){
          
         if (event.which == '13') {
            document.getElementById("company-desc").value =document.getElementById("company-desc").value + "\n";
            return false;
          }
    });

    $('#job-desc').keydown(
        function(event){
          
         if (event.which == '13') {
            document.getElementById("job-desc").value =document.getElementById("job-desc").value + "\n";
            return false;
          }
    });

    $('#job-requirements').keydown(
        function(event){
          
         if (event.which == '13') {
            document.getElementById("job-requirements").value =document.getElementById("job-requirements").value + "\n";
            return false;
          }
    });

    $('#why-us').keydown(
        function(event){
          
         if (event.which == '13') {
            document.getElementById("why-us").value = document.getElementById("why-us").value + "\n";
            return false;
          }
    });

    $('#job-benefits').keydown(
        function(event){
          
         if (event.which == '13') {
            document.getElementById("job-benefits").value =document.getElementById("job-benefits").value + "\n";
            return false;
          }
    });
});
