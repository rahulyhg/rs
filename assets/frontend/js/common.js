$(function(){

	$('.alert').find('.close').click(function(){$(this).parent().remove();});

	$( ".datepicker" ).datepicker({dateFormat:'yy-mm-dd',changeMonth: true,changeYear: true});

});