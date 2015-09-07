<?php echo $tmenu;?>

<div class="clear"></div>

<div class="cont">
    <div class="l_cont">
        <?php echo $smenu;?>
    </div>

    

    <div class="inner_cont">
    	<h1 style="margin-top:0px;">Change Password</h1>
        <hr/>
        <form action="" method="post" >

            <div class="basic-grey">
                <div class="left">
                    <label>New Password</label>
                </div>
                <div class="right">
                    <input type="text" id="password" name="password" placeholder="New Password">
                    <?php echo form_error('password', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Retype Password</label>
                </div>
                <div class="right">
                    <input type="text" placeholder="Retype Password" name="retype_password" id="retype_password">
                    <?php echo form_error('retype_password', '<span class="error_text">', '</span>'); ?>
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


