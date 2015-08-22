

<?php echo $tmenu;?>

<div class="clear"></div>

<div class="cont">
    <div class="l_cont">
        <?php echo $smenu;?>
    </div>

    

    <div class="inner_cont">
    	<h1 style="margin-top:0px;">Your Profile Photo</h1>
        <hr/>
        <p class="info">Add your photo now! Other members will be reassured to see who they will be travelling with, and you will find  your car share much more easily. Photos also helps members to recognise each other.  </p>
        <form action="" method="post" >
            
            <?php
                $btnTxt = 'Choose a Photo';
                $src = '';
                $style = 'style="display:none"';

                if ( $general['profile_img'] != '' )
                {
                    $btnTxt = 'Click here to Change Photo';
                    $src = base_url('assets/uploads/users/'.$general['profile_img']);
                    $style = 'style="display:block"';
                }

            ?>
            <div class="basic-grey profile-image"  <?php echo $style;?>>                
                <div class="right" >
                    <img src="<?php echo $src;?>">
                </div>
            </div>
            <div class="clear"></div>
            <div class="basic-grey">
                
                <div class="right">
                    <button id="uploadBtn" class="button"><?php echo $btnTxt;?></button>
                </div>
                <div class="clear"></div>
                <div id="msgBox" class="error" style="display:none"></div>
            </div>

            

               
        </form>

    </div>
</div>

<div class="clear"></div>


