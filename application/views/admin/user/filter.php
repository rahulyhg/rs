<?php 
die('111111111111111111111');
$start_date     = array('name' => 'start_date', 'value' => $start_date, 'class' => 'span8', 'size' => '16');

$end_date      = array('name' => 'end_date', 'value' => $end_date, 'class' => 'span8', 'size' => '16');

$radio_all       = array('name' => 'order_status', 'value' => 'ALL', 'checked' => ($order_status == 'ALL') ? TRUE : FALSE);
$radio_pending   = array('name' => 'order_status', 'value' => 'PENDING', 'checked' => ($order_status == 'PENDING') ? TRUE : FALSE);
$radio_failed    = array('name' => 'order_status', 'value' => 'FAILED', 'checked' => ($order_status == 'FAILED') ? TRUE : FALSE);
$radio_accepted  = array('name' => 'order_status', 'value' => 'ACCEPTED', 'checked' => ($order_status == 'ACCEPTED') ? TRUE : FALSE);
$radio_processing  = array('name' => 'order_status', 'value' => 'PROCESSING', 'checked' => ($order_status == 'PROCESSING') ? TRUE : FALSE);
$radio_shipped   = array('name' => 'order_status', 'value' => 'SHIPPED', 'checked' => ($order_status == 'SHIPPED') ? TRUE : FALSE);
$radio_amazon_shipped   = array('name' => 'order_status', 'value' => 'AMAZON-SHIPPED', 'checked' => ($order_status == 'AMAZON-SHIPPED') ? TRUE : FALSE);
$radio_cancelled   = array('name' => 'order_status', 'value' => 'CANCELLED', 'checked' => ($order_status == 'CANCELLED') ? TRUE : FALSE);
$radio_partially_refunded   = array('name' => 'order_status', 'value' => 'PARTIALLY REFUNDED', 'checked' => ($order_status == 'PARTIALLY REFUNDED') ? TRUE : FALSE);
$radio_hold   = array('name' => 'order_status', 'value' => 'HOLD', 'checked' => ($order_status == 'HOLD') ? TRUE : FALSE);
$radio_unshipped   = array('name' => 'order_status', 'value' => 'UNSHIPPED', 'checked' => ($order_status == 'UNSHIPPED' || $order_status == '') ? TRUE : FALSE);

$radio_type_all  = array('name' => 'type', 'value' => '', 'checked' => ($type == NULL) ? TRUE : FALSE);
$radio_type_D    = array('name' => 'type', 'value' => 'D', 'checked' => ($type == 'D') ? TRUE : FALSE);
$radio_type_I    = array('name' => 'type', 'value' => 'I', 'checked' => ($type == 'I') ? TRUE : FALSE);

$radio_followup_all  = array('name' => 'followup', 'value' => '', 'checked' => ($followup == NULL )? TRUE : FALSE);
$radio_followup_Y  = array('name' => 'followup', 'value' => 'yes', 'checked' => ($followup == 'yes') ? TRUE : FALSE);
$radio_followup_N    = array('name' => 'followup', 'value' => 'no', 'checked' => ($followup == 'no') ? TRUE : FALSE);

$radio_fraud_all  = array('name' => 'fraudulent', 'value' => '', 'checked' => ($fraudulent == NULL )? TRUE : FALSE);
$radio_fraud_Y  = array('name' => 'fraudulent', 'value' => '1', 'checked' => ($fraudulent == '1') ? TRUE : FALSE);
$radio_fraud_N    = array('name' => 'fraudulent', 'value' => '0', 'checked' => ($fraudulent == '0') ? TRUE : FALSE);

$next_due_start_date     = array('name' => 'next_due_start_date', 'value' => $next_due_start_date, 'class' => 'span8', 'size' => '16');

$next_due_end_date      = array('name' => 'next_due_end_date', 'value' => $next_due_end_date, 'class' => 'span8', 'size' => '16');

$radio_paid_all      = array('name' => 'paid_status', 'value' => '', 'checked' => ($paid_status == NULL) ? TRUE : FALSE);
$radio_paid_status  = array('name' => 'paid_status', 'value' => 'Y', 'checked' => ($paid_status == 'Y') ? TRUE : FALSE);
$radio_unpaid_status    = array('name' => 'paid_status', 'value' => 'N', 'checked' => ($paid_status == 'N') ? TRUE : FALSE);

$radio_overdue 			= array('name' => 'overdue', 'value' => '1', 'checked' => ($overdue == '1') ? TRUE : FALSE);

$ship_start_date     = array('name' => 'ship_start_date', 'value' => $ship_start_date, 'class' => 'span8', 'size' => '16');

$ship_end_date      = array('name' => 'ship_end_date', 'value' => $ship_end_date, 'class' => 'span8', 'size' => '16');

$radio_order_risk_all  = array('name' => 'orders_at_risk', 'value' => '', 'checked' => ($orders_at_risk == NULL )? TRUE : FALSE);
$radio_order_risk_Y    = array('name' => 'orders_at_risk', 'value' => 'Y', 'checked' => ($orders_at_risk == 'Y') ? TRUE : FALSE);
$radio_order_risk_N    = array('name' => 'orders_at_risk', 'value' => 'N', 'checked' => ($orders_at_risk == 'N') ? TRUE : FALSE);


?>
 
