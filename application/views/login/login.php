<div class="container">
<div class="row">
<div class="col-xs-12">
<?php if($message = $this->service_message->render()):
       echo $message;
      endif;
 ?>
    <div id="login-box">
    <div id="login-box-holder">
    <div class="row">
    <div class="col-xs-12">
        <header id="login-header">
            <div id="login-logo">LOGIN</div>
        </header>
        <div id="login-box-inner">
        <?php
         if(validation_errors()):
          echo validation_errors();
         endif; 
        ?>
            <form role="form" name="admin_login" id="admin_login" action="<?php site_url('admin/login')?>" method="POST">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input class="form-control" type="text" name="email" placeholder="Email or Username">
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div id="remember-me-wrapper">
                <div class="row">
                <div class="col-xs-6"></div>
                <a href="#" id="login-forget-link" class="col-xs-6"> Forgot password? </a>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <input type="submit" name="submit" class="btn btn-success col-xs-12" value="Login">
                </div>
            </div>
             <!-- <div class="row">
                <div class="col-xs-12">
                    <p class="social-text">Or login with</p>
                </div>
            </div>
           <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <button type="submit" class="btn btn-primary col-xs-12 btn-facebook"><i class="fa fa-facebook"></i> facebook</button>
                </div>
                <div class="col-xs-12 col-sm-6">
                        <button type="submit" class="btn btn-primary col-xs-12 btn-twitter"><i class="fa fa-twitter"></i> Twitter</button>
                </div>
            </div>-->
            </form>
        </div>
    </div>
    </div>
    </div>
    
    </div>
</div>
</div>
</div>