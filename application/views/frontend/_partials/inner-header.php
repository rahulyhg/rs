<div id="header">
<div class="logo">
<a href="<?php echo site_url('');?>"><img src="<?php echo include_img_path();?>/logo.jpg" /></a>
</div>
<div class="nav">
<form action="<?php echo site_url('');?>" method="post" class="basic-grey" style="float:left; margin-left:30px; color:#000000; font-size:13px;">
    <label>
        <input type="submit" class="button" value="Find a ride" />&nbsp;&nbsp;&nbsp;Or
    </label>   
</form>

<form action="<?php echo site_url('offer_seats');?>" method="post" class="basic-grey" style="float:left; margin-left:-10px;">
    <label>
        <input type="submit" class="button" value="Offer a ride" />
    </label>   
</form>
<div class="nav_right">
<img src="<?php echo include_img_path();?>/right.jpg" />
</div>
</div>
</div>
