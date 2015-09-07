<div class="banner">
	<div id="container">
		<div id="slides">
			<div class="slides_container">
				<div class="slide">
					<img src="<?php echo include_img_path();?>/banner.png">					
				</div>
				<div class="slide">
					<img src="<?php echo include_img_path();?>/banner2.png">					
				</div>
			</div>
		</div>
	</div>
	<div class="banner_right">
		<span><p>Driving Somewhere ?</p></span>
		<img src="<?php echo include_img_path();?>/b_right_img.png" />
		<a href="<?php echo site_url('offer_seats');?>"><p>Offer a Ride</p></a>
	</div>
	<div class="search">
		<p>Find your ride offered by many drivers across Asia...</p>
		<form id="ride_search" method="post" action="<?php echo site_url('ride_sharing');?>">
			<input type="text" data-type="origin" name="from" id="from" class="input-from autocomplete" title="From" placeholder="From"   />

			<input type="text" data-type="destination" name="to" id="to" class="input-to autocomplete" title="To" placeholder="To"   />
			<input type="text" name="jdate" id="jdate" class="input-date datepicker" title="Date" placeholder="yyyy-mm-dd"  />
			<input type="button"  name="search" id="search" value="Search Now" />

			<input type="hidden" name="origin_name" />
			<input type="hidden" name="origin_latlng" />
			<input type="hidden" name="origin_address" />
			<input type="hidden" name="dest_name" />
			<input type="hidden" name="dest_latlng" />
			<input type="hidden" name="dest_address" />
		</form>
	</div>
</div>

<div class="cont">
	<div class="l_cont">
		<div class="box1">
			<h2>Today Trip</h2>
			<p class="b_right">Bangkok</p>
			<p class="b_left">Chiang Mai</p>
			<span>600 THB</span>

		</div>
		<div class="box2">
			<h2>How it works?</h2>
			<a href=""><img src="<?php echo include_img_path();?>/how_it_works.png" /></a>
		</div>
	</div>
	<div class="r_cont">
		<h1>Connecting people who need to travel with drivers who have empty seats</h1>
		<p><strong>RoadSharing-asia.com</strong> is the easy way to find a carpool match.</p>
		<p>Join our community of members to find carpool partners for your regular commute or a one-off trip. Just register, find a match and share a ride.</p>
		<p>How many people use alone their car for work, study or else, even several times a week?</p>
		<p>RoadSharing-asia.com is an easy way to find someone to share a trip with, is a meeting point between
			those who offer and those who look for a lift and it is the best way to save money, pollute less and 
			meet new friends!</p>
		<p><strong>Carpooling</strong> is cheaper, fun and environmentally friendly.</p>
	</div>
	<div class="bottom_box">
		<div class="bot_box1">
			<h1>Drivers:<br/>
			<span>Reduce travel expenses</span></h1>
			<p>
				Do not travel alone!<br/>
				Save on costs by taking passengers during <br/>
				long car trips or sjust to get to your work <br/>
				every day.
			</p>
			<a href="">Read More..</a>
		</div>
		<div class="bot_box2">
			<h1>Passengers:<br/>
			<span>Traveling cheaper</span></h1>
		</div>
		<div class="bot_box3">
			<h1>Carpooling:<br/>
			<span>Trust & Safety</span></h1>
		</div>
	</div>

</div>

<script type="text/javascript">
$(function(){
	$('input[name="search"]').on('click', function()
	{
		if( $.isEmptyObject(roadShare.origin) )
		{
			alert('Please choose Origin.');
		}
		else if( $.isEmptyObject(roadShare.destination) )
		{
			alert('Please choose Destination.');
		}
		else if( $('#jdate').val() == '' )
		{
			alert('Please select Journey date.');
		}
		else
		{
			$('input[name="origin_name"]').val(roadShare.origin.name);
			$('input[name="origin_latlng"]').val(roadShare.origin.LatLngString);
			$('input[name="origin_address"]').val(roadShare.origin.formatted_address);

			$('input[name="dest_name"]').val(roadShare.destination.name);
			$('input[name="dest_latlng"]').val(roadShare.destination.LatLngString);
			$('input[name="dest_address"]').val(roadShare.destination.formatted_address);

			$("#ride_search").submit();
		}

		//console.log(roadShare.origin);
	});
});

</script>