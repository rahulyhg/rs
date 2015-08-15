
<div id="content-wrapper">
<div class="row">
<div class="col-lg-12">
<div class="row">
    <div class="col-lg-12">
	    <div id="content-header" class="clearfix">
		    <div class="pull-left">
			    <ol class="breadcrumb">
			        <li><a href="#">Home</a></li>
			        <li class="active"><span>Dashboard</span></li>
			    </ol>
				<h1>Dashboard</h1>
			</div>
		</div>
	</div>
</div>

<div class="row">

	<div class="col-lg-3 col-sm-6 col-xs-12">
		<div class="main-box infographic-box colored purple-bg">
			<i class="fa fa-globe"></i>
			<span class="headline">Visits</span>
			<span class="value">69,600</span>
		</div>
	</div>
	
	<div class="col-lg-3 col-sm-6 col-xs-12">
		<div class="main-box infographic-box colored blue-bg">
			<i class="fa fa-money"></i>
			<span class="headline">Orders</span>
			<span class="value"><?php echo $home_data['buy_count']; ?></span>
		</div>
	</div>

	<div class="col-lg-3 col-sm-6 col-xs-12">
		<div class="main-box infographic-box colored red-bg">
			<i class="fa fa-user"></i>
			<span class="headline">Users</span>
			<span class="value"><?php echo $home_data['users_count'];?></span>
		</div>
	</div>

	<div class="col-lg-3 col-sm-6 col-xs-12">
		<div class="main-box infographic-box colored red-bg">
			<i class="fa fa-building"></i>
			<span class="headline">Products</span>
			<span class="value"><?php echo $home_data['products_count'];?></span>
		</div>
	</div>

	
</div>


<div class="row">
<div class="col-lg-8">
	<div class="row">
		<div class="col-lg-6">
			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Products Buycount</h2>
				</header>
				<div class="main-box-body clearfix">
					<div id="buycount_chart"> </div>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Products Followed</h2>
				</header>
				<div class="main-box-body clearfix">
					<div id="followed_chart"> </div>
				</div>
			</div>
		</div>

	</div>
	<div class="row">
	<div class="col-lg-6">
			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Product Likes</h2>
				</header>
				<div class="main-box-body clearfix">
					<div id="likes_chart"> </div>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Product Favorites</h2>
				</header>
				<div class="main-box-body clearfix">
					<div id="favorites_chart"> </div>
				</div>
			</div>
		</div>
	</div>

</div>



<div class="col-lg-4">
	<div class="main-box">
		<header class="main-box-header clearfix">
			<h2>Recent Activities</h2>
		</header>
		<div class="main-box-body clearfix">
			<div id="followed_chart222"> 
			LLLL<br/>
			LLLL<br/>
			LLLL<br/>
			LLLL<br/>
			LLLL<br/>
			LLLL<br/>
			LLLL<br/>
			LLLL<br/>
			LLLL<br/>
			LLLL<br/>
			LLLL<br/>
			LLLL<br/>
			LLLL<br/>
			LLLL<br/>
			LLLL<br/>
			LLLL<br/>
			LLLL<br/>
			LLLL<br/>
			</div>
		</div>
	</div>
</div>



</div>

<div class="row">
<div class="col-lg-12 col-xs-12">
	<div class="main-box">
		<header class="main-box-header clearfix">
			<h2>User Charts</h2>
		</header>
		<div class="main-box-body clearfix">
			<div id="user_chart"> </div>
		</div>
	</div>
</div>
</div>

</div>
</div>
