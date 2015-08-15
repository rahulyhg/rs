<div class="container">
			<?php if($message = $this->service_message->render()):
       echo $message;
      endif;
 ?>
				<div class="row">
										
					<div class="col-xs-12 login-page omb_login">
						
						<!--/ Page Title -->
							<div class="page-title">
								<h3 class="title title text-center">Forgot your Password? </h3> </div>
						<!-- Page Title /-->
                        
                        <div class="omb_socialButtons center col-xs-12"><div class="col-xs-4 center">
		        <a href="login.html" class="btn btn-lg btn-block btn-facebook">
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
						
        <div class="col-xs-12 col-sm-6 col-lg-5 center">
			<?php
         if(validation_errors()):
          echo validation_errors();
         endif; 
        ?>
    <h4 class="">
     <b> Forgot your password?</b>
    </h4>
	<form role="form" name="admin_login" id="admin_login" action="<?php site_url('login/forgot_password')?>" method="POST">
      <fieldset>
        <span class="help-block">
          Email address you use to log in to your account
          <br>
          We'll send you an email with instructions to choose a new password.
        </span>
        <div class="input-group ">
          <span class="input-group-addon">
            
          </span>
          <input class="form-control txt-hide" placeholder="Email" name="email" type="text">
          
         
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg" id="btn-olvidado">
          Continue
        </button>
        
      </fieldset>
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
