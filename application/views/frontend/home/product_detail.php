
 <script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript">stLight.options({publisher: "10d8f0ec-ae8a-4157-bb45-e1ad1185e6c1", doNotHash: false, doNotCopy: false, hashAddressBar: true});</script>


<div class="col-sm-9 col-md-10 affix-content home-product-wrap">
						<ul class="share">
                        	<li><a href="#"><b>Share:</b></a></li>
								<span class='st_email' displayText='Email'></span>
								<span class='st_twitter' displayText='Tweet'></span>
								<span class='st_facebook' displayText='Facebook'></span>
								<span class='st_pinterest' displayText='Pinterest'></span>
                         
                        </ul>
                       
						
						
         <?php foreach($this->product_detail as $product) { ?>               
                       
                        
                        
						<!--/ Page Title -->
							<div class="page-title">
								<h3 class="title"><?php echo $product['product_name'];?> <div class="btn-group pull-right">
        <?php /*  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
          </button>
          <ul class="dropdown-menu dropdown-menu1" role="menu">
            <li><a href="#">Move to different Collection</a></li>
            <li><a href="#">Remove Follow</a></li>
            <li><a href="#">Save</a></li>
          </ul> */ ?>
        </div></h3> 


							</div>
						<!-- Page Title /-->
						
						<!--/ product -->							
						<div class="col-md-12 singleview">
                        	<div class="singleview">
								
								<?php if($product['product_image'] == "") {  
											$img= "no_image.png"; }
											 else {  
												 $img= $product['product_image']; 
												 } ?>
                            <img src="<?php  echo base_url('assets/uploads/products/'.$img) ?>" alt="" class="img-responsive mb_15 center-block">
                                <div class="caption1">
									<div class="stepwizard center-block">
                                    <div class="stepwizard-row">
                                        <div class="stepwizard-step">
                                        
                                            <a href="#"  title="" data-placement="top" data-toggle="tooltip" data-original-title="Likes: <?php echo $product['likes'];?>" class="btn btn-default btn-circle" type="button">
                                            	<img src="<?php echo include_img_path();?>/heart.png" class="img-responsive" alt="">
                                            </a>
                                           
                                        </div>
                                        <div class="stepwizard-step">
                                            <a href="#" title="" data-placement="top" data-toggle="tooltip" data-original-title="Favorite: <?php echo $product['favorites'];?>" class="btn btn-default btn-circle1" type="button">
                                            <img src="<?php echo include_img_path();?>/star.png" class="img-responsive" alt="">
                                            </a>
                                            
                                        </div>
                                        <div class="stepwizard-step">
                                            <a title="" data-placement="top" data-toggle="tooltip" data-original-title="Buy" class="btn btn-default btn-circle2" type="button" onclick="buy('<?php echo $product['id']?>','<?php echo $this->session->userdata('user_id') ?>')">
                                            <img src="<?php echo include_img_path();?>/bag.png" class="img-responsive" alt="">
                                            </a>
                                           
                                        </div> 
                                    </div>
                                </div>
								</div>
                                        
                                
                            </div>
                            <div class="clearfix"></div>
                            <div>
                            <p><?php echo $product['description'];?> </p>
                            <?php /*<p class="mT_15">I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents. I should be incapable of drawing a single stroke at the present moment; and yet I feel that I never was a greater artist than now.</p> */ ?>
                            </div>
                        <hr>
                        <?php $this->load->model('home_model');
								$status_fav = $this->home_model->check_user_fav_follow($product['id']);
								//print_r($status_fav);exit;
								?>
								
                      <?php
						
                      if(count($status_fav)>0){
                      
                       foreach($status_fav as $fav) {  ?>   
						   <div class="cf mT_15 space_view" >
								<div class="row">
									<div class="col-sm-3 col-md-2 follwer">
										<img class="center-block fn " alt="Image" src="<?php echo include_img_path();?>/users/<?php echo $fav['user_image'];?>"> 
									</div>
										<div class="col-sm-3 col-md-10 follwerhead">
										 <h3><?php echo $fav['user_name']; ?></h3>
										 
										 <?php $this->load->model('home_model');
											   $status_follow = $this->home_model->check_user_follow_exist($fav['id']); 
											  // print_r(count($status_follow));exit;
											    ?>
										 <?php if(count($status_follow)>0){ ?>
										 <div id ="append_but">
											<button class="btn btn-primary"  type="button" onclick="unfollow(<?php echo $fav['id']?>,<?php echo $this->session->userdata('user_id') ?>);">Unfollow</button>  
											</div>
										<?php } else {?>
											
											 <div id ="append_but">
											<button class="btn btn-primary"  type="button" onclick="follow(<?php echo $fav['id']?>,<?php echo $this->session->userdata('user_id') ?>);">Follow</button>  
											</div>
											<?php } ?>
										</div>
								</div>
						   </div>
                        
                       <?php } } ?> 
                        
                        <div class="panel panel-default">
					<?php $this->load->model('home_model');
								$status = $this->home_model->list_user_comments($product['id']);
								//print_r($status);exit;
								?>	
							
                        <div class="panel-heading">
                           
                            <h3 class="panel-title">
                                <span class="glyphicon glyphicon-comment"> </span> <?php echo count($status);?> Responses
                            </h3>   
                      	</div>     
						<div class="panel-body">
                        <?php foreach($status as $comment){  
							
							if($comment['user_image'] == ''){
									$img = "no_image.png";
								}else {
									$img = $comment['user_image'];
								} ?>
								
							<div class="" id="app_comm2">
								
							</div>
								
                         <div class="" id="app_comm">
                         <div class="viewer cf">
                            <div class="col-sm-3 col-md-2 resp-img">
                                <img class="center-block fn " alt="Image" src="<?php echo include_img_path();?>/users/<?php echo $img;?>">
                            </div>
                            <div class="col-sm-9 col-md-10">
                            <div class="row">
									<?php
									  $curenttime=$comment['comment_created_time'];
									  $time_ago =strtotime($curenttime);
									  
									?>
                                <h4><span class="viewername"><?php echo $comment['user_name'];?></span> <span class="view-hr viewername"><?php echo get_timeago($time_ago); ?></span></h4>
                                <p>
                                   <?php echo $comment['comment']; ?> 
                                </p>
                                <?php if($comment['id'] == $this->session->userdata('user_id')) { ?>
									
									<a href="<?php echo site_url('home/comment_delete/'.$comment['comment_id'].'/'.$product['id'].'') ?>" title="" data-placement="top" data-toggle="tooltip" class="btn btn-redcircle glyphicon glyphicon-remove pull-right" > </a>
                                        
                               <?php } ?>             
                            </div>
                            </div>
                         </div>
                         </div>
                         <?php } ?>
                         
                          <hr> <div class="row">
                        <form role="form" name="admin_login" id="admin_login" action="" method="POST"> 
                             
                              <div class="col-sm-9 col-md-10">
                                <textarea rows="5" placeholder="Reply" name="message" id="message" class="form-control frm-ctrl mb_15"></textarea>
                              </div>
                              <button type="button" class=" btn btn-lg btn-primary submited" onclick="product_comments('<?php echo $product['id']?>','<?php echo $this->session->userdata('user_id') ?>')">
                              <img src="<?php echo include_img_path();?>/submitd.png" alt="" class="img-responsive">
                             </button>  
                    
						</form>
                         </div>
                        </div>
                        
                        </div>
						<!-- product /-->
					</div
					<?php } ?>
				</div>
			


<script>
	
	/* BUY */
	
	
	function buy(product_id,user_id)
	{
		//alert('in');
		
		if(user_id == ''){
			
			alert("Pls login to access");
		}
		
		 var url = "<?php echo site_url(); ?>home/buy";
            
            $.ajax(
            {
                type:'POST',
                url:url,
                data: {product_id:product_id},
                cache:false,
                async:true,
                global:false,
                dataType:"html",
                success:function(check)
                { 
                  if(check == 0){
					  
					  alert("User can't access more than one time");
				  }else {
					  
					  $('#test_buy').addClass('highlight');
				  }
                  
                  
                }
            });    
            
	}
	



<?php
function get_timeago( $ptime )
{
    $etime = time() - $ptime;

    if( $etime < 1 )
    {
        return 'less than 1 second ago';
    }

    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60             =>  'hour',
                60                  =>  'minute',
                1                   =>  'second'
    );

    foreach( $a as $secs => $str )
    {
        $d = $etime / $secs;

        if( $d >= 1 )
        {
            $r = round( $d );
            return ' ' . $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
        }
    }
}
?>



	
</script>	
