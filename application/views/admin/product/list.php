<div id="content-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-lg-12">
					<ol class="breadcrumb">
						<li><a href="#">Home</a>
						</li>
						<li class="active"><span>Products</span>
						</li>
					</ol>
					<div class="clearfix">
						<h1 class="pull-left">Products</h1>
						<div class="pull-right top-page-ui">
							<a href="<?php echo site_url('admin/products/add');?>"> <i
								class="fa fa-plus-circle fa-lg"></i> Add Product
							</a>
						</div>
					</div>
				</div>
			</div>
			
			<?php echo $grid;?>
			<?php //echo '<pre>';print_r($this->session->all_userdata());die;?>
		</div>
	</div>




<!--

<div id="content-wrapper"><div class="row">
<div class="col-lg-12">
<div class="row">
<div class="col-lg-12">
<ol class="breadcrumb">
<li><a href="#">Home</a></li>
<li class="active"><span>Users</span></li>
</ol>
<div class="clearfix">
<h1 class="pull-left">Users</h1>
<div class="pull-right top-page-ui">
<button class="md-trigger btn btn-primary mrg-b-lg" onclick="window.location.href='products/add'">
<i class="fa fa-plus-circle fa-lg"></i> Add Product
</button>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-lg-12">
<div class="main-box no-header clearfix">
<div class="main-box-body clearfix">
<div class="table-responsive">
<table class="table user-list table-hover">
<thead>
<tr>
<th><span>User</span></th>
<th><span>Created</span></th>
<th class="text-center"><span>Status</span></th>
<th><span>Email</span></th>
<th>&nbsp;</th>
</tr>
</thead>
<tbody>
<tr>
<td>
<img src="<?php echo base_url();?>assets/admin/img/samples/scarlet-159.png" alt=""/>
<a href="#" class="user-link">Jennifer Lawrence</a>
<span class="user-subhead">Admin</span>
</td>
<td>
2013/08/08
</td>
<td class="text-center">
<span class="label label-default">Inactive</span>
</td>
<td>
<a href="#"><span class="__cf_email__" data-cfemail="05686c6964456e706b6c762b666a68">[email&#160;protected]</span><script cf-hash='f9e31' type="text/javascript">
/* <![CDATA[ */!function(){try{var t="currentScript"in document?document.currentScript:function(){for(var t=document.getElementsByTagName("script"),e=t.length;e--;)if(t[e].getAttribute("cf-hash"))return t[e]}();if(t&&t.previousSibling){var e,r,n,i,c=t.previousSibling,a=c.getAttribute("data-cfemail");if(a){for(e="",r=parseInt(a.substr(0,2),16),n=2;a.length-n;n+=2)i=parseInt(a.substr(n,2),16)^r,e+=String.fromCharCode(i);e=document.createTextNode(e),c.parentNode.replaceChild(e,c)}}}catch(u){}}();/* ]]> */</script></a>
</td>
<td style="width: 20%;">
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link danger">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
</span>
</a>
</td>
</tr>
<tr>
<td>
<img src="<?php echo base_url();?>assets/admin/img/samples/robert-300.jpg" alt=""/>
<a href="#" class="user-link">Robert Downey Jr.</a>
<span class="user-subhead">Member</span>
</td>
<td>
2013/08/12
</td>
<td class="text-center">
<span class="label label-success">Active</span>
</td>
<td>
<a href="#"><span class="__cf_email__" data-cfemail="3b565a495754557b59495a555f5415585456">[email&#160;protected]</span><script cf-hash='f9e31' type="text/javascript">
/* <![CDATA[ */!function(){try{var t="currentScript"in document?document.currentScript:function(){for(var t=document.getElementsByTagName("script"),e=t.length;e--;)if(t[e].getAttribute("cf-hash"))return t[e]}();if(t&&t.previousSibling){var e,r,n,i,c=t.previousSibling,a=c.getAttribute("data-cfemail");if(a){for(e="",r=parseInt(a.substr(0,2),16),n=2;a.length-n;n+=2)i=parseInt(a.substr(n,2),16)^r,e+=String.fromCharCode(i);e=document.createTextNode(e),c.parentNode.replaceChild(e,c)}}}catch(u){}}();/* ]]> */</script></a>
</td>
<td style="width: 20%;">
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link danger">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
</span>
</a>
</td>
</tr>
<tr>
<td>
<img src="<?php echo base_url();?>assets/admin/img/samples/ryan-300.jpg" alt=""/>
<a href="#" class="user-link">Ryan Gossling</a>
<span class="user-subhead">Registered</span>
</td>
<td>
2013/03/03
</td>
<td class="text-center">
<span class="label label-danger">Banned</span>
</td>
<td>
<a href="#">jack@nicholson</a>
</td>
<td style="width: 20%;">
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link danger">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
</span>
</a>
</td>
</tr>
<tr>
<td>
<img src="<?php echo base_url();?>assets/admin/img/samples/emma-300.jpg" alt=""/>
<a href="#" class="user-link">Emma Watson</a>
<span class="user-subhead">Registered</span>
</td>
<td>
2004/01/24
</td>
<td class="text-center">
<span class="label label-warning">Pending</span>
</td>
<td>
<a href="#"><span class="__cf_email__" data-cfemail="7d1508100d150f18043d1f121a1c0f09531e1210">[email&#160;protected]</span><script cf-hash='f9e31' type="text/javascript">
/* <![CDATA[ */!function(){try{var t="currentScript"in document?document.currentScript:function(){for(var t=document.getElementsByTagName("script"),e=t.length;e--;)if(t[e].getAttribute("cf-hash"))return t[e]}();if(t&&t.previousSibling){var e,r,n,i,c=t.previousSibling,a=c.getAttribute("data-cfemail");if(a){for(e="",r=parseInt(a.substr(0,2),16),n=2;a.length-n;n+=2)i=parseInt(a.substr(n,2),16)^r,e+=String.fromCharCode(i);e=document.createTextNode(e),c.parentNode.replaceChild(e,c)}}}catch(u){}}();/* ]]> */</script></a>
</td>
<td style="width: 20%;">
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link danger">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
</span>
</a>
</td>
</tr>
<tr>
<td>
<img src="<?php echo base_url();?>assets/admin/img/samples/robert-300.jpg" alt=""/>
<a href="#" class="user-link">Robert Downey Jr.</a>
<span class="user-subhead">Admin</span>
</td>
<td>
2013/12/31
</td>
<td class="text-center">
<span class="label label-success">Active</span>
</td>
<td>
<a href="#">spencer@tracy</a>
</td>
<td style="width: 20%;">
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link danger">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
</span>
</a>
</td>
</tr>
<tr>
<td>
<img src="<?php echo base_url();?>assets/admin/img/samples/kunis-300.jpg" alt=""/>
<a href="#" class="user-link">Mila Kunis</a>
<span class="user-subhead">Admin</span>
</td>
<td>
2013/08/08
</td>
<td class="text-center">
<span class="label label-default">Inactive</span>
</td>
<td>
<a href="#"><span class="__cf_email__" data-cfemail="b8d5d1d4d9f8d3cdd6d1cb96dbd7d5">[email&#160;protected]</span><script cf-hash='f9e31' type="text/javascript">
/* <![CDATA[ */!function(){try{var t="currentScript"in document?document.currentScript:function(){for(var t=document.getElementsByTagName("script"),e=t.length;e--;)if(t[e].getAttribute("cf-hash"))return t[e]}();if(t&&t.previousSibling){var e,r,n,i,c=t.previousSibling,a=c.getAttribute("data-cfemail");if(a){for(e="",r=parseInt(a.substr(0,2),16),n=2;a.length-n;n+=2)i=parseInt(a.substr(n,2),16)^r,e+=String.fromCharCode(i);e=document.createTextNode(e),c.parentNode.replaceChild(e,c)}}}catch(u){}}();/* ]]> */</script></a>
</td>
<td style="width: 20%;">
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link danger">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
</span>
</a>
</td>
</tr>
<tr>
<td>
<img src="<?php echo base_url();?>assets/admin/img/samples/lima-300.jpg" alt=""/>
<a href="#" class="user-link">George Clooney</a>
<span class="user-subhead">Member</span>
</td>
<td>
2013/08/12
</td>
<td class="text-center">
<span class="label label-success">Active</span>
</td>
<td>
<a href="#"><span class="__cf_email__" data-cfemail="b1dcd0c3dddedff1d3c3d0dfd5de9fd2dedc">[email&#160;protected]</span><script cf-hash='f9e31' type="text/javascript">
/* <![CDATA[ */!function(){try{var t="currentScript"in document?document.currentScript:function(){for(var t=document.getElementsByTagName("script"),e=t.length;e--;)if(t[e].getAttribute("cf-hash"))return t[e]}();if(t&&t.previousSibling){var e,r,n,i,c=t.previousSibling,a=c.getAttribute("data-cfemail");if(a){for(e="",r=parseInt(a.substr(0,2),16),n=2;a.length-n;n+=2)i=parseInt(a.substr(n,2),16)^r,e+=String.fromCharCode(i);e=document.createTextNode(e),c.parentNode.replaceChild(e,c)}}}catch(u){}}();/* ]]> */</script></a>
</td>
<td style="width: 20%;">
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link danger">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
</span>
</a>
</td>
</tr>
<tr>
<td>
<img src="<?php echo base_url();?>assets/admin/img/samples/ryan-300.jpg" alt=""/>
<a href="#" class="user-link">Ryan Gossling</a>
<span class="user-subhead">Registered</span>
</td>
<td>
2013/03/03
</td>
<td class="text-center">
<span class="label label-danger">Banned</span>
</td>
<td>
<a href="#">jack@nicholson</a>
</td>
<td style="width: 20%;">
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link danger">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
</span>
</a>
</td>
</tr>
<tr>
<td>
<img src="<?php echo base_url();?>assets/admin/img/samples/emma-300.jpg" alt=""/>
<a href="#" class="user-link">Emma Watson</a>
<span class="user-subhead">Registered</span>
</td>
<td>
2004/01/24
</td>
<td class="text-center">
<span class="label label-warning">Pending</span>
</td>
<td>
<a href="#"><span class="__cf_email__" data-cfemail="5d3528302d352f38241d3f323a3c2f29733e3230">[email&#160;protected]</span><script cf-hash='f9e31' type="text/javascript">
/* <![CDATA[ */!function(){try{var t="currentScript"in document?document.currentScript:function(){for(var t=document.getElementsByTagName("script"),e=t.length;e--;)if(t[e].getAttribute("cf-hash"))return t[e]}();if(t&&t.previousSibling){var e,r,n,i,c=t.previousSibling,a=c.getAttribute("data-cfemail");if(a){for(e="",r=parseInt(a.substr(0,2),16),n=2;a.length-n;n+=2)i=parseInt(a.substr(n,2),16)^r,e+=String.fromCharCode(i);e=document.createTextNode(e),c.parentNode.replaceChild(e,c)}}}catch(u){}}();/* ]]> */</script></a>
</td>
<td style="width: 20%;">
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link danger">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
</span>
</a>
</td>
</tr>
<tr>
<td>
<img src="<?php echo base_url();?>assets/admin/img/samples/robert-300.jpg" alt=""/>
<a href="#" class="user-link">Robert Downey Jr.</a>
<span class="user-subhead">Admin</span>
</td>
<td>
2013/12/31
</td>
<td class="text-center">
<span class="label label-success">Active</span>
</td>
<td>
<a href="#">spencer@tracy</a>
</td>
<td style="width: 20%;">
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="#" class="table-link danger">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
</span>
</a>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

-->