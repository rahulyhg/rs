<?php if (isset($message)) { if($message = $this->service_message->render()) :?>
		<?php echo $message;?>
<?php endif; }?>
<div id="content-wrapper">
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb"><li><a href="#">Message</a></li><li class="active"><span>Add</span></li></ol>
                <h1 style="font-weight: bold; font-size:16px; color:#000; margin:20px 0 20px 0"><?php echo (isset($form_data['id']) && ($form_data['id'] !=''))?'Edit':'Add';?> Message</h1>  
            </div>
        </div>
<div class="row">
 <div class="col-lg-12">
   <div class="main-box">
    <header class="main-box-header clearfix"><h2></h2></header>
    
    <div class="main-box-body clearfix">
        <form role="form" name="message" id="message" method="POST">
              
            <input type="hidden" name="edit_id" id="edit_id" value="<?php echo $edit_id = (isset($form_data['id']) && $form_data['id']!='')?$form_data['id']:"";?>" />
 
            <div class="form-group <?php echo form_error('name')?'has-error':'';?>">
                <label for="name">Title</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter title" value="<?=set_value('name', $form_data['name']);?>" />
                <?php echo form_error('name', '<span class="help-block">', '</span>'); ?>
            </div>
            
            <div class="form-group <?php echo form_error('message')?'has-error':'';?>">
                <label for="message">Message</label>
                <textarea class="form-control" name="message" id="message" rows="5"><?=set_value('message', $form_data['message']);?></textarea>
                <?php echo form_error('message', '<span class="help-block">', '</span>'); ?>
            </div>

            <div class="form-group <?php echo form_error('type')?'has-error':'';?>" >
                <label>Message Type</label>
                <div class="radio">
                    <input type="radio" name="type" id="type1" value="1" <?=set_value('type', $form_data['type']) == 'site' ? "checked" : ""?> >
                    <label for="type1">
                    Site
                    </label>
                </div>
                <div class="radio">
                    <input type="radio" name="type" id="type2" value="2" <?=set_value('type', $form_data['type']) == 'users' ? "checked" : ""; ?> >
                    <label for="type2">
                    Users
                    </label>
                </div>
                <?php echo form_error('type', '<span class="help-block">', '</span>'); ?>
            </div>
            
            <div id="autosuggest" style="<?=set_value('type', $form_data['type']) == 1 ?"display:none":"";?>" class="form-group <?php echo form_error('users')?'has-error':'';?>">
				 <label for="type">Select User</label>
				 <input type="text" id="search_user" class="form-control" name="users" value=""/>
                 <?php echo form_error('users', '<span class="help-block">', '</span>'); ?>
			</div>

            <div class="col-md-1">
                <input type="submit" class="form-control btn btn-primary" style="font-weight: bold; font-size:17px;" name="submit" id="submit" value="Submit" />
            </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>

<script type="text/javascript">

var msg_type ="";
<?php if($form_data['type']): ?>
    var msg_type   = "<?php echo $form_data['type']; ?>";
<?php endif;?>

var prepoulate ="";
<?php if($sel_users): ?>
    var prepoulate   = <?php echo $sel_users; ?>;
<?php endif;?>
//alert(prepoulate);
  /*          
    $(document).ready(function() {
            
        $(".test").hide();
            $("#type2").click(function () {
                $(".test").show();
            $("#type1").click(function () {
                $(".test").hide();
            });
        });
        
        $("#search_user").keyup(function(){
        
            var s = $("#search_user").val();        
        
            var url = "<?php echo site_url(); ?>admin/message/auto_complete";
            
        
            $.ajax(
            {
                type:'POST',
                url:url,
                data: {search_key:$("#search_user").val()},
                cache:false,
                async:true,
                global:false,
                dataType:"json",
                success:function(check)
                { //alert(check);
                  $("#search_user").tokenInput(check);
                }
            });

        });
           
    });
*/
   
</script>
