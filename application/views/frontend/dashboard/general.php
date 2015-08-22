<?php echo validation_errors();?>

<?php echo $tmenu;?>

<div class="clear"></div>

<div class="cont">
    <div class="l_cont">
        <?php echo $smenu;?>
    </div>

    

    <div class="inner_cont">
    	<h1 style="margin-top:0px;">Your Personal Information</h1>
        <hr/>
        <form action="" method="post" >

            <div class="basic-grey">
                <div class="left">
                    <label>Gender</label>
                </div>
                <div class="right">
                    <?php echo form_dropdown('gender', array('M' => 'Male', 'F' => 'Female'), set_value('gender', $general['gender']) ); ?>
                    <?php echo form_error('gender', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>First Name</label>
                </div>
                <div class="right">
                    <input type="text" id="first_name" name="first_name" value="<?php echo set_value('first_name', $general['first_name']);?>" placeholder="Your First Name">
                    <?php echo form_error('first_name', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Last Name</label>
                </div>
                <div class="right">
                    <input type="text" placeholder="Your Last Name" value="<?php echo set_value('last_name', $general['last_name']);?>" name="last_name" id="last_name">
                    <?php echo form_error('last_name', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            
            <div class="basic-grey">
                <div class="left">
                    <label>Email</label>
                </div>
                <div class="right">
                    <input type="text" placeholder="Your Email Address" value="<?php echo set_value('email', $general['email']);?>" name="email" id="email">
                    <?php echo form_error('email', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Mobile Phone</label>
                </div>
                <div class="right">
                    <input type="text" placeholder="Phone" name="phone" id="phone" value="<?php echo set_value('phone', $general['phone']);?>">
                    <?php echo form_error('phone', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Date of Birth</label>
                </div>
                <div class="right">
                    <input type="text" placeholder="YYYY-MM-DD" name="dob" id="dob" value="<?php echo set_value('dob', $general['dob']);?>">
                    <?php echo form_error('dob', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Bio</label>
                </div>
                <div class="right">
                    <textarea name="bio" id="bio"><?php echo set_value('bio', $general['bio']);?></textarea>
                    <?php echo form_error('bio', '<span class="error_text">', '</span>'); ?>
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


