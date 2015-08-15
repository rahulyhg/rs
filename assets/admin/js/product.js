
var upld_options = {};
    upld_options.uploadUrl = base_url+'admin/uploads/do_upload';
    upld_options.allowedFileExtensions = ['jpg','jpeg','png','gif'];
    upld_options.maxFileSize = 1000;
    upld_options.showCaption = false;
    upld_options.dropZoneEnabled = false;
    upld_options.showRemove = false;
    if(prv_img != '')
        upld_options.initialPreview = [prv_img];
    upld_options.uploadExtraData = {'upload_folder':'products','types':'gif|jpg|png|jpeg'}, 

    upld_options.slugCallback = function(filename) {
            //alert(filename);
            return filename.replace('(', '_').replace(']', '_');
        };

    $("#upload_image").fileinput(upld_options);

    $(document).ready(function() {

        $('#upload_image').on('fileuploaded', function(event, data, previewId, index) {

            var form = data.form, files = data.files, extra = data.extra,
            response = data.response, reader = data.reader;

            $("#product_image").val(response.fileuploaded);       
        });
    });

