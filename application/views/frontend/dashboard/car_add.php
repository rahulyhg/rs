
<?php echo $tmenu;?>

<div class="clear"></div>

<div class="cont">
    <div class="l_cont">
        <?php echo $smenu;?>
    </div>

    

    <div class="inner_cont">
    	<h1 style="margin-top:0px;">Your car details</h1>
        

        <form action="" method="post" >

            <div class="basic-grey">
                <div class="left">
                    <label>Make</label>
                </div>
                <div class="right">
                    <?php 
                    $make_id = set_value('make_id', $car['make_id']);
                    echo form_dropdown('make_id', get_makes(), $make_id);?>
                    <?php echo form_error('make_id', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Model</label>
                </div>
                <div class="right">
                    <?php echo form_dropdown('model_id', get_models($make_id, false), set_value('model_id', $car['model_id']));?>
                    <?php echo form_error('model_id', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            
            <div class="basic-grey">
                <div class="left">
                    <label>Comfort</label>
                </div>
                <div class="right">
                    <?php echo form_dropdown('comfort_id', get_comforts($make_id), set_value('comfort_id', $car['comfort_id']));?>
                    <?php echo form_error('comfort_id', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Number of seats</label>
                </div>
                <div class="right">
                    <input type="text" name="seat_count" id="seat_count" value="<?php echo set_value('seat_count', $car['seat_count']);?>">
                    <?php echo form_error('seat_count', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Colour</label>
                </div>
                <div class="right">
                    <?php echo form_dropdown('colour_id', get_colours(), set_value('colour_id', $car['colour_id']));?>
                    <?php echo form_error('colour_id', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    <label>Type</label>
                </div>
                <div class="right">
                    <?php echo form_dropdown('type_id', get_types(), set_value('type_id', $car['type_id']));?>
                    <?php echo form_error('type_id', '<span class="error_text">', '</span>'); ?>
                </div>
            </div>

            <div class="basic-grey">
                <div class="left">
                    &nbsp;
                </div>
                <div class="right">
                    <input type="submit" class="button" value="Add"/>
                </div>
            </div>

               
        </form>

    </div>
</div>

<div class="clear"></div>

<script type="text/javascript">
$(function(){
    $('select[name="make_id"]').on('change', function(){
        $.ajax({
            url:site_url+'dashboard/profile/car/get_models/'+this.value,
            type:'POST',
            dataType:'json',
            success:function(resp)
            {
                $('select[name="model_id"]').html('');
                $.each(resp.content, function(){

                    $('select[name="model_id"]')
                     .append($("<option></option>")
                     .attr("value",this.id)
                     .text(this.value));
                });
              // $('select[name="make_id"]').html(resp.content); 
            }
        });
    })
});
</script>

