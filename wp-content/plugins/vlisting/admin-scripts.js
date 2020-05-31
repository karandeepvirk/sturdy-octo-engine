jQuery(document).ready(function($){
    // Instantiates the variable that holds the media library frame.
    var meta_image_frame;
    $('#upload_image_btn').click(function(e){
        e.preventDefault();
        if ( meta_image_frame ) {
            meta_image_frame.open();
            return;
        }
        meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
            title: meta_image.title,
            button: { text:  meta_image.button },
            library: { type: 'image' }
        });
        meta_image_frame.on('select', function(){
            var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
            $('#txt_upload_image').val(media_attachment.url);
            $('#img_upload_image').attr('src',media_attachment.url);
        });
        meta_image_frame.open();
    });
});