<div class="col-xs-12 col-sm-3 col-md-2">
						<div class="row">
							<div class="sidebar-nav">
							
							
								<div class="navbar navbar-default" role="navigation">								
									<div class="navbar-header">
										<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
											<span class="sr-only">Toggle navigation</span>
											<span class="icon-bar"></span>
											<span class="icon-bar"></span>
											<span class="icon-bar"></span>
										</button>
										<span class="visible-xs navbar-brand"><b>menu</b></span>
									</div>
																		
									<div class="navbar-collapse collapse sidebar-navbar-collapse">
									<?php foreach($this->data['user_data'] as $data) { 
										
										
										
										?>
										<!--// User Details -->
											 <div class="user_details totpad">
												<div class="profile-pic">
													<a href="#" data-toggle="tooltip" data-placement="bottom" title="">
														<img src="<?php echo include_img_path();?>/users/<?php echo $data['user_image']; ?>" class="img-responsive" alt="" />
														<div class="profile-nic ellipsis"><?php echo $data['user_name']; ?></div>
												</a>
												</div>
												<!-- Follower & Following  -->
												<div class="follow cf">
													<div class="col-xs-6">
														<span><a href="<?php echo site_url(); ?>/home/followers_user_list/<?php echo $data['id'];?>" ><?php echo $data['following_count'] ?></a></span>
														<p>Following</p>
													</div>
													<div class="col-xs-6">
														<span><a href="<?php echo site_url(); ?>/home/following_user_list/<?php echo$data['id'];?>" ><?php echo $data['followed_count'] ?></a></span>
														<p>Followers</p>
													</div>
													<a href="#" class="btn btn-follow"> <i class="fa fa-plus"></i>Follow </a>
												</div>
												
												<!-- About Profiler -->
												<div class="cf about-profiler">
													<ul>
														<li> <i class="fa fa-send"></i><a href="mailto:??"><?php echo $this->session->userdata('email') ?></a></li>
														<li> <i class="fa fa-mobile-phone"></i> <?php echo $data['phone']; ?></li>
														<li> <i class="fa fa-map-marker"></i> <?php echo $data['location']; ?></li>
														<li> <i class="fa fa-link"></i> <a href="#">http://tjb.com/shagrey</a></li>
														<li> <b>ABOUT ME: </b> <?php echo $data['about']; ?>
														</li>
													</ul>
												</div>
												
											</div> 
										<?php } ?>
										<!-- User Details //-->
										
										<div class="left-nav">
											<ul>
												<li><a <?php  if($this->uri->segment(2) == "most_popular"){ ?> class="active" <?php } ?> href="<?php echo site_url(); ?>/home/most_popular" > Most Popular</a></li>
												<li><a <?php  if($this->uri->segment(2) == "upcomming_auctions"){ ?> class="active" <?php } ?>href="<?php echo site_url(); ?>/home/upcomming_auctions" >Upcomming Auctions</a></li>
												
												<li><a <?php  if($this->uri->segment(2) == "auction_calender"){ ?> class="active" <?php } ?>href="<?php echo site_url(); ?>/home/auction_calender" >Auction Calender</a></li>
												
												<?php /*<li><a href="#">Museum Collections</a></li> */ ?>
												<li><a <?php  if($this->uri->segment(2) == "directory_list"){ ?> class="active" <?php } ?> href="<?php echo site_url('home/directory_list')?>">Directory</a></li>
												<li><a <?php  if($this->uri->segment(2) == "signup"){ ?> class="active" <?php } ?> href="<?php echo site_url('login/signup')?>">Signup</a></li>
												<li><a href="#">About</a></li>
												<li><a href="#">Feed Back</a></li>
												<li><a href="#">Privacy Policy</a></li>
												 
											</ul>
										</div>
									</div><!--/.nav-collapse -->
								</div>
							</div>
						</div>
					</div>
					
