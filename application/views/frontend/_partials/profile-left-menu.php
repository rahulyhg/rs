<div class="box">
      <h2>Profile Information</h2>
      <p <?php echo ($type=='general')?'class="active"':'';?>>
            <a href="<?php echo site_url('dashboard/profile/general');?>">
                  &raquo;&nbsp;Personal Information
            </a>
      </p>
      <p <?php echo ($type=='picture')?'class="active"':'';?>>
            <a href="<?php echo site_url('dashboard/profile/picture');?>">&raquo;&nbsp;Photo</a>
      </p>
      <p <?php echo ($type=='preferences')?'class="active"':'';?>>
            <a href="<?php echo site_url('dashboard/profile/preferences');?>">&raquo;&nbsp;Preferences</a>
      </p>
      <!--<p <?php echo ($type=='verifications')?'class="active"':'';?>>
            <a href="<?php echo site_url('dashboard/profile/verifications');?>">&raquo;&nbsp;Verifications</a>
      </p>-->
      <p <?php echo ($type=='car')?'class="active"':'';?>>
            <a href="<?php echo site_url('dashboard/profile/car');?>">&raquo;&nbsp;Car</a>
      </p>
      <p <?php echo ($type=='address')?'class="active"':'';?>>
            <a href="<?php echo site_url('dashboard/profile/address');?>">&raquo;&nbsp;Postal Address</a>
      </p>
      <div class="sep"></div>
      <h2>Account</h2>
      <p <?php echo ($type=='notifications')?'class="active"':'';?>>
            <a href="<?php echo site_url('dashboard/profile/notifications');?>">&raquo;&nbsp;Notifications</a>
      </p>
      <p <?php echo ($type=='change_password')?'class="active"':'';?>>
            <a href="<?php echo site_url('dashboard/profile/change_password');?>">&raquo;&nbsp;Change Password</a>
      </p>
      <!--<p <?php echo ($type=='close_account')?'class="active"':'';?>>
            <a href="<?php echo site_url('dashboard/profile/close_account');?>">&raquo;&nbsp;Close my account</a>
      </p> -->     
</div>