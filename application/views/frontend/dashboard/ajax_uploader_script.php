<script>
function escapeTags( str ) {
  return String( str )
           .replace( /&/g, '&amp;' )
           .replace( /"/g, '&quot;' )
           .replace( /'/g, '&#39;' )
           .replace( /</g, '&lt;' )
           .replace( />/g, '&gt;' );
}

window.onload = function() {

  var btn = document.getElementById('uploadBtn'),
      //progressBar = document.getElementById('progressBar'),
      //progressOuter = document.getElementById('progressOuter'),
      msgBox = document.getElementById('msgBox');

  var uploader = new ss.SimpleUpload({
        button: btn,
        url: site_url+'/dashboard/upload_photo',
        //progressUrl:site_url+'dashboard/upload_progress',
        //checkProgressInterval:100,
        //allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
        name: 'uploadfile',
		    multiple: false,
        maxUploads: 1,
		    debug: true,
        hoverClass: 'hover',
        focusClass: 'focus',
        responseType: 'json',
        startXHR: function() {
            
            //progressOuter.style.display = 'block'; // make progress bar visible
            //this.setProgressBar( progressBar );
        },
        onSubmit: function(a, b, c,d) {
		    //alert(d);
            msgBox.innerHTML = ''; // empty the message box
            btn.innerHTML = 'Uploading...'; // change button text to "Uploading..."
          },
        onComplete: function( filename, response ) {
            
            btn.innerHTML = 'Choose a Photo';
            
            if ( !response ) {
                displayUploadResponse(msgBox, 'Unable to upload photo', 'error');
                return;
            }

            if ( response.success === true ) 
            {   btn.innerHTML = 'Click here to Change Photo';
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
          },
        onError: function() {
            displayUploadResponse(msgBox, 'Unable to upload photo', 'error');
          }
	});
};

function displayUploadResponse(elm, msg, type, fname)
{
    $(elm).css('display', 'block').html(msg);
    
    if( type == 'success' )
    {
      $(elm).removeClass('error').addClass('success');
      $(".profile-image").css('display', 'block');
      $(".profile-image").find('img').attr('src', base_url+'assets/uploads/users/'+fname);
    }
    else
    {
      $(elm).removeClass('success').addClass('error');
    }

    $(elm).hide( 5000 );


}
</script>