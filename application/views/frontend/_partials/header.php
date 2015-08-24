
<div id="header">
	<div class="logo">
		<a href="<?php echo site_url('');?>"><img src="<?php echo include_img_path();?>/logo.jpg" /></a>
	</div>
	<div class="nav">
		<ul>
			<li><a href="<?php echo site_url();?>">Home</a></li>
			<li><a href="<?php echo site_url('how-it-works');?>">How It Works</a></li>
			<li><a href="<?php echo site_url('faq');?>">FAQ</a></li>
			<li><a href="<?php echo site_url('join-now');?>">Join Now</a></li>
			<?php if( !is_logged_in() ):?>
				<li><a href="<?php echo site_url('login');?>">Login</a></li>
			<?php else:?>
				<li><a href="<?php echo site_url('logout');?>">Logout</a></li>
			<?php endif;?>
		</ul>
		<div class="nav_right">
			<img src="<?php echo include_img_path();?>/right.jpg" />
		</div>
	</div>
</div>
<div class="clear"></div>
