<?php echo $tmenu;?>

<div class="clear"></div>

<div class="cont">
	<div class="l_cont offer-seat">
		<div style="margin:0 0 0 10px;">
			<h1>Offer a ride on your next long journey</h1>
		</div>

		<div style="margin:0 0 0 10px;">
			<!-- multistep form -->
			<form id="msform">
				<!-- progressbar -->
				<ul id="progressbar">
					<li class="active">Schedule</li>
					<li>Price</li>
				</ul>


				<!-- fieldsets -->
				<fieldset id="fld_schdule">

					<label>Departure</label>
					<input type="text" data-type="origin" class="autocomplete" name="from" placeholder="Your departure point (address, city, station...)" />
					<label>Arrival</label>
					<input type="text" data-type="destination" class="autocomplete" name="to" placeholder="Your arrival point (address, city, station...)" />
					
					<div id="stopovers">
						<label>Stopovers</label>
						<p>Now enter the main cities you will drive through: this is key to connecting you and your co-travellers</p>
						<input type="text" data-type="waypoint" class="autocomplete" name="stopover[]" placeholder="Add cities on your route" />
					</div>	
					
					<a href="javascript:;" id="add_stopovers">Add cities on your route</a>

					<div class="clear"></div>

					<div id="datetime">
  
					   <div>
		               		<div style="float:left"><h2>Date and Time </h2> </div>
		               		<div style="float:right;margin-top:17px;"> <input type="checkbox" name="round_trip" id="round_trip" value="round_trip"> Round Trip</div>
		            	</div>
		            	
		            	<div class="clear"></div>

		            	<div id="div_dep_date">
		            		<label>Departure date:</label>
			            	<input type="text" class="datetimepicker" name="dep_date">
					   	</div>
					   	<div id="div_ret_date">
					   		<label>Return date:</label>
						   	<input type="text" class="datetimepicker" name="ret_date">
						</div>
					</div>

					<div class="clear"></div>
					<div style="float:right;">
						<input type="button" name="next" class="next action-button" value="Continue" />
					</div>
				</fieldset>
				<fieldset id="fld_price">
					<div id="price-details">

					</div>
					<div class="clear"></div>
					<div>
						<div style="float:left;width=60%">Number of seats offered:</div>
						<div style="float:right;width=30%;"><input type="text" name="seat_count" value="3" style="width:80%;padding:3px;" />
					</div>
					<div class="clear"></div>
					<div>
						<h2>Ride Details</h2>
						<p>Please add further details about your ride - it'll save you answering lots of questions from co-travellers.
						</p>
						<textarea name="ride_details" id="ride_details"></textarea>
						<p> Please do not add your contact details here. Interested co-travellers will receive your phone number individually (See our rules) 
						</p>

					

					</div>
					<div class="clear"></div>
					<div>
						<div style="float:left;width=60%">Maximum luggage size:</div>
						<div style="float:right;width=30%"> 
							<select name="luggage" id="luggage">
								<option value="SMALL">Small</option>
								<option selected="selected" value="MIDDLE">Medium</option>
								<option value="BIG">Big</option>
							</select>
						 </div>
					</div>
					<div class="clear"></div>
					<div>
						<div style="float:left;width=60%">I will leave:</div>
						<div style="float:right;width=30%">
						<select name="schedule_flexibility" id="schedule_flexibility">
							<option selected="selected" value="ON_TIME">Right on time</option>
							<option value="FIFTEEN_MINUTES">In a 15 minute window</option>
							<option value="THIRTY_MINUTES">In a 30 minute window</option>
							<option value="ONE_HOUR">In a 1 hour window</option>
							<option value="TWO_HOURS">In a 2 hour window</option>
						</select>
					 	</div> 
					</div>
					<div class="clear"></div>
					<div>
						<div style="float:left;width=60%;">I can make a detour:</div>
						<div style="float:right;width=30%"> 
							<select name="detour_flexibility" id="detour_flexibility">
								<option value="NONE">I'm not willing to make a detour</option>
								<option selected="selected" value="FIFTEEN_MINUTES">15 minute detour max.</option>
								<option value="THIRTY_MINUTES">30 minute detour max.</option>
								<option value="WHATEVER_IT_TAKES">Anything is fine</option>
							</select>
						</div>
					</div>
					<div class="clear"></div>
					<div>
						<div style="float:left;">
							<input type="checkbox" name="tc" id="tc" />
						</div>
						<p>
							I have read and accept the T&Cs, and certify that I am over 18 years old, hold a driving licence and have valid car insurance.
						</p>
					</div>
					<div class="clear"></div>
					<div>
						<div style="float:left;width=40%;">
							<input type="button" name="previous" class="previous action-button" value="Previous" />
						</div>
						<div style="float:right;width=40%;">
							<input type="button" name="next" class="submit-btn action-button" value="Publish" />
						</div>
					</div>
				</fieldset>
			</form>

		</div>
		

		

		
	</div>
	<div class="r_cont offer-seat">
		<h2>My Trip Summary</h2>
		<div id="map"></div>
		<div id="warnings_panel" style="width:100%;height:10%;text-align:center"></div>
		<div id="directions_panel" style="width:100%;height:10%;text-align:center"></div>
		<div id="total_distance" style="width:100%;height:10%;text-align:center"></div>
	</div>
	

</div>

<script src="http://thecodeplayer.com/uploads/js/jquery.easing.min.js" type="text/javascript"></script>
<script type="text/javascript">
	

	//jQuery time
var current_fs, next_fs, previous_fs; //fieldsets
var left, opacity, scale; //fieldset properties which we will animate
var animating; //flag to prevent quick multi-click glitches

$(".next").click(function(){

	if(animating) return false;
	animating = true;
	
	current_fs = $(this).parents('fieldset');
	next_fs = $(this).parents('fieldset').next();

	console.log(roadShare.origin);
	console.log(roadShare.destination);
	console.log($.isEmptyObject(roadShare.origin));
	/*if( $.isEmptyObject(roadShare.origin) )
	{
		alert('Please Select Departure .');
		animating = false;
		return false;
	}

	if( $.isEmptyObject(roadShare.destination) )
	{
		alert('Please Select Arrival .');
		animating = false;
		return false;
	}
	
	if( $('input[name="dep_date"]').val() == '' )
	{
		alert('Please Select Departure date .');
		animating = false;
		return false;
	}
	*/
	if( $('#round_trip').prop('checked') && $('input[name="ret_date"]').val() == '' )
	{
		alert('Please Select Return date .');
		animating = false;
		return false;
	}

	//activate next step on progressbar using the index of next_fs
	$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
	
	//populate data

	var $elm = $("#price-details"),
	    $html = '';
		$elm.html($html);

	$html = '<h2>Price per co-traveller</h2>';
	$input_style = 'style="width:80%;padding:3px;"';
	for(var i=0;i<roadShare.route_details.length;i++)
	{
		var from = roadShare.route_details[i].from,
			to = roadShare.route_details[i].to,
			kms = roadShare.route_details[i].totalDist/1000,
			duration = (roadShare.route_details[i].totalTime/60).toFixed(2);			

			$html += '<div>';
			$html += '<div style="float:left;width=60%">'+from+' to '+to+'</div>';
			$html += '<div style="float:right;width=30%">$<input type="text" value="'+(kms*2)+'" '+$input_style+' /></div>';
			$html += '</div>';
			$html += '<div class="clear"></div>';

	}

	if( roadShare.route_details.length > 1 )
	{
		$html += '<hr>';

		var from = roadShare.origin.name,
			to = roadShare.destination.name,
			kms = roadShare.totalDist,
			duration = (roadShare.totalTime/60).toFixed(2);			

			$html += '<div>';
			$html += '<div style="float:left;width=60%">'+from+' to '+to+'</div>';
			$html += '<div style="float:right;width=30%">$<input type="text" value="'+(kms*2)+'" '+$input_style+' /></div>';
			$html += '</div>';
			$html += '<div class="clear"></div>';

	}

	$elm.html($html);
	//show the next fieldset
	next_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale current_fs down to 80%
			scale = 1 - (1 - now) * 0.2;
			//2. bring next_fs from the right(50%)
			left = (now * 50)+"%";
			//3. increase opacity of next_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({'transform': 'scale('+scale+')'});
			next_fs.css({'left': left, 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
});

$(".previous").click(function(){
	if(animating) return false;
	animating = true;
	
	current_fs = $(this).parents('fieldset');
	previous_fs = $(this).parents('fieldset').prev();
	
	//de-activate current step on progressbar
	$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
	
	//show the previous fieldset
	previous_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale previous_fs from 80% to 100%
			scale = 0.8 + (1 - now) * 0.2;
			//2. take current_fs to the right(50%) - from 0%
			left = ((1-now) * 50)+"%";
			//3. increase opacity of previous_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({'left': left});
			previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
});

$(".submit").click(function(){
	return false;
})

$("#round_trip").bind('change', function(){
	if(this.checked)
		$('#div_ret_date').css('display', 'block');
	else
		$('#div_ret_date').css('display', 'none');
});

$("#round_trip").trigger('change');

$('.submit-btn').on('click', function(){

	
	if( $('#ride_details').val() == '' )
	{
		alert( 'Please enter ride details. ');
		return false;	
	}
	if( !$('input[name="tc"]').prop('checked') )
	{
		alert( 'Please read and accept the T&Cs. ');
		return false;
	}

	var $data = {};	

	$data.origin_name = roadShare.origin.name;
	$data.origin_latlng = roadShare.origin.LatLngString;
	$data.origin_address = roadShare.origin.formatted_address;

	$data.dest_name = roadShare.destination.name;
	$data.dest_latlng = roadShare.destination.LatLngString;
	$data.dest_address = roadShare.destination.formatted_address;

	$data.ride_type 	= $('#round_trip').prop('checked')?'up_down':'up';
	$data.dep_date 		= convertToGMT( $('input[name="dep_date"]').val() );
	$data.ret_date 		= convertToGMT( $('input[name="ret_date"]').val() );

	$data.waypoints = [];
	for(var i=0;i<roadShare.waypoints.length; i++)
	{
		var tmp = {};
			tmp.name = roadShare.waypoints[i].name;
			tmp.address = roadShare.waypoints[i].formatted_address;
			tmp.latlng = roadShare.waypoints[i].LatLngString;

		$data.waypoints.push(tmp);
	}

	$data.total_dist 			= roadShare.totalDist;
	$data.total_time 			= roadShare.totalTime;
	$data.seat_count 			= $('input[name="seat_count"]').val();
	$data.ride_details 			= $('[name="ride_details"]').val();
	$data.luggage 				= $('select[name="luggage"]').val();
	$data.schedule_flexibility 	= $('select[name="schedule_flexibility"]').val();
	$data.detour_flexibility 	= $('select[name="detour_flexibility"]').val();

	//console.log($data);
	$.ajax({
		url:site_url+'/offer_seats',
		method :'POST',
		data:$data,
		dataType :'json',
		success:function(resp){
			if( resp.status == 'success' )
			{
				alert(resp.message);
			}
			else if(resp.message == 'Your session is expired.')
			{
				alert(resp.message);
				location.href = site_url+'login';
			}
			else
			{
				alert(resp.message);
			}

		}
	});
});


function convertToGMT( date_time )
{
	var d = new Date( date_time ),
		utc_month = d.getUTCMonth()+1,
	    utc_month = utc_month>9?utc_month:'0'+utc_month,
	    utc_date = d.getUTCDate()>9?d.getUTCDate():'0'+d.getUTCDate();
	return d.getUTCFullYear()+'-'+(utc_month)+'-'+utc_date+' '+d.getUTCHours()+':'+d.getUTCMinutes();
}

</script>