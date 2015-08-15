<!--/ Start Header Section -->
			<!--div class="hidden-header"></div-->
			<header class="clearfix">
				<div class="">
					<div class="container">
						<div class="row">
							<!-- section left -->
							<div class="col-xs-12 col-sm-5">
								<div class="row">&nbsp;</div>
							</div>
							
							<!-- section mid -->
							<div class="col-xs-12 col-sm-2">
								<div class="row text-center logo">
									<a class="" href="<?php echo site_url();?>">
										<img alt="" src="<?php echo include_img_path();?>/logo-small.png" class="logo" width="100" height="114" alt="logo [100x114]">
									</a>
								</div>
							</div>
							
							<!-- section right -->
							
							<div class="col-xs-12 col-sm-5 top-section text-right right-section">
								<div class="r-ow">
                                
									<div class="col-xs-12 col-md-8 col-md-offset-4">
										<div class="row">
											<div class="col-xs-12 divice-align-right">
												<div class="row">
													<div class="dropdown small-width">
													  <a href="<?php echo site_url('login/user_login')?>" class="btn btn-default btn-green mb_20">Login </a>
													  
													</div>    
												</div>
											</div>
											
										
												<div class="row">
														<!--/ Search -->
			
			<?php if(!$this->session->userdata('user_id') &&  !$this->uri->segment(2) == 'user_login'  || $this->uri->segment(2) == '' || $this->uri->segment(2) == 'search_result' || $this->uri->segment(2) == 'user_profile' || $this->uri->segment(2) == 'product_detail' ) { ?>
				<form role="form" action="<?php echo site_url('login/search_result')?>" method="POST">
				
					<div class="col-sm-12">
						<div class="input-group brd">
						  <input type="text" id="search_id" class="form-control" name="search" placeholder="Search..." value="">
						  
						  <span class="input-group-btn">
							<button type="submit" class="button btn btn-default"><i class="fa fa-search"></i></button>
						  </span>
						  
						</div>
					
				</div>
				</form>
				<?php } ?>
			<!-- Search /-->

												</div>
											</div>
										
										<?php if($this->session->userdata('user_id')){ ?>
											
											
											<div class="col-xs-12 col-sm-12 top-section text-right">
								<div class="r-ow">
									<div class="col-xs-12 col-sm-7 col-md-8">
										<div class="row">
																						
											<div class="col-xs-6 col-sm-7 col-md-6 col-xs-push-3 col-sm-push-6 divice-center">
												<div class="row">
													<div class="dropdown ">
                                                   <div class="btn-group pull-right small-width">
													   
        <button class="btn btn-default btn-green dropdown-toggle glyphicon glyphicon-triangle-bottom" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="false">
														<img src="<?php echo include_img_path();?>/users/<?php echo $this->session->userdata('user_image');?>" class="custom-icon1 custom-icon13" alt="" width="60" height="60" /><?php echo $this->session->userdata('user_name');?>
													  </button>
														  <ul role="menu" class="dropdown-menu">
															  <li><a href="<?php echo site_url('login/user_profile') ?>">My Profile</a></li>
															   <li><a href="<?php echo site_url('recently_viewed/recent_view') ?>">Recently viewed</a></li>
															<li><a href="<?php echo site_url('login/user_settings')?>">Preferences</a></li>
															<li><a href="<?php echo site_url('login/logout')?>">Log out</a></li>
															<li class="btn-grn-noti">

     <b>Notification</b>
      <span class="counting1">575</span>           
     
             </li>
														  </ul>
														</div><!--
												<button class="btn btn-default btn-green dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="false">
														<img src="images/profile.png" class="custom-icon" alt="" width="60" height="60" />Login
													  </button>-->
                                                     
                                           
													</div>
												</div>
											</div>
										</div>
									</div>
                                    
                                    <div class="col-xs-12 col-sm-5 col-md-4">
										<div class="row">
                                        
											<div class="count btn-grn-notify">
                                            	<img src="<?php echo include_img_path();?>/count.png" alt="">
                                                <span class="counting">75
											</span>											
											</div>
										</div>
									</div>
								</div>
							</div>
										
										<?php } ?>
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>
			<!-- End Header Section /-->
			
		
