<?php

add_action('admin_menu', 'lesson_pannel');
//include the following files, you may or may not need them all
//update the paths for where you put them on your server
require 'phpexcel/Classes/PHPExcel.php';
require_once 'phpexcel/Classes/PHPExcel/IOFactory.php';
require_once 'phpexcel/Classes/PHPExcel/Calculation/TextData.php';
require_once 'phpexcel/Classes/PHPExcel/Style/NumberFormat.php';

function lesson_pannel() {
    add_menu_page('Polyglotti', 'Polyglotti', 'activate_plugins', 'lesson-pannel', 'render_pannel', null, 85);
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
            <input id='upload_button' type='submit' name='submit' class='button' value='<?php _e('Upload Leçon', 'lesson'); ?>' />
        </form>
    </div>

<?php
    if(isset($_POST['submit'])) {
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
    $maxlessonId = null;

    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        for ($row = 2; $row <= $highestRow; $row++) {

            $lesson = ''; $fr = ''; $fr_audio = ''; $fr_number = ''; $fr_people = ''; $fr_time = ''; $fr_quality = ''; $fr_actions = ''; $fr_space = ''; $fr_negative = ''; $fr_objects = ''; $fr_questions = ''; $en = ''; $en_audio = ''; $en_number = ''; $en_people = ''; $en_time = ''; $en_quality = ''; $en_actions = ''; $en_space = ''; $en_negative = ''; $en_objects = ''; $en_questions = ''; $ch = ''; $ch_audio = ''; $ch_number = ''; $ch_people = ''; $ch_time = ''; $ch_quality = ''; $ch_actions = ''; $ch_space = ''; $ch_negative = ''; $ch_objects = ''; $ch_questions = ''; $pin = ''; $pin_audio = ''; $pin_number = ''; $pin_people = ''; $pin_time = ''; $pin_quality = '';$pin_actions = ''; $pin_space = ''; $pin_negative = ''; $pin_objects = ''; $pin_questions = ''; $es = ''; $es_audio = ''; $es_number = ''; $es_people = ''; $es_time = ''; $es_quality = ''; $es_actions = ''; $es_space = ''; $es_negative = ''; $es_objects = ''; $es_questions = ''; $hin = ''; $hin_audio = ''; $hin_number = '';$hin_people = ''; $hin_time = ''; $hin_quality = ''; $hin_actions = ''; $hin_space = ''; $hin_negative = ''; $hin_objects = ''; $hin_questions = ''; $ru = ''; $ru_audio = ''; $ru_number = ''; $ru_people = ''; $ru_time = ''; $ru_quality = ''; $ru_actions = ''; $ru_space = ''; $ru_negative = ''; $ru_objects = ''; $ru_questions = ''; $po = ''; $po_audio = ''; $po_number = ''; $po_people = ''; $po_time = ''; $po_quality = ''; $po_actions = ''; $po_space = ''; $po_negative = ''; $po_objects = '';$po_questions = ''; $ar = ''; $ar_audio = ''; $ar_number = ''; $ar_people = ''; $ar_time = ''; $ar_quality = ''; $ar_actions = ''; $ar_space = ''; $ar_negative = '';$ar_objects = ''; $ar_questions = ''; $de = ''; $de_audio = ''; $de_number = ''; $de_people = ''; $de_time = ''; $de_quality = ''; $de_actions = ''; $de_negative = ''; $de_objects = ''; $de_questions = ''; $it = ''; $it_audio = ''; $it_number = ''; $it_people = ''; $it_time = ''; $it_quality = ''; $it_actions = ''; $it_space = '';$it_negative = ''; $it_objects = ''; $it_questions = '';

            for ($col = 0; $col < $highestColumnIndex; $col++) {

                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                $value = $cell->getValue();
                // echo $value . '<br />';
                switch ($col)
                {   // assign a var to each call form each column so we can work with them
                    case 0 : $lesson = $value; break; case 1 : $fr = $value; break; case 2 : $fr_audio = $value; break; case 3 : $fr_number = $value; break; case 4 : $fr_people = $value; break; case 5 : $fr_time = $value; break; case 6 : $fr_quality = $value; break; case 7 : $fr_actions = $value; break; case 8 : $fr_space = $value; break; case 9 : $fr_negative = $value; break; case 10 : $fr_objects = $value; break; case 11 : $fr_questions = $value; break; case 12 : $en = $value; break; case 13 : $en_audio = $value; break; case 14 : $en_number = $value; break; case 15 : $en_people = $value; break; case 16 : $en_time = $value; break; case 17 : $en_quality = $value; break; case 18 : $en_actions = $value; break; case 19 : $en_space = $value; break; case 20 : $en_negative = $value; break; case 21 : $en_objects = $value; break; case 22 : $en_questions = $value; break; case 23 : $ch = $value; break; case 24 : $ch_audio = $value; break; case 25 : $ch_number = $value; break; case 26 : $ch_people = $value; break; case 27 : $ch_time = $value; break; case 28 : $ch_quality = $value; break; case 29 : $ch_actions = $value; break; case 30 : $ch_space = $value; break; case 31 : $ch_negative = $value; break; case 32 : $ch_objects = $value; break; case 33 : $ch_questions = $value; break; case 34 : $pin = $value; break; case 35 : $pin_audio = $value; break; case 36 : $pin_number = $value; break; case 37 : $pin_people = $value; break; case 38 : $pin_time = $value; break; case 39 : $pin_quality = $value; break; case 40 : $pin_actions = $value; break; case 41 : $pin_space = $value; break; case 42 : $pin_negative = $value; break; case 43 : $pin_objects = $value; break; case 44 : $pin_questions = $value; break; case 45 : $es = $value; break; case 46 : $es_audio = $value; break; case 47 : $es_number = $value; break; case 48 : $es_people = $value; break; case 49 : $es_time = $value; break; case 50 : $es_quality = $value; break; case 51 : $es_actions = $value; break; case 52 : $es_space = $value; break; case 53 : $es_negative = $value; break; case 54 : $es_objects = $value; break; case 55 : $es_questions = $value; break; case 56 : $hin = $value; break; case 57 : $hin_audio = $value; break; case 58 : $hin_number = $value; break; case 59 : $hin_people = $value; break; case 60 : $hin_time = $value; break; case 61 : $hin_quality = $value; break; case 62 : $hin_actions = $value; break; case 63 : $hin_space = $value; break; case 64 : $hin_negative = $value; break; case 65 : $hin_objects = $value; break; case 66 : $hin_questions = $value; break; case 67 : $ru = $value; break; case 68 : $ru_audio = $value; break; case 69 : $ru_number = $value; break; case 70 : $ru_people = $value; break; case 71 : $ru_time = $value; break; case 72 : $ru_quality = $value; break; case 73 : $ru_actions = $value; break; case 74 : $ru_space = $value; break; case 75 : $ru_negative = $value; break; case 76 : $ru_objects = $value; break; case 77 : $ru_questions = $value; break; case 78 : $po = $value; break; case 79 : $po_audio = $value; break; case 80 : $po_number = $value; break; case 81 : $po_people = $value; break; case 82 : $po_time = $value; break; case 83 : $po_quality = $value; break; case 84 : $po_actions = $value; break; case 85 : $po_space = $value; break; case 86 : $po_negative = $value; break; case 87 : $po_objects = $value; break; case 88 : $po_questions = $value; break; case 89 : $ar = $value; break; case 90 : $ar_audio = $value; break; case 91 : $ar_number = $value; break; case 92 : $ar_people = $value; break; case 93 : $ar_time = $value; break; case 94 : $ar_quality = $value; break; case 95 : $ar_actions = $value; break; case 96 : $ar_space = $value; break; case 97 : $ar_negative = $value; break; case 98 : $ar_objects = $value; break; case 99 : $ar_questions = $value; break; case 100 : $de = $value; break; case 101 : $de_audio = $value; break; case 102 : $de_number = $value; break; case 103 : $de_people = $value; break; case 104 : $de_time = $value; break; case 105 : $de_quality = $value; break; case 106 : $de_actions = $value; break; case 107 : $de_negative = $value; break; case 108 : $de_objects = $value; break; case 109 : $de_questions = $value; break; case 110 : $it = $value; break; case 111 : $it_audio = $value; break; case 112 : $it_number = $value; break; case 113 : $it_people = $value; break; case 114 : $it_time = $value; break; case 115 : $it_quality = $value; break; case 116 : $it_actions = $value; break; case 117 : $it_space = $value; break; case 118 : $it_negative = $value; break; case 119 : $it_objects = $value; break; case 120 : $it_questions = $value; break;
                }
            }

            if(!$lesson == "") {


            echo'<br />';

            // details for the post we're about to insert
            $my_post = array(
              'post_title'            => $fr,
              'post_status'           => 'publish',
              'post_type'             => 'phrases',
              'post_author'           => $user_ID,
              'ping_status'           => get_option('default_ping_status'),
              'post_parent'           => 0,
              'menu_order'            => 0,
              'lang'                  => 'fr',
              'to_ping'               =>  '',
              'pinged'                => '',
              'post_password'         => '',
              'guid'                  => '',
              'post_content_filtered' => '',
              'post_excerpt'          => '',
              'import_id'             => 0
            );

            $phrase_data = array(
                'lesson' => $lesson,

                // Français
                'fr' => $fr,
                'fr_audio' => $fr_audio,
                'fr_number' => $fr_number,
                'fr_people' => $fr_people,
                'fr_time' => $fr_time,
                'fr_quality' => $fr_quality,
                'fr_actions' => $fr_actions,
                'fr_space' => $fr_space,
                'fr_negative' => $fr_negative,
                'fr_objects' => $fr_objects,
                'fr_questions' => $fr_questions,

                // Anglais
                'en' => $en,
                'en_audio' => $en_audio,
                'en_number' => $en_number,
                'en_people' => $en_people,
                'en_time' => $en_time,
                'en_quality' => $en_quality,
                'en_actions' => $en_actions,
                'en_space' => $en_space,
                'en_negative' => $en_negative,
                'en_objects' => $en_objects,
                'en_questions' => $en_questions,

                // Chinois
                'ch' => $ch,
                'ch_audio' => $ch_audio,
                'ch_number' => $ch_number,
                'ch_people' => $ch_people,
                'ch_time' => $ch_time,
                'ch_quality' => $ch_quality,
                'ch_actions' => $ch_actions,
                'ch_space' => $ch_space,
                'ch_negative' => $ch_negative,
                'ch_objects' => $ch_objects,
                'ch_questions' => $ch_questions,

                // Chinois - Pinyin
                'pin' => $pin,
                'pin_audio' => $pin_audio,
                'pin_number' => $pin_number,
                'pin_people' => $pin_people,
                'pin_time' => $pin_time,
                'pin_quality' => $pin_quality,
                'pin_actions' => $pin_actions,
                'pin_space' => $pin_space,
                'pin_negative' => $pin_negative,
                'pin_objects' => $pin_objects,
                'pin_questions' => $pin_questions,

                // Espagnol
                'es' => $es,
                'es_audio' => $es_audio,
                'es_number' => $es_number,
                'es_people' => $es_people,
                'es_time' => $es_time,
                'es_quality' => $es_quality,
                'es_actions' => $es_actions,
                'es_space' => $es_space,
                'es_negative' => $es_negative,
                'es_objects' => $es_objects,
                'es_questions' => $es_questions,

                // Hindi
                'hin' => $hin,
                'hin_audio' => $hin_audio,
                'hin_number' => $hin_number,
                'hin_people' => $hin_people,
                'hin_time' => $hin_time,
                'hin_quality' => $hin_quality,
                'hin_actions' => $hin_actions,
                'hin_space' => $hin_space,
                'hin_negative' => $hin_negative,
                'hin_objects' => $hin_objects,
                'hin_questions' => $hin_questions,

                // Russe
                'ru' => $ru,
                'ru_audio' => $ru_audio,
                'ru_number' => $ru_number,
                'ru_people' => $ru_people,
                'ru_time' => $ru_time,
                'ru_quality' => $ru_quality,
                'ru_actions' => $ru_actions,
                'ru_space' => $ru_space,
                'ru_negative' => $ru_negative,
                'ru_objects' => $ru_objects,
                'ru_questions' => $ru_questions,

                // Portugais
                'po' => $po,
                'po_audio' => $po_audio,
                'po_number' => $po_number,
                'po_people' => $po_people,
                'po_time' => $po_time,
                'po_quality' => $po_quality,
                'po_actions' => $po_actions,
                'po_space' => $po_space,
                'po_negative' => $po_negative,
                'po_objects' => $po_objects,
                'po_questions' => $po_questions,

                // Arabe
                'ar' => $ar,
                'ar_audio' => $ar_audio,
                'ar_number' => $ar_number,
                'ar_people' => $ar_people,
                'ar_time' => $ar_time,
                'ar_quality' => $ar_quality,
                'ar_actions' => $ar_actions,
                'ar_space' => $ar_space,
                'ar_negative' => $ar_negative,
                'ar_objects' => $ar_objects,
                'ar_questions' => $ar_questions,

                // Allemand
                'de' => $de,
                'de_audio' => $de_audio,
                'de_number' => $de_number,
                'de_people' => $de_people,
                'de_time' => $de_time,
                'de_quality' => $de_quality,
                'de_actions' => $de_actions,
                'de_space' => $de_space,
                'de_negative' => $de_negative,
                'de_objects' => $de_objects,
                'de_questions' => $de_questions,

                 // Italien
                'it' => $it,
                'it_audio' => $it_audio,
                'it_number' => $it_number,
                'it_people' => $it_people,
                'it_time' => $it_time,
                'it_quality' => $it_quality,
                'it_actions' => $it_actions,
                'it_space' => $it_space,
                'it_negative' => $it_negative,
                'it_objects' => $it_objects,
                'it_questions' => $it_questions
            );

            $postid = wp_insert_post($my_post);

            foreach($phrase_data as $key => $value)
            {
                add_post_meta($postid, $key, $value);
            }

            $maxlessonId = $lesson;
            print_r($phrase_data);
            echo "<i><b>Successfully inserted post " . $postid . " to the WordPress DB</i></b><br /><br />";
        }
        }
        //break;

    }
    echo $maxlessonId;
    generate_lesson($maxlessonId);
    exit;
}
