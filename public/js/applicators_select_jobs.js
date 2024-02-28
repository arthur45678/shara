$(function(){



	$(document).on('change', '#company_applicants', function(){
		var company = $('#company_applicants option:selected').val();
		if (!company) {
			return;
		}
		$('#job_applicants').html('');
		$.ajax({
			url: '/admin/get-company-jobs/'+company,
			method: 'GET',
			data: '',
			success:function(data)
			{
				var jobs = data.jobs;
				var filteredJob = $('#filteredJob');
				if(jobs)
				{
					var job_option = document.createElement("option");
					var job_text = document.createTextNode('Select Job');
					job_option.appendChild(job_text);
					job_option.setAttribute("value", '');
					var select = $('#job_applicants');
					select.append(job_option);
					jobs.forEach(function(job, key, jobs)
					{
						var job_option = document.createElement("option");
						var job_text = document.createTextNode(job.name);
						job_option.appendChild(job_text);
						job_option.setAttribute("value", job.id);
						if(filteredJob == job.id) {
							job_option.setAttribute('selected', 'selected');
						}
						var select = $('#job_applicants');
						select.append(job_option);
					});
					
				}
			}
		})	
	})
	$(document).ready(function() {
		var company = $('#company_applicants option:selected').val();
		if (!company) {
			return;
		}
		$('#job_applicants').html('');
		$.ajax({
			url: '/admin/get-company-jobs/'+company,
			method: 'GET',
			data: '',
			success:function(data)
			{
				var jobs = data.jobs;
				var filteredJob = $('#filteredJob').val();
				console.log(filteredJob)
				if(jobs)
				{
					var job_option = document.createElement("option");
					var job_text = document.createTextNode('Select Job');
					job_option.appendChild(job_text);
					job_option.setAttribute("value", '');
					var select = $('#job_applicants');
					select.append(job_option);
					jobs.forEach(function(job, key, jobs)
					{
						var job_option = document.createElement("option");
						var job_text = document.createTextNode(job.name);
						job_option.appendChild(job_text);
						job_option.setAttribute("value", job.id);
						if(filteredJob == job.id) {
							job_option.setAttribute('selected', 'selected');
						}
						var select = $('#job_applicants');
						select.append(job_option);
					});
					
				}
			}
		})
	})
	

})