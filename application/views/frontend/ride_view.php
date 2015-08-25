

<div class="clear"></div>

<div class="cont">
    <div class="l_cont">
            <div class="box">
                  <h2>Profile Information</h2>
                       
            </div>
    </div>

     <div class="inner_cont">

            <div>
      
                  <div class="ride_detail">

                        <div class='head'>     
                              <h1> <?php echo $view_data['origin_name'].' - '.$view_data['dest_name'];?></h1>
                        </div>

                        <table class="detail" cellpadding="0" border="0" cellspacing="0" width="100%">
                              
                              <tr>
                                    <td width="35%" class="tit">Departure Point</td>
                                    <td width="65%"><?php echo $view_data['origin_address'];?></td>
                              </tr>
                              <tr>
                                    <td class="tit">Drop off point</td>
                                    <td><?php echo $view_data['dest_address'];?></td>
                              </tr>
                               <tr>
                                    <td class="tit">Departure Date</td>
                                    <td><?php echo $view_data['schedule_start_date'];?></td>
                              </tr>
                               <tr>
                                    <td class="tit">Departure Time</td>
                                    <td><?php echo date('H:i',strtotime($view_data['ride_start_time']));?></td>
                              </tr>
                              
                        </table>

                        <table class="desc" cellpadding="0" border="0" cellspacing="0" width="95%">
                              
                              <tr>
                                    <td colspan="2"><h2>Trip Details</h2></td>
                              </tr>
                              <tr>
                                    <td class="tit" colspan="2">"<?php echo $view_data['description'];?>"</td>
                              </tr>
                               <tr>
                                    <td width="30%">Detour</td>
                                    <td><?php echo $view_data['detour_flexibility'];?></td>
                              </tr>
                               <tr>
                                    <td >Schedule flexibility</td>
                                    <td><?php echo $view_data['schedule_flexibility'];?></td>
                              </tr>
                              <tr>
                                    <td >Luggage size</td>
                                    <td><?php echo $view_data['luggage'];?></td>
                              </tr>
                              <tr>
                                    <td >Car</td>
                                    <td> Maruti Swift</td>
                              </tr>
                              
                        </table>

                        <div class="offer_seen">Offer Published : <?php echo date('d/m/Y',strtotime($view_data['schedule_start_date']));?> - seen 123 times</div>

                        <div class="clear"></div>


                  </div>   

                  <div class="owner_detail">

                        <div class='price'>
                              <div class='head'><span>$<?php echo round(($view_data['total_dist']*2),2); ?></span> per co-traveller</div>
                              
                              <div class='seat'>
                                    <span><?php echo  $view_data['seat_count'];?></span> seats left
                              </div>

                              <div class="book_seat">
                                    <div id="book-popup" style="display:none;">  
                                        <h3>How can i book a seat?</h3>  
                                        <p>Contact the car owner by phone or private message</p>  
                                        <p>Confirm the details of the trip together</p>
                                        <p>Travel together, You'll pay the car owner during the ride</p>
                                        <button id="close-book">Close  
                                    </div>

                                    <p>click to book your sheet</p>
                                    <a href="javascript:void(0);" class="button" >Contact car owner</a><br/><br/>

                                    <a href="javascript:void(0);"  id="show-book" >How can i book a seat?</a><br/>
                              </div>
                        </div>

                        <div class="clear">&nbsp;</div>

                        <div class='car'>
                              <div class='head'><span>Car owner</span></div>
                              <?php $user_img = include_img_path().'/NoProfileImage.jpg';
                               if( $view_data['profile_img']!= '' )
                                     $user_img = base_url('assets/uploads/users/'.$view_data['profile_img']);
                              ?>
                              <table cellspacing="0" cellpadding="0" border="0" width="96%" class="detail">
                              <tr>
                                    <td><img src="<?php echo $user_img;?>" style="width:75px;height:80px"/></td>
                                    <td>
                                          <p><?php echo $view_data['first_name']." ".$view_data['last_name']; ?></p>
                                          <p><?php $interval = abs(strtotime($view_data['dob']) - strtotime(date('Y-m-d'))); echo floor($interval/(365*60*60*24)); ?> years old</p>                                          
                                    </td>
                              </tr>
                              <tr>
                                    <td colspan="2" class="phone">Phone number verified</td>
                              </tr>

                              <tr>
                                    <td colspan="2" class="mem">Member Activity</td>
                              </tr>
                              <tr>
                                    <td colspan="2" >1 nde offered</td>
                              </tr>
                              <tr>
                                    <td colspan="2" >Last online : Thursday 17 August</td>
                              </tr>
                              <tr>
                                    <td colspan="2" >Member since : 15 july 2015</td>
                              </tr>
                              <tr>
                                    <td colspan="2"> <a href="#" >See public profile</a></td>
                              </tr>

                              </table>
                        </div>
                  </div>  
        
            </div>      

      </div>
</div>
 <div class="clear"></div>


