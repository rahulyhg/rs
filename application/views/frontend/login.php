<div class="container">
			<?php if($message = $this->service_message->render()):
       echo $message;
      endif;
 ?>
 
				<div class="row">
										
					<div class="col-xs-12 login-page omb_login">
						
						<!--/ Page Title -->
							<div class="page-title">
								<h3 class="title title text-center">Login or Sign up </h3> </div>
						<!-- Page Title /-->
                        
                        <div class="omb_socialButtons center col-xs-12"><div class="col-xs-4 center">
		        <a href="#" class="btn btn-lg btn-block btn-facebook">
			        <i class="fa fa-facebook visible-xs"></i>
			        <span class="hidden-xs">Login with Facebook</span>
		        </a>
	        </div></div>
            
            <div class="row omb_loginOr">
			<div class="col-xs-12 col-sm-6 center">
				<hr class="omb_hrOr">
				<span class="omb_spanOr">or</span>
			</div>
		</div>
		
						
						<!--/ Form -->							
						
        <div class="loginsec loginsec-container col-xs-12 col-sm-6 col-lg-4 center">
          <?php
         if(validation_errors()):
          echo validation_errors();
         endif; 
        ?>
			<form role="form" class="form-signin" name="admin_login" id="admin_login" action="<?php site_url('login/user_login')?>" method="POST">
              <input type="text" name="email" id="inputEmail" class="form-control" placeholder="Username or Mail ID" >
                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" >
                <div id="remember" class="checkbox">
                    <label>
                        <input type="checkbox" value="remember-me"> Remember me
                    </label>
                </div>
                <button class="btn btn-lg btn-primary btn-block btn-signing btn-lg" type="submit">Sign in</button>
            </form><!-- /form -->
            <a href="<?php echo site_url('login/forgot_password')?>" class="forgot-password">
                Forgot the password?
            </a>
        </div><!-- /loginsec-container -->
        
        <p class="text-center mb_20">Don't have a Keep account? <a href="<?php echo site_url('login/signup')?>">Sign Up now!</a></p>
        
   <!-- /container -->
						<!-- Form /-->
					</div>
				</div>
			</div>
	<!-- page -->
