<?php if (isset($message)) { if($message = $this->service_message->render()) :?>
		<?php echo $message;?>
<?php endif; }?>

<div id="content-wrapper">
    <div class="row">
        <div class="col-lg-12">

            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb"><li><a href="#">Products</a></li><li class="active"><span>add</span></li></ol>
                    <h1 style="font-weight: bold; font-size:16px; color:#000; margin:20px 0 20px 0"><?php echo (!empty($form_data['id']))?'Edit':'Add';?> Product</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="main-box">

                        <header class="main-box-header clearfix"><h2></h2></header>

                        <div class="main-box-body clearfix">

                            <form role="form" name="products" id="products"  method="POST" enctype="multipart/form-data">
                                 
                                <input type="hidden" name="edit_id" id="edit_id" value="<?php echo $edit_id = (isset($form_data['id']) && $form_data['id']!='')?$form_data['id']:"";?>" />
                                 
                                <div class="form-group <?php echo form_error('product_name')?'has-error':'';?>">
                                    <label for="product_name">Product Name <span class="vstar">*</span></label>
                                    <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Product Name" value="<?=set_value('product_name', $form_data['product_name']);?>" />
                                    <?php echo form_error('product_name', '<span class="help-block">', '</span>'); ?>
                                </div>

                                <div class="form-group <?php echo form_error('description')?'has-error':'';?>">
                                    <label for="description">Description <span class="vstar">*</span></label>
                                    <textarea class="form-control" name="description" id="description" rows="6"><?=set_value('description', $form_data['description']);?></textarea>
                                    <?php echo form_error('description', '<span class="help-block">', '</span>'); ?>
                                </div>

                                <div class="form-group <?php echo form_error('product_image')?'has-error':'';?>">
                                    <label for="upload_image">Product Image <span class="vstar"></span></label>
                                    <input id="upload_image" name="upload_image" type="file" class="file" >
                                    <input type="hidden" name="product_image" id="product_image" value="<?=set_value('product_image', $form_data['product_image']);?>">
                                    <?php echo form_error('product_image', '<span class="help-block">', '</span>'); ?>
                                </div>
                                
                                <div class="form-group <?php echo form_error('upcoming_product')?'has-error':'';?>">
                                    <label>Upcoming Product</label>
                                    <br/>
                                    <div class="checkbox-nice checkbox-inline">
                                        <input type="checkbox" class="form-control" name="upcoming_product" id="upcoming_product" value="1" <?=set_value('upcoming_product',$form_data['upcoming_product'])==1 ?'checked':'';?> />
                                        <label for="upcoming_product">
                                            Yes
                                        </label>
                                    </div>
                                </div>    
                                
                                <div class="form-group <?php echo form_error('buylink')?'has-error':'';?>">
                                    <label for="buylink">Buylink: <span class="vstar">*</span></label>
                                    <input type="text" class="form-control" name="buylink" id="buylink" placeholder="Enter Bulink" value="<?php echo set_value('buylink', $form_data['buylink']);?>" />
                                    <?php echo form_error('buylink', '<span class="help-block">', '</span>'); ?>
                                </div>
                                <div class="form-group <?php echo form_error('price')?'has-error':'';?>">
                                    <label for="price">Price <span class="vstar">*</span></label>
                                    <input type="text" class="form-control" name="price" id="price" placeholder="Enter Price" value="<?php echo set_value('price', $form_data['price']);?>" />
                                    <?php echo form_error('price', '<span class="help-block">', '</span>'); ?>
                                </div>
                                <div class="col-md-1">
                                    <input type="submit" class="form-control btn btn-primary" style="font-weight: bold; font-size:17px;" name="submit" id="submit" value="SAVE" />
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
</div>

<?php $prev_img = (!empty($form_data['product_image']))?"<img src='$img_url' class='file-preview-image' alt='Product Image' title='Product Image'>":""; ?>

<script>

var prv_img = "<?php echo $prev_img; ?>";
    
</script>
