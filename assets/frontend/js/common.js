$(function(){

	$('.alert').find('.close').click(function(){$(this).parent().remove();});

	$( ".datepicker" ).datepicker({dateFormat:'yy-mm-dd',changeMonth: true,changeYear: true});

	$( "#dob" ).datepicker({dateFormat:'yy-mm-dd',changeMonth: true,changeYear: true,yearRange:"c-100:c"});

	$('.datetimepicker').datetimepicker({ minDate:new Date()});
});