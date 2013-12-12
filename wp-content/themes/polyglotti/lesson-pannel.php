<?php

add_action('admin_menu', 'lesson_pannel');

function lesson_pannel() {
    add_menu_page('Leçon', 'Leçon', 'activate_plugins', 'lesson-pannel', 'render_pannel', null, 85);
};



function render_pannel() {

    if(isset($_FILES['csv']))
    {
        if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
        $uploadedfile = $_FILES['csv'];
        $upload_overrides = array( 'test_form' => false );
        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

        if ( $movefile ) {
            $wp_filetype = $movefile['type'];
            $filename = $movefile['file'];
            $wp_upload_dir = wp_upload_dir();
            $attachment = array(
                'guid' => $wp_upload_dir['url'] . '/' . basename( $filename ),
                'post_mime_type' => $wp_filetype,
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id = wp_insert_attachment( $attachment, $filename);
        }
    }
    ?>
    <div class="wrap theme-option-page">
        <div id="icon-options-general" class="icon32"></div>
        <h2>Leçons</h2>
        <h3>Ici, vous pouvez ajouter votre fichier de leçon !</h3>

        <form id="lesson-options" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
            <input type='file' id='csv' name='csv' size='40' value='' />
            <input id='upload_button' type='submit' class='button' value='<?php _e('Upload Leçon', 'lesson'); ?>' />
        </form>
    </div>

<?php
    $upload_dir = wp_upload_dir();
    $path = $upload_dir['path'];

    $latest_ctime = 0;
    $latest_filename = '';

    $d = dir($path);
    while (false !== ($entry = $d->read())) {
        $filepath = "{$path}/{$entry}";
        if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
            $latest_ctime = filectime($filepath);
            $latest_filename = $entry;
        }
    }
    var_dump($latest_filename);

    $file = get_attached_file($latest_filename);
}

