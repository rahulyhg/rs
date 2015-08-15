<div class="col-sm-9 col-md-10 affix-content home-product-wrap">
						
					
                        
						<!--/ Page Title -->
							<div class="page-ti-tle filter-nav text-center">
								<ul class="pagination cf text-center">
									<?php foreach($this->data['user_data'] as $data) { ?>
									<li><a <?php  if($this->uri->segment(2) == "user_profile"){ ?> class="active" <?php } ?>  href="<?php echo site_url('login/user_profile/'.$data['id'].'') ?>" >Collection </a></li>
									<li><a <?php  if($this->uri->segment(2) == "list_fav_product"){ ?> class="active" <?php } ?>  href="<?php echo site_url('home/list_fav_product/'.$data['id'].'')?>">Favorite </a></li>
									<li><a <?php  if($this->uri->segment(2) == "list_like_product"){ ?> class="active" <?php } ?> href="<?php echo site_url('home/list_like_product/'.$data['id'].'')?>">Likes </a></li>
									
								</ul>
								<div class="clear"></div>
							</div>
						<!-- Page Title /-->
						<a name=""
						<!--/ My Profile -->							
						<div class="cf page-gap">
							<?php if($this->session->userdata('user_id') == $data['id']) { ?>
							<div class="col-xs-12 col-sm-6 col-md-3 user-collection">
								
								<div class="newprod">
									
									<div class="recently-added gallery-projects-wrap  newcollection">
										<a data-dismiss="modal" class="btn btn-red1 glyphicon glyphicon-plus" type="button" id="create_new" ></a>
										<h2 id="new_colle">Create a New Collection</h2>
                                           
                                           
                                      <div class="form-group">
										<form>
                                           <input type="Text" class="form-control frmcrtl" id="text" placeholder="Name your collection">
                                         <p style="color:red;" id="error_collection"> Pls Enter Collection </p>
                                         <button type="button" class="btn btn-lg btn-default btn-primary btn-gn" onclick="user_collection_create('<?php echo $this->session->userdata('user_id') ?>')">Create</button>
                                         
                                         <a id="cancel_create"> Cancel </a>
                                        </form>
                                           
                                      </div>
                                                                               
                                        </div>
									
								</div>
                                <?php }   ?>
                              <?php /*  <div class="newprod1">
									
									<div class="recently-added gallery-projects-wrap  newcollection">
										<a data-dismiss="modal" class="btn btn-red glyphicon glyphicon-remove" type="button" href="#"></a>
										<h2>Create a New Collection</h2>
                                        
                                          <div class="form-group">
										<form>
                                           <input type="Text" class="form-control frmcrtl" id="text" placeholder="Name your collection">
                                          </div>
                                         
                                         <button type="submit" class="btn btn-lg btn-default btn-primary btn-gn">Create</button>
                                        </form>
                                        </div>
									
								</div> */ ?>
							</div>
                            <?php } ?>
							<!-- -->
						
						
						<?php foreach($this->collection_list as $list_collection) {   
							
							$this->load->model('login_model');
						$collection = $this->userlogin_model->get_product_list_collection($list_collection['id'],$list_collection['user_id']);
						//print_r($collection);
							
							
							?>		
							<div class="col-xs-12 col-sm-6 col-md-3 user-collection">
								<div class="">
									<h3><?php echo $list_collection['name']; ?></h3>
									
									<?php 
						
										$this->load->model('login_model');
										$collection = $this->userlogin_model->get_product_list_collection($list_collection['id'],$list_collection['user_id']);
										//print_r($collection);
									?>
									
									<p><?php echo count($collection) ?> Collections</p>
									
									
									
									<div class="recently-added gallery-projects-wrap">
										
										<?php if(count($collection)>0 ){ 
										 foreach($collection as $result) {?>
											<a>
												<img src="<?php  echo base_url('assets/uploads/products/'.$result[0]['product_image']) ?>" class="" alt="" width="201" height="181" />
											</a>
										<?php break;} }else {  ?>
											<a>
												<img src="<?php  echo base_url('assets/uploads/products/no_image.png') ?>" class="" alt="" width="201" height="181" />
											</a>
											<?php } ?>
										<div class="gallery-cover-overlay">
											<div class="gallery-cover-overlay-text">
											<?php if(count($collection)>0 ){ ?>	
												<a href="<?php echo site_url('login/list_collection_product/'.$list_collection['user_id'].'/'.$list_collection['id'].'') ?>">View Collection&nbsp;<span class="rarr fa fa-long-arrow-right">&nbsp;</span></a>
												<?php } else { ?>
													
													<a>View Collection&nbsp;<span class="rarr fa fa-long-arrow-right">&nbsp;</span></a>
													
													<?php } ?>
													
											</div>
										</div>
									</div>
									<div class="last-added text-center">
										<ul class="cf">
											<?php $i=0; foreach($collection as $result) {?>
											<li><img src="<?php  echo base_url('assets/uploads/products/'.$result[0]['product_image']) ?>" class="" alt="" width="" height="" /></li>
											<?php $i++; if($i==3){ break; } }  ?>
										</ul>
									</div>
									
									
								</div>
							</div>
							<!-- -->
							
						<?php  } ?>	
							
							
							
							
						
						</div>
						<!-- My Profile /-->
					</div>
				
