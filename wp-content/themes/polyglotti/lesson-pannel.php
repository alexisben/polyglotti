<?php

add_action('admin_menu', 'lesson_pannel');
//include the following files, you may or may not need them all
//update the paths for where you put them on your server
require 'phpexcel/Classes/PHPExcel.php';
require_once 'phpexcel/Classes/PHPExcel/IOFactory.php';
require_once 'phpexcel/Classes/PHPExcel/Calculation/TextData.php';
require_once 'phpexcel/Classes/PHPExcel/Style/NumberFormat.php';

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
    parse_csv($latest_filename);
}

function parse_csv($file) {

    //dir the XLS files are located in for parsing
    $upload_dir = wp_upload_dir();
    $file_dir = $upload_dir['path']."/".$file;

    // Open the directory with XLS files, and proceed to read its contents
    echo "<h3><b>Traitement du fichier</b>:  " . $file . "</h3>";

    //location of the XLS file we are going to parse and work with
    $inputFileName = $file_dir;

    //loading the XLS file with the PHPExcel library
    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
    //setup foreach to work through all the rows and columns
    $worksheet = null;

    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        for ($row = 1; $row <= $highestRow; $row++) {

            $lesson_id = "";
            $french = "";
            $chinese = "";
            $pinyin = "";
            $english = "";

            for ($col = 0; $col < $highestColumnIndex; $col++) {

                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                $value = $cell->getValue();
                // echo $value . '<br />';
                switch ($col)
                {   // assign a var to each call form each column so we can work with them
                    case 0:
                        $lesson_id = $value;
                    break;
                    case 1:
                        $french = $value;
                    break;
                    case 2:
                        $chinese = $value;
                    break;
                    case 3:
                        $pinyin = $value;
                    break;
                    case 5:
                        $english = $value;
                    break;
                }
            }

            echo'<br />';
            // if (!$code || strlen(trim($code)) == 0)
            //     break;

            // details for the post we're about to insert
            $my_post = array(
              'id'  => $lesson_id,
              'french'   => $french,
              'chinese' => $chinese,
              'pinyin' => $pinyin,
              'english' => $english
            );

            print_r($my_post);

            // Inserts and publishes a new post into the database
            // $postid = wp_insert_post( $my_post );

            // // add the custom fields for each post
            // add_post_meta($postid, 'custom_field1', $custom_field1);
            // add_post_meta($postid, 'custom_field2', $custom_field2);
            // add_post_meta($postid, 'date', $newdate);

            // // set the newly created post to the CSO Code category
            // wp_set_object_terms($postid, 'products', 'category');

            echo "<i><b>Successfully inserted post " . $postid . " to the WordPress DB</i></b><br /><br />";
        }
        break;

    }

    exit;
}
