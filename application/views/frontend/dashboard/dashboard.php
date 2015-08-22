

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
                    <select id="gender" name="gender">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                    <?php echo form_error('gender', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>First Name</label>
                </div>
                <div class="right">
                    <input type="text" id="first_name" name="first_name" value="" placeholder="Your First Name">
                    <?php echo form_error('bio', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Last Name</label>
                </div>
                <div class="right">
                    <input type="text" placeholder="Your Last Name" value="" name="last_name" id="last_name">
                    <?php echo form_error('bio', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Last Name</label>
                </div>
                <div class="right">
                    <input type="text" placeholder="Your Last Name" value="" name="last_name" id="last_name">
                    <?php echo form_error('bio', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Email</label>
                </div>
                <div class="right">
                    <input type="text" placeholder="Your Email Address" value="" name="email" id="email">
                    <?php echo form_error('bio', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Mobile Phone</label>
                </div>
                <div class="right">
                    <input type="text" placeholder="Phone" name="phone" id="phone">
                    <?php echo form_error('bio', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Birth Year</label>
                </div>
                <div class="right">
                    <select id="byear" name="byear">
                        <?php 
                            $year = date('Y');
                            for($i=0;$i<100;$i++)
                            {
                                $yr = $year - $i;
                                echo '<option value="'.$yr.'">'.$yr.'</option>';
                            }
                        ?>
                    </select>
                    <?php echo form_error('bio', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Bio</label>
                </div>
                <div class="right">
                    <textarea name="bio" id="bio"></textarea>
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


