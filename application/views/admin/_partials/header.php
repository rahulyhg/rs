

<header class="navbar" id="header-navbar">
<div class="container">
    <a href="<?php echo site_url('admin/dashboard'); ?>" id="logo" class="navbar-brand">TJW
        <img src="img/logo-black.png" alt="" class="normal-logo logo-black"/>
        <img src="img/logo-small.png" alt="" class="small-logo hidden-xs hidden-sm hidden"/>
    </a>
    <div class="clearfix">
        <button class="navbar-toggle" data-target=".navbar-ex1-collapse" data-toggle="collapse" type="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="fa fa-bars"></span>
        </button>
<div class="nav-no-collapse navbar-left pull-left hidden-sm hidden-xs">
	<ul class="nav navbar-nav pull-left">
		<li style="margin-left: -50px;">
			<a class="btn" id="make-small-nav" title="Hide Menus">
				<i class="fa fa-bars"></i>
			</a>
		</li>
	</ul>
</div>

<div class="nav-no-collapse pull-right" id="header-nav">
<ul class="nav navbar-nav pull-right">

<!--
<li class="dropdown hidden-xs">
	<a class="btn dropdown-toggle" data-toggle="dropdown">
		<i class="fa fa-bell"></i>
		<span class="count">8</span>
	</a>

	<ul class="dropdown-menu notifications-list">
		<li class="pointer">
		<div class="pointer-inner">
		<div class="arrow"></div>
		</div>
		</li>
		<li class="item-header">You have 6 new notifications</li>
		<li class="item">
		<a href="#">
		<i class="fa fa-comment"></i>
		<span class="content">New comment on ‘Awesome P...</span>
		<span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
		</a>
		</li>
		<li class="item">
		<a href="#">
		<i class="fa fa-plus"></i>
		<span class="content">New user registration</span>
		<span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
		</a>
		</li>
		<li class="item">
		<a href="#">
		<i class="fa fa-envelope"></i>
		<span class="content">New Message from George</span>
		<span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
		</a>
		</li>
		<li class="item">
		<a href="#">
		<i class="fa fa-shopping-cart"></i>
		<span class="content">New purchase</span>
		<span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
		</a>
		</li>
		<li class="item">
		<a href="#">
		<i class="fa fa-eye"></i>
		<span class="content">New order</span>
		<span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
		</a>
		</li>
		<li class="item-footer">
		<a href="#">
		View all notifications
		</a>
		</li>
	</ul>
</li>
-->

<li class="dropdown profile-dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		<img src="img/samples/scarlet-159.png" alt=""/>
		<?php $user_data = $this->session->userdata('admin_user_data'); ?>
		<span class="hidden-xs"><b><?php echo ucfirst($user_data['first_name']." ".$user_data['last_name']);  ?></b></span> <b class="caret"></b>
	</a>
	<ul class="dropdown-menu">
		<li><a href="#"><i class="fa fa-user"></i>Profile</a></li>
		<li><a href="<?php echo site_url('admin/login/logout'); ?>"><i class="fa fa-power-off"></i>Logout</a></li>
	</ul>
</li>

<!--
<li class="dropdown language hidden-xs">
	<a class="btn dropdown-toggle" data-toggle="dropdown">
		English
		<i class="fa fa-caret-down"></i>
	</a>
	<ul class="dropdown-menu">
		<li class="item">
			<a href="#">
			Spanish
			</a>
		</li>
		<li class="item">
			<a href="#">
			German
			</a>
		</li>
		<li class="item">
			<a href="#">
			Italian
			</a>
		</li>
	</ul>
</li>
-->
</ul>
</div>


</div>
</div>
</header>
