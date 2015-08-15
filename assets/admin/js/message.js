$(document).ready(function() {

	if(msg_type=='users'){
		$("#autosuggest").show();
	}
			
			
	$("#type2").click(function () {
		$("#autosuggest").show();
	});
		
	$("#type1").click(function () { 
		$("#autosuggest").hide();
	});
	

	var url = base_url+"admin/message/auto_complete";
			
	var pp = [];
    if(prepoulate)
    {
    	pp = {prePopulate:prepoulate};
    }
		
	
				 
		$("#search_user").tokenInput(url,pp);
		   
		$("#search_user").keyup(function(){
        
			$.ajax({
				type:'POST',
				url:url,
				data: {search_key:$("#search_user").val()},
				cache:false,
				async:true,
				global:false,
				dataType:"json",
				success:function(check)
				{ //alert(check);
				  $("#search_user").tokenInput(check);
				}
			});

		});

});
