

<div class="clear"></div>

<div class="cont">
    <div class="l_cont">
            <?php $type = 'general'; ?>
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
    </div>

    

    <div class="inner_cont">
      
        
            <?php if(count($rides)): ?>
                  <h1 style="margin-top:0px;"><?php echo count($rides);?> ride(s) from <?php echo $data['origin_name'].' to '.$data['dest_name'];?></h1>
                  <hr/>
            <?php foreach ($rides as $ride):?>
            <?php
                  $user_img = include_img_path().'/NoProfileImage.jpg';
                  if( $ride['profile_img']!= '' )
                        $user_img = base_url('assets/uploads/users/'.$ride['profile_img']);

            ?>
                  <div>
                        <div class="sec1">
                              <div class="left">
                                    <img src="<?php echo $user_img;?>" />                 
                              </div>
                              <div class="right">
                                    <p><?php echo $ride['first_name'].' '.$ride['last_name'];?>
                                    <br/><?php echo get_age($ride['dob'])?> years old</p>
                              </div>
                        </div>
                        <div class="sec2">
                              <p><?php echo $ride['schedule_start_date'].' '.$ride['ride_start_time'];?>
                              <br/><?php echo get_org_dest($ride, 'origin_name').' -> '.get_org_dest($ride, 'dest_name');?>
                              <br/>Departure :<?php echo get_org_dest($ride, 'origin_address');?>
                              <br/>Destination :<?php echo get_org_dest($ride, 'dest_address');?></p>
                        </div>
                        <div class="sec3">
                              <p>
                                    <?php echo '$'.number_format($ride['total_dist']*2, 2);?>
                                    <br><small>per co traveller</small>

                                    <br/>
                                    <?php echo $ride['seat_count'];?> seat(s) left
                              </p>

                        </div>
                  </div>
            <?php endforeach;?>

            <?php else:?>
                  <br/><br/>
                  <h1 style="margin-top:0px;">No ride from <?php echo $data['origin_name'].' to '.$data['dest_name'];?></h1>
                  <br/><br/><br/>
            <?php endif;?>

        


    </div>
</div>
 <div class="clear"></div>


