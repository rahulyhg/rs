<?php echo validation_errors();?>

<?php echo $tmenu;?>

<div class="clear"></div>

<div class="cont">
    <div class="l_cont">
        <?php echo $smenu;?>
    </div>

    

    <div class="inner_cont">
    	<h1 style="margin-top:0px;">Your Postal Address</h1>
        <hr/>
        <p class="info">Your postal address is never displayed on the site.</p>
        <form action="" method="post" >

            <div class="basic-grey">
                <div class="left">
                    <label>First Name</label>
                </div>
                <div class="right">
                    <input type="text" id="first_name" name="first_name" value="<?php echo set_value('first_name', $address['first_name']);?>" placeholder="Your First Name">
                    <?php echo form_error('first_name', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Last Name</label>
                </div>
                <div class="right">
                    <input type="text" placeholder="Your Last Name" value="<?php echo set_value('last_name', $address['last_name']);?>" name="last_name" id="last_name">
                    <?php echo form_error('last_name', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            
            <div class="basic-grey">
                <div class="left">
                    <label>Address</label>
                </div>
                <div class="right">
                    <input type="text" placeholder="Street address, P.O. box, company name" value="<?php echo set_value('address_1', $address['address_1']);?>" name="address_1" id="address_1">
                    <?php echo form_error('address_1', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Address( line 2 )</label>
                </div>
                <div class="right">
                    <input type="text" placeholder="Building number, floor level" name="address_2" id="address_2" value="<?php echo set_value('address_2', $address['address_2']);?>">
                    <?php echo form_error('address_2', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Postcode</label>
                </div>
                <div class="right">
                    <input type="text" placeholder="" name="zip" id="zip" value="<?php echo set_value('zip', $address['zip']);?>">
                    <?php echo form_error('zip', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>City</label>
                </div>
                <div class="right">
                    <input type="text" name="city" id="city" value="<?php echo set_value('city', $address['city']);?>" />
                    <?php echo form_error('city', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Country</label>
                </div>
                <div class="right">
                    <?php echo form_dropdown('country', get_countries(), set_value('country', $address['country']));?>
                    <?php echo form_error('country', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    &nbsp;
                </div>
                <div class="right">
                    <input type="submit" class="button" value="Save"/>
                </div>
            </div>

               
        </form>

    </div>
</div>

<div class="clear"></div>


