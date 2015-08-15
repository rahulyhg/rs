<div class="alert alert-<?php echo $service_message['status'];?> " style="margin-bottom: 0px !important;">
<button type="button" class="close" data-dismiss="alert">&times;</button>	
<strong><?php echo ucfirst($service_message['status']);?>:&nbsp;</strong>
<?php echo $service_message['message'];?>
</div>