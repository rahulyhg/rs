
<?php $method = $this->router->fetch_method();?>
<div class="sep_tab">
    <div class="top_n">
        <ul>
            <li>
            	<a href="<?php echo site_url('dashboard/general');?>" class="<?php echo $method == 'general'?'tactive':'';?>" >Dashboard</a>
            </li>
            <li>
            	<a href="<?php echo site_url('dashboard/rides_offered');?>" class="<?php echo $method == 'rides_offered'?'tactive':'';?>">Rides Offered</a>
            	</li>
            <li>
            	<a href="<?php echo site_url('dashboard/messages');?>" class="<?php echo $method == 'messages'?'tactive':'';?>">Messages</a>
            </li>
            <li>
            	<a href="<?php echo site_url('dashboard/emails');?>" class="<?php echo $method == 'emails'?'tactive':'';?>">Email Alerts</a>
            </li>
            <li>
            	<a href="<?php echo site_url('dashboard/profile');?>" class="<?php echo $method == 'profile'?'tactive':'';?>">Profile</a>
            </li>
        </ul>
    </div>

</d