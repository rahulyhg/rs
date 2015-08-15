<div class="sep_tab">

	<form action="" method="post" class="basic-grey" style="float:right; margin-top:-12px;">
		<label>
			<input type="submit" class="button" value="LOG IN" />
		</label>   
	</form>
	<h3 style="float:right; margin-top:20px; margin-right:10px;">Already a member?</h3>
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
	<div class="inner_cont">
		<h1>Not a member? Register for free</h1>
		<form action="" method="post" class="basic-grey">
			<label>
				
				<input id="first_name" type="text" name="first_name"  value="<?php echo set_value('first_name');?>" placeholder="Your First Name" />
				<?php echo form_error('first_name', '<span class="error_text">', '</span>'); ?>
			</label>
			<label>
				
				<input id="last_name" type="text" name="last_name"  value="<?php echo set_value('last_name');?>" placeholder="Your Last Name"/>
				<?php echo form_error('last_name', '<span class="error_text">', '</span>'); ?>
			</label>
			
			<label>
				
				<input id="email" type="text" name="email" value="<?php echo set_value('email');?>" placeholder="Your Email Address" />
				<?php echo form_error('email', '<span class="error_text">', '</span>'); ?>
			</label>
			<label>
				
				<input id="password" type="password" name="password" placeholder="Password" />
				<?php echo form_error('password', '<span class="error_text">', '</span>'); ?>
			</label>
			
			<label>
				
				<input id="confirm_password" type="password" name="confirm_password" placeholder="Confirm Password" /> 
				<?php echo form_error('confirm_password', '<span class="error_text">', '</span>'); ?>
			</label>
			
			<label>
				
				<input id="dob" type="text" class="datepicker" name="dob" value="<?php echo set_value('dob');?>" placeholder="Date Of Birth" /> 
				<?php echo form_error('dob', '<span class="error_text">', '</span>'); ?>
			</label>
			
			<label>
				<input type="checkbox" name="agree" id="agree" value="1" checked /><p>Receive information about new features and other useful information.</p>
				<?php echo form_error('agree', '<span class="error_text">', '</span>'); ?>
			</label>
			
			<label>
				<input type="submit" class="button" value="REGISTER" />
			</label>    
		</form>

	</div>

</div>