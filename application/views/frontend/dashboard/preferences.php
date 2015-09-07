<?php echo validation_errors();?>

<?php echo $tmenu;?>

<div class="clear"></div>

<div class="cont">
    <div class="l_cont">
        <?php echo $smenu;?>
    </div>

    

    <div class="inner_cont">
    	<h1 style="margin-top:0px;">Your ridesharing preferences</h1>
        <hr/>
        
        <div class="prefer">
            <form action="" method="post" >

                <div class="basic-grey">
                    <div class="left">
                        <label>Chattiness</label>
                    </div>
                    <div class="right">
                        <input type="radio"  name="chattiness" value="1" > I'm Quiet type
                        <input type="radio"  name="chattiness" value="2" checked> I talk depending on mood
                        <input type="radio"  name="chattiness" value="3" > I love to chat
                    </div>
                </div>

                <div class="basic-grey">
                    <div class="left">
                        <label>Smoking</label>
                    </div>
                    <div class="right">
                        <input type="radio"  name="smoking" value="1" > No Smoking Please
                        <input type="radio"  name="smoking" value="2" checked> OK sometimes
                        <input type="radio"  name="smoking" value="3" > doen't bother me
                    </div>
                </div>

                <div class="basic-grey">
                    <div class="left">
                        <label>Pets</label>
                    </div>
                    <div class="right">
                        <input type="radio"  name="pets" value="1" > No Pets Please
                        <input type="radio"  name="pets" value="2" checked> Depends on the animal
                        <input type="radio"  name="pets" value="3" > Pets welcome
                    </div>
                </div>

                <div class="basic-grey">
                    <div class="left">
                        <label>Music</label>
                    </div>
                    <div class="right">
                        <input type="radio"  name="music" value="1" > Silence is golden
                        <input type="radio"  name="music" value="2" checked> I listen to music if i fancy it
                        <input type="radio"  name="music" value="3" > Its all about the playlist
                    </div>
                </div>
                

                <div class="basic-grey">
                    <div class="left">
                        &nbsp;
                    </div>
                    <div class="right">
                        <input type="submit" class="button" value="Save"/>
                    </div>
                </div>

                   
            </form>
        </div>

    </div>
</div>

<div class="clear"></div>


