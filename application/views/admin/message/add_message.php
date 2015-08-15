<?php if (isset($message)) { if($message = $this->service_message->render()) :?>
		<?php echo $message;?>
<?php endif; }?>
<div id="content-wrapper">
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb"><li><a href="#">Home</a></li><li class="active"><span>Form Elements</span></li></ol>
               <?php /* <h1 style="font-weight: bold; font-size:16px; color:#000; margin:20px 0 20px 0"><?php echo (isset($form_data['id']) && ($form_data['id'] !=''))?'Edit':'Add';?> User</h1> */ ?>
            </div>
        </div>
<div class="row">
 <div class="col-lg-12">
   <div class="main-box">
    <header class="main-box-header clearfix"><h2></h2></header>
    <span style="font-weight:bold; color:red; font-size: 12;">
    <?php if(validation_errors()):
          echo validation_errors();  
        endif;?>
    </span>
    <div class="main-box-body clearfix">
        <form role="form" name="add_message" id="add_message" action="<?php echo site_url("admin/message/add_message"); ?>" method="POST">
              
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter name" value="" />
            </div>
            
            <div class="form-group">
                <label for="message">Message</label>
                <textarea class="form-control" name="message" id="message" rows="5"><?php /* echo set_value('about', $form_data['about']); */?></textarea>
            </div>
                      
            <div class="form-group">
                <label for="type">Type</label>
                <input type="radio" class="form-control" name="type" id="type1" value="1" />Site
                <input type="radio" class="form-control" name="type" id="type2" value="2" />User
            </div>
            
            
            
             <div class="test">
				 <label for="type">Select User</label>
				 <input type="text" id="search_user" name="users" value=""/>
			</div>
        <script type="text/javascript">
			
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
        //alert(s);
		
		
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
        
   
        </script>
    
            
            
           
            <div class="col-md-1">
                <input type="submit" class="form-control btn btn-primary" style="font-weight: bold; font-size:17px;" name="submit" id="submit" value="Submit" />
            </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
