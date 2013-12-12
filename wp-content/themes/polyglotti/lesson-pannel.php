<?php

add_action('admin_menu', 'lesson_pannel');

function lesson_pannel() {
    add_menu_page('Leçon', 'Leçon', 'activate_plugins', 'lesson-pannel', 'render_pannel', null, 85);
};

// Load WordPress Uploader Script
function omnizz_options_enqueue_scripts() {
    wp_register_script( 'omnizz-upload', get_template_directory_uri() .'/init/js/omnizz-upload.js', array('jquery','media-upload','thickbox') );

        wp_enqueue_script('jquery');

        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');

        wp_enqueue_script('media-upload');
        wp_enqueue_script('omnizz-upload');

}

add_action('admin_enqueue_scripts', 'omnizz_options_enqueue_scripts');

function render_pannel() {

error_reporting(E_ALL|E_STRICT);

//include the following files, you may or may not need them all
//update the paths for where you put them on your server
require 'phpexcel/Classes/PHPExcel.php';
require_once 'phpexcel/Classes/PHPExcel/IOFactory.php';
require_once 'phpexcel/Classes/PHPExcel/Calculation/TextData.php';
require_once 'phpexcel/Classes/PHPExcel/Style/NumberFormat.php';

$exceldata = get_option("exceldata");

// Determine if field is not in Database
if($exceldata === FALSE)
    add_option("exceldata");

    ?>
        <?php $exceldata = get_option("exceldata"); ?>

        <!-- <form id="form-options" action="" type="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td valign='top'><label>Logo Image : </label></td>
                <td valign='top'>
                    <input type='text' id='logo_url' readonly='readonly' name='logo' size='40' value='" . esc_url( $omnizzOption ) . "' />"
                    <input id='upload_button' type='button' class='button' value='<?php _e( 'Upload Logo', 'omnizz' ); ?>' />
                    <br />
                    <em>size should be 188x69</em>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                <input name="submit" id="submit_option" type="submit" class="button button-primary" value="<?php esc_attr_e('Save Settings', 'omnizz'); ?>" />
                </td>
            <tr>
        </table>
        </form> -->
        <div class="wrap theme-option-page">
            <div id="icon-options-general" class="icon32"></div>
            <h2>Leçons</h2>
            <h3>Ici, vous pouvez ajouter votre fichier de leçon !</h3>

            <form id="lesson-options" action="" method="post" enctype="multipart/form-data">

                <input type='file' id='logo_url' readonly='readonly' name='logo' size='40' value='<?php esc_url( $exceldata ); ?>' />
                <input id='upload_button' type='submitg' class='button' value='<?php _e('Upload Leçon', 'lesson'); ?>' />


            </form>
        </div>

    <?php

    if(isset($FILES['csv']))
    {
        /*$uploaddir = '/uploads/';
        $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

        echo '<pre>';
        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
            echo "Le fichier est valide, et a été téléchargé
                   avec succès. Voici plus d'informations :\n";
        } else {
            echo "Attaque potentielle par téléchargement de fichiers.
                  Voici plus d'informations :\n";
        }

        echo 'Voici quelques informations de débogage :';
        print_r($_FILES);

        echo '</pre>'; */
        echo "HOORAY";
    }

    else
    {
       echo ('uploadez un fichier csv');
    }
    ?>

   <?php

        if(isset($_POST['csv']))
        {
            echo $_POST['csv'];
            $file = $_FILES['csv']['tmp_name'];
            echo "<h3><b>Now we are parsing this XLS file</b>:  " . $file . "</h3>";

                //location of the XLS file we are going to parse and work with
                $inputFileName =

                //loading the XLS file with the PHPExcel library
                $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

                //setup foreach to work through all the rows and columns
                $worksheet = null;
                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    //only parse data when we find the worksheet that contains it
                    $worksheetTitle = $worksheet->getTitle();
                    if ($worksheetTitle == "cso rpt")
                    {
                        $worksheetTitle = $worksheet->getTitle();
                        $highestRow = $worksheet->getHighestRow(); // e.g. 10
                        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

                        // create array to hold value of each row
                        $val=array();

                        for ($row = 3; $row <= $highestRow; $row++) {

                            $title = "";
                            $content = "";
                            $author = "";
                            $date = "";
                            $custom_field1 = "";
                            $custom_field2 = "";
                            for ($col = 0; $col < $highestColumnIndex; $col++) {

                                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                $value = $cell->getValue();
                                switch ($col)
                                {   // assign a var to each call form each column so we can work with them
                                case 0:
                                    $title = $value;
                                break;
                                case 1:
                                    $content = $value;
                                break;
                                case 2:
                                    $author = $value;
                                break;
                                case 3:
                                    $date = $value;
                                break;
                                case 4:
                                    $custom_field1 = $value;
                                break;
                                case 5:
                                    $custom_field2 = $value;
                                }
                            }

                            if (!$code || strlen(trim($code)) == 0)
                                break;

                            // get the date from cell B:1
                            $date = $worksheet->getCellByColumnAndRow(1,1);
                            //the Excel formatted date coming back from the cell is an object, convert it to a string
                            $dateint = intval($date->__toString());
                            //now we have to convert that string to an integer
                            $dateintVal = (int) $dateint;
                            //now we format that date in mm/dd/yyyy format to make the post titles
                            $newdate = PHPExcel_Style_NumberFormat::toFormattedString($dateintVal, "MM/DD/YYYY");
                            //WordPress requires Y-m-d H:i:s for post_date so we have to reformat for that
                            $wpPostDate = PHPExcel_Style_NumberFormat::toFormattedString($dateintVal, "YYYY/MM/DD");

                            // details for the post we're about to insert
                            $my_post = array(
                              'post_title'  => $title,
                              'post_date'   => $wpPostDate,
                              'post_content' => $content,
                              'post_status' => 'publish',
                              'post_author' => 1
                            );

                            // Inserts and publishes a new post into the database
                            $postid = wp_insert_post( $my_post );

                            // add the custom fields for each post
                            add_post_meta($postid, 'custom_field1', $custom_field1);
                            add_post_meta($postid, 'custom_field2', $custom_field2);
                            add_post_meta($postid, 'date', $newdate);

                            // set the newly created post to the CSO Code category
                            wp_set_object_terms($postid, 'products', 'category');

                            echo "<i><b>Successfully inserted post " . $postid . " to the WordPress DB</i></b><br /><br />";
                        }

                        //move the files to an /XLS-archive folder just in case needed in the future
                        //then delete the copy in the /XLS folder so it's not parsed again in the future
                        if (copy( "../XLSdir/" . basename($file),"../XLS-archive/" . basename($file) )) {
                          unlink( "../XLSdir/" . basename($file) );
                        }

                        break;
                    }
                }
            }

            else
            {
                ?>
                <p> Rien d'uploadé </p>
                <?php
            }

            exit;

}