<form id="advance_search_form" method="POST">

	<div class="row-fluid">
		<div class="span2">
		  <fieldset>
		    <legend>Date added</legend>
	    	<label>Start Date</label>
	    	<?php echo form_input($start_date);?>
	    	<label>End Date</label>
	    	<?php echo form_input($end_date);?>
	    	<!--<div class="input-append date datepicker">
	    		<?php echo form_input($start_date);?><span class="add-on"><i class="icon-th"></i></span>
	    	</div>
		    
		    <label>End Date</label>
		   	<div class="input-append date datepicker">
		    	<?php echo form_input($end_date);?><span class="add-on"><i class="icon-th"></i></span>
		    </div>-->
		  </fieldset>

		  	<fieldset>
		   		<legend>Fraudulent</legend>
		       	<label class="radio">
		      		<?php echo form_radio($radio_fraud_all);?>
		      		All
		    	</label>
		    
		    	<label class="radio">
		      		<?php echo form_radio($radio_fraud_Y);?>
		      		Yes
		    	</label>
		
		       <label class="radio">
		      		<?php echo form_radio($radio_fraud_N);?>
		      		No
		    	</label>
		   	</fieldset>

		</div>
	
	 	<div class="span2">
	  		<fieldset>
	    		<legend>Sales Channel</legend>
	    		<?php echo form_multiselect('sales_channel[]', array('zm' => 'Zing-Mobile')+getChannels(), $sales_channel, 'size="9"');?>
	    	</fieldset>

	    	<fieldset>
		   		<legend>Orders At Risk</legend>
		       	<label class="radio">
		      		<?php echo form_radio($radio_order_risk_all);?>
		      		All
		    	</label>
		    
		    	<label class="radio">
		      		<?php echo form_radio($radio_order_risk_Y);?>
		      		Yes
		    	</label>
		
		       <label class="radio">
		      		<?php echo form_radio($radio_order_risk_N);?>
		      		No
		    	</label>
		   	</fieldset>
		</div>
	
		<div class="span2">
			<fieldset>
		   		<legend>Status</legend>
		   		<label class="radio">
	      			<?php echo form_radio($radio_all);?>
	      			All
	    		</label>
	    		<label class="radio">
	      			<?php echo form_radio($radio_pending);?>
	      			Pending
	    		</label>
	    		<label class="radio">
	      			<?php echo form_radio($radio_failed);?>
	      			Failed
	    		</label>
	    		<label class="radio">
	      			<?php echo form_radio($radio_accepted);?>
	      			Accepted
	    		</label>
	    		<label class="radio">
	      			<?php echo form_radio($radio_processing);?>
	      			Processing
	    		</label>
	    		<label class="radio">
	      			<?php echo form_radio($radio_shipped);?>
	      			Shipped
	    		</label>		    	
		  	</fieldset>
		</div>
	
		<div class="span2">
			<fieldset>
		  		<legend>&nbsp;</legend>
		    	<label class="radio">
	      			<?php echo form_radio($radio_amazon_shipped);?>
	      			Amazon-Shipped
	    		</label>
	    		<label class="radio">
	      			<?php echo form_radio($radio_cancelled);?>
	      			Cancelled
	    		</label>
	    		<label class="radio">
	      			<?php echo form_radio($radio_partially_refunded);?>
	      			Partially Refunded
	    		</label>
	    		<label class="radio">
	      			<?php echo form_radio($radio_hold);?>
	      			Hold
	    		</label>
	    		<label class="radio">
	      			<?php echo form_radio($radio_unshipped);?>
	      			<b>Unshipped</b> <small>(Acceptd+Processing+Hold+Partially Refunded)</small>	    		
	    		</label>
			</fieldset>
		</div>
	
		<div class="span2">
			<fieldset>
		   		<legend>Type</legend>
		       	<label class="radio">
		      		<?php echo form_radio($radio_type_all);?>
		      		All
		    	</label>
		    
		    	<label class="radio">
		      		<?php echo form_radio($radio_type_D);?>
		      		Domestic
		    	</label>
		
		       <label class="radio">
		      		<?php echo form_radio($radio_type_I);?>
		      		International
		    	</label>
		   </fieldset>
		</div>
	
		<div class="span2">
			<fieldset>
		   		<legend>Followup</legend>
		       	<label class="radio">
		      		<?php echo form_radio($radio_followup_all);?>
		      		All
		    	</label>
		    
		    	<label class="radio">
		      		<?php echo form_radio($radio_followup_Y);?>
		      		Yes
		    	</label>
		
		       <label class="radio">
		      		<?php echo form_radio($radio_followup_N);?>
		      		No
		    	</label>
		   	</fieldset>
		</div>
	
		<div class="clearfix"></div>
	
		<div class="row-fluid">

			<div class="span3">
			  <fieldset>
			    <legend>Due Date</legend>
		    	<label>Start Date</label>
		    	<?php echo form_input($next_due_start_date);?>
		    	<label>End Date</label>
		    	<?php echo form_input($next_due_end_date);?>
		    	
			  </fieldset>
			</div>

			<div class="span3">
			  <fieldset>
			    <legend>Ship Date</legend>
		    	<label>Start Date</label>
		    	<?php echo form_input($ship_start_date);?>
		    	<label>End Date</label>
		    	<?php echo form_input($ship_end_date);?>
		    	
			  </fieldset>
			</div>

			<div class="span2">
				<fieldset>
			   		<legend>Paid Status</legend>
			       <label class="radio">
			      		<?php echo form_radio($radio_paid_all);?>
			      		All
			    	</label>
			    	<label class="radio">
			      		<?php echo form_radio($radio_paid_status);?>
			      		Yes
			    	</label>			
			       <label class="radio">
			      		<?php echo form_radio($radio_unpaid_status);?>
			      		No
			    	</label>
			   </fieldset>
			</div>
			<div class="span2">
				<fieldset>
			   		<legend>Overdue Date</legend>
			       <label class="radio">
			      		<?php echo form_radio($radio_overdue);?>
			      		Overdue
			    	</label>			    	
			   </fieldset>
			</div>
			<div class="span2 pull-right">
				<fieldset>
					<legend>Action</legend>
					<button type="button" class="btn btn-primary" onclick="$.fn.submit_advance_search_form();">Update</button>
					<button type="button" class="btn" onclick="$('#advancesearch').popover('hide');">Cancel</button>
				</fieldset>
			</div>	
		</div>
	</div>

</form>