
google.load('visualization', '1', {packages: ['corechart','controls']});

google.setOnLoadCallback(productChart);
google.setOnLoadCallback(userChart);

	function productChart(){

		$.ajax({
			url: base_url+"admin/dashboard/product_chart",
			type : "POST",
			data:{},
			dataType:"json",
			success : function(data) {
				
				if(data.status == 'success')
				{
	                $.each(data.records, function( index, value ){

						var rep_data = new google.visualization.arrayToDataTable(value);

						var options = {
						  title: index.toUpperCase(),
						  fontSize:12,
						  //width:490,
						  height:200,
						  legend: '',
						  vAxis: {title: 'PRODUCTS'}
						};

						var chart = new google.visualization.ColumnChart(document.getElementById(index+'_chart'));
						chart.draw(rep_data, options);

					});

				}
				else
				{
					$('#buycount_chart,#likes_chart,#followed_chart,#favorites_chart').html("<div class='no_records'>"+data.message+"</div>");
				}
				
			},
			error : function(data) {
				$('#buycount_chart,#likes_chart,#followed_chart,#favorites_chart').html("<div class='no_records'>Failed to load graphs</div>");
			}
		});
	}

	function userChart(){

		$.ajax({
			url: base_url+"admin/dashboard/user_chart",
			type : "POST",
			data:{},
			dataType:"json",
			success : function(data) {
				
				if(data.status == 'success')
				{
	               
						var rep_data = new google.visualization.arrayToDataTable(data.records);

						var options = {
						  title: 'Range',
						  fontSize:12,
						  height:200,
						  legend: '',
						  vAxis: {title: 'Total'}
						};

						var chart = new google.visualization.BarChart(document.getElementById('user_chart'));
						chart.draw(rep_data, options);


				}
				else
				{
					$('#user_chart').html("<div class='no_records'>"+data.message+"</div>");
				}
				
			},
			error : function(data) {
				$('#user_chart').html("<div class='no_records'>Failed to load graphs</div>");
			}
		});
	}	

