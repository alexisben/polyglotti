<?php
$options = array(
    'name' => 'Leçons',
    'slug' => 'lessons',
    'options' => array(
        array(
            'name' => 'Fichier CSV',
            'type' => 'start'
            ),
        array(
            'name' => 'Votre fichier csv :',
            'id' => 'csv_file',
            'default'=>'',
            'type' => 'upload'
        ),
        array(
            'type' => 'end'
        ),
    ),
);