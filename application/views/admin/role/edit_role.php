<?php if (isset($message)) { if($message = $this->service_message->render()) :?>
		<?php echo $message;?>
<?php endif; }?>
<div id="content-wrapper">
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb"><li><a href="#">Home</a></li><li class="active"><span>Form Elements</span></li></ol>
                
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
        <form role="form" name="user" id="user" action="<?php echo site_url("admin/role/edit_role"); ?>" method="POST">
           <?php foreach ($this->result as $r){ ?>    
            <div class="form-group">
                <label for="Name">Role</label>
                <input type="text" class="form-control" name="role" id="name" placeholder="Enter Role" value="<?php echo $r["name"]; ?>" />
                 <input type="hidden" class="form-control" name="id" id="name" placeholder="Enter Role" value="<?php echo $r["id"]; ?>" />
            </div>
           <div class="col-md-1">
                <input type="submit" class="form-control btn btn-primary" style="font-weight: bold; font-size:17px;" name="submit" id="submit" value="SAVE" />
            </div>
            <?php } ?>
            </form>
        </div>
    </div>
</div>
</div>
</div>
