<div class="sep_tab">

    <div class="basic-grey" style="float:right; margin-top:-6px;">
        <label>
            <a class="button" href="<?php echo site_url('join-now');?>">REGISTER</a>
        </label>   
        
    </div>

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
        <h1>Forgot Password</h1>
        <form action="" method="post" class="basic-grey">

         
            <label>
                
                <input id="email" type="email" name="email" value="<?php echo set_value('email');?>"   placeholder="Email" />
                <?php echo form_error('email', '<span class="error_text">', '</span>'); ?>
            </label>
            
         
         <label>
            <input type="submit" class="button" value="SUBMIT" />
        </label>    
    </form>

</div>

</div>