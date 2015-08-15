<div class="container">
			<?php if($message = $this->service_message->render()):
       echo $message;
      endif;
 ?>
				<div class="row">
										
					<div class="col-xs-12 login-page omb_login">
						
						<!--/ Page Title -->
							<div class="page-title">
								<h3 class="title title text-center">Sign up </h3> </div>
						<!-- Page Title /-->
                        
                        <div class="omb_socialButtons center col-xs-12"><div class="col-xs-4 center">
		        <a href="#" class="btn btn-lg btn-block btn-facebook">
			        <i class="fa fa-facebook visible-xs"></i>
			        <span class="hidden-xs">Signup with Facebook</span>
		        </a>
	        </div></div>
            
            <div class="row omb_loginOr">
			<div class="col-xs-12 col-sm-6 center">
				<hr class="omb_hrOr">
				<span class="omb_spanOr">or</span>
			</div>
		</div>
						
						<!--/ Form -->							
						
        <div class="col-md-6 col-md-offset-3">
             <?php
         if(validation_errors()):
          echo validation_errors();
         endif; 
        ?>
			<form role="form" name="admin_login" id="admin_login" action="<?php echo site_url('login/signup')?>" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            <input type="text" name="first_name" value="" class="form-control input-lg" placeholder="First Name"  />    
                            </div>
                        <div class="col-xs-6 col-md-6">
                            <input type="text" name="last_name" value="" class="form-control input-lg" placeholder="Last Name"  />       </div>
                         
                    </div>
                    
                    <input type="text" name="user_name" value="" class="form-control input-lg" placeholder="User Name"  />       
                    <input type="text" name="email" value="" class="form-control input-lg" placeholder="Your Email"  />
                    <input type="password" name="password" value="" class="form-control input-lg" placeholder="Password"  />
                    <input type="password" name="confirm_password" value="" class="form-control input-lg" placeholder="Confirm Password"  />
                    <?php /*<input type="text" name="phone" value="" class="form-control input-lg" placeholder="Your Phone Number"  />
                    
                    <label>Birth Date</label>                   
                     <div class="row">
                        <div class="col-xs-4 col-md-4">
                            <select name="month" class = "form-control input-lg">
								<option value="01">Jan</option>
								<option value="02">Feb</option>
								<option value="03">Mar</option>
								<option value="04">Apr</option>
								<option value="05">May</option>
								<option value="06">Jun</option>
								<option value="07">Jul</option>
								<option value="08">Aug</option>
								<option value="09">Sep</option>
								<option value="10">Oct</option>
								<option value="11">Nov</option>
								<option value="12">Dec</option>
							</select>                        
						</div>
                        <div class="col-xs-4 col-md-4">
                            <select name="day" class = "form-control input-lg">
								<?php for($i=1; $i<=31; $i++) { ?>
									<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
								<?php } ?>
							</select>                       
						 </div>
                        <div class="col-xs-4 col-md-4">
                            <select name="year" class = "form-control input-lg">
								<?php
								for ($i=1950; $i<date('Y'); $i++) {
								?>
								<option value="<?php echo $i; ?>">
									<?php echo $i; ?></option>
								<?php } ?>
								</select>                        
						</div>
                    </div>  
                     <label>Gender : </label>                    <label class="radio-inline">
                        <input type="radio" name="gender" value="1" id=male />Male
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="gender" value="2" id=female />Female
                    </label>
                    <br /> */ ?>
              <span class="help-block"><label><input name="" type="checkbox" value=""> I agree the terms and conditions</label></span>
                    <button class="btn btn-lg btn-primary btn-block signup-btn" type="submit">
                        Create my account</button>
            </form>          
          </div><!-- /signup-container -->
        <div class="clear"></div>
        <p class="text-center mb_20">Already have an account? <a href="<?php echo site_url('login/user_login')?>">Login now!</a></p>
        
   <!-- /container -->
						<!-- Form /-->
					</div>
				</div>
			</div>
	<!-- page -->
	
