<?php
add_theme_support('post-thumbnails');
add_action( 'admin_enqueue_scripts', 'image_uploader_enqueue');
add_action('init', 'start_my_session');
function image_uploader_enqueue() {
    global $typenow;
    if( ($typenow == 'menu') ) {
        wp_enqueue_media();

        wp_register_script( 'meta-image', get_template_directory_uri() . '/js/media-uploader.js', array( 'jquery' ) );
        wp_localize_script( 'meta-image', 'meta_image',
            array(
                'title' => 'Upload Item Image',
                'button' => 'Use this Image',
            )
        );
        wp_enqueue_script( 'meta-image' );
    }
}
function start_my_session(){
    if(!session_id()){
        session_start();
    }
}
?>