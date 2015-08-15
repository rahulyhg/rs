<div class="sep_tab">

    <form action="join-now.php" method="post" class="basic-grey" style="float:right; margin-top:-12px;">
        <label>
            <input type="submit" class="button" value="REGISTER" />
        </label>   
        
    </form>

    <h3 style="float:right; margin-top:20px; margin-right:10px;">Not a member?</h3>
</div>

<div class="cont">
    <div class="l_cont">
        <div class="box1">
            <h2>Today Trip</h2>
            <p class="b_right">Bangkok</p>
            <p class="b_left">Chiang Mai</p>
            <span>600 THB</span>

        </div>
        <div class="box2">
            <h2>How it works?</h2>
            <a href=""><img src="<?php echo include_img_path();?>/how_it_works.png" /></a>
        </div>
    </div>
    <div class="inner_cont">
        <h1>Reset your Password</h1>
        <form action="" method="post" class="basic-grey">

         
            <label>
                
                <input id="password" type="password" name="password" value="<?php echo set_value('password');?>"   placeholder="Password" />
                <?php echo form_error('password', '<span class="error_text">', '</span>'); ?>
            </label>
            <label>
                
                <input id="retype_password" type="password" name="retype_password"   placeholder="Retype password" /> 
                <?php echo form_error('retype_password', '<span class="error_text">', '</span>'); ?>
            </label>
            
            
            <input type="hidden" name="enc_str" value ="<?php echo $enc_str;?>">
         
         <label>
            <input type="submit" class="button" value="SUBMIT" />
        </label>    
    </form>

</div>

</div>