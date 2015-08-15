
<div id="content-wrapper"><div class="row">
<div class="col-lg-12">
<div class="row">
<div class="col-lg-12">
<ol class="breadcrumb">
<li><a href="#">Home</a></li>
<li class="active"><span>Messages</span></li>
</ol>
<div class="clearfix">
<h1 class="pull-left">Messages</h1>

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
<th><span>S.No</span></th>
<th><span>Name</span></th>
<th><span>Message</span></th>
<th class="text-center"><span>type</span></th>
<th>&nbsp;</th>
</tr>
</thead>
<?php $i = 1;foreach( $this->result as $details ){ ?>
<tbody>
<tr>
<td>
<span class="user-subhead"><?php echo $i;?></span>
</td>
<td>
<span class="user-subhead"><?php echo $details["name"]; ?></span>
</td>
<td>
<?php echo $details["message"]; ?>
</td>
<td class="text-center">
<span class="label label-default"><?php echo $details["type"]; ?></span>
</td>

<td style="width: 20%;">
<?php /*<a href="#" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
</span>
</a> */?>
<a href="<?php echo site_url('admin/message/edit_message/'.$details['id'].'');?>" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
</span>
</a>
<a href="<?php echo site_url('admin/message/delete_message/'.$details['id'].'');?>" class="table-link danger" onclick="return confirm('Are you sure you want to delete this ?');">
	
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
</span>
</a>
</td>
</tr>
</tbody>
<?php $i++; }  ?>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>


