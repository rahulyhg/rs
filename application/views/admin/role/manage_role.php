
<div id="content-wrapper"><div class="row">
<div class="col-lg-12">
<div class="row">
<div class="col-lg-12">
<ol class="breadcrumb">
<li><a href="#">Home</a></li>
<li class="active"><span>Manage Role</span></li>
</ol>
<div class="clearfix">
<h1 class="pull-left">Manage Role</h1>

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
<th><span>Role</span></th>
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

<td style="width: 20%;">
<a href="<?php echo site_url('admin/role/edit_role/'.$details['id'].'');?>" class="table-link">
<span class="fa-stack">
<i class="fa fa-square fa-stack-2x"></i>
<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
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


