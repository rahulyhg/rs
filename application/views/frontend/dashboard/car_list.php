
<?php echo $tmenu;?>

<div class="clear"></div>

<div class="cont">
    <div class="l_cont">
        <?php echo $smenu;?>
    </div>

    

    <div class="inner_cont">
    	<h1 style="margin-top:0px;">Your car details</h1>
       
        <?php if(count($cars)): ?>
        <table>
            <tbody>
                <?php 

                $makes  = get_makes();
                $models = get_models();
                $types  = get_types();

                foreach ($cars as $car)
                {

                    $img = $car['img_name']?'assets/uploads/cars/'.$car['img_name']:'assets/frontend/images/car.png';
                    $btnText = $car['img_name']?'Click here to Change Photo':'Upload Photo';

                    $details = '<p>';
                    $details .= $makes[$car['make_id']].' '.$models[$car['make_id']][$car['model_id']];
                    $details .= '<br/>'.$car['seat_count'].' seats';
                    $details .= '<br/><a href="'.site_url('dashboard/profile/car/edit/'.$car['id']).'"><img src="'.include_img_path().'/edit.png" width="15px"></a>';
                    $details .= '&nbsp;&nbsp;&nbsp;<a href="'.site_url('dashboard/profile/car/delete/'.$car['id']).'"><img src="'.include_img_path().'/del.png" width="15px"></a>';
                    
                    echo '<tr>';
                        echo '<td> <img width="120px" id="car-'.$car['id'].'" src="'.base_url($img).'" /></td>';
                        echo '<td>'.$details.'</td>';
                    echo '</tr>';

                    echo '<tr>';
                        echo '  <td colspan="2">
                                    <button id="'.$car['id'].'" class="button uploadBtn">'.$btnText.'</button>
                                    <div id="div-'.$car['id'].'" style="display:none;"></div>
                                </td>';
                    echo '</tr>';

                } ?>
                
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<div class="clear"></div>

<script type="text/javascript">
    $(function(){
        
        $.fn.sUpload = function(options) {
        
            var defaults = {};
            var settings = $.extend(defaults, options);

            return this.each(function() {
                var self = this;
                settings.button = self;
                settings.url = site_url+'dashboard/upload_car/'+self.id;
                settings.name = 'uploadfile';
                settings.responseType = 'json';
                settings.onComplete = function( filename, response ) {
            
                    self.innerHTML = 'Upload Photo';
                    
                    var msgBox = 'div-'+self.id;

                    if ( !response ) 
                    {
                        displayUploadResponse(msgBox, 'Unable to upload photo', 'error');
                        return;
                    }

                    if ( response.success === true ) 
                    {   self.innerHTML = 'Click here to Change Photo';
                        $('#car-'+self.id).attr('src', base_url+'assets/uploads/cars/'+response.fname);
                        displayUploadResponse(msgBox, 'Uploaded Successfully', 'success', response.fname);                        
                    }
                    else 
                    {
                        if ( response.msg )  
                        {
                          response.msg = escapeTags( response.msg );
                          displayUploadResponse(msgBox, response.msg, 'error');
                        } 
                        else 
                        {
                            displayUploadResponse(msgBox, 'An error occurred and the upload failed.', 'error');
                        }
                    } 
                  };
                settings.onError = function() {
                    
                  };

                new ss.SimpleUpload(settings);
            });
        };

        $('.uploadBtn').sUpload();

        function displayUploadResponse(elm, msg, type, fname)
{
            $(elm).css('display', 'block').html(msg);
            
            if( type == 'success' )
            {
              $(elm).removeClass('error').addClass('success');
            }
            else
            {
              $(elm).removeClass('success').addClass('error');
            }

            $(elm).hide( 5000 );


        }

        function escapeTags( str ) 
        {
          return String( str )
                   .replace( /&/g, '&amp;' )
                   .replace( /"/g, '&quot;' )
                   .replace( /'/g, '&#39;' )
                   .replace( /</g, '&lt;' )
                   .replace( />/g, '&gt;' );
        }

    });

    
</script>


