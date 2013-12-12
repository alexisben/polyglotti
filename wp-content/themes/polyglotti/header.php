<?php if(isset($_GET['ajax'])) return; ?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?> class="no-js" xmlns="http://www.w3.org/1999/html">
<!--<![endif]-->


<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=1280" />

    <meta name="description" content="Polyglotti" />


    <title><?php

        // Add the blog name.
        bloginfo( 'name' );

        // Add the blog description for the home/front page.
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) )
            echo " | $site_description";


        ?></title>
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_directory' ); ?>/stylesheets/screen.css" />

    <link type="text/css" rel="stylesheet" href="http://fast.fonts.net/cssapi/225f6939-296a-402d-8c59-992348ea079a.css"/>

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <?php wp_head(); ?>
    <script type="text/javascript">
        $('html').removeClass('no-js');
    </script>
</head>
<body>
    <div class="wrapper">
        <header>
<!--            <a href="--><?php //echo home_url(); ?><!--" id="logo"><img src="--><?php //bloginfo( 'stylesheet_directory' ); ?><!--/images/icons/logo.jpg">POLYGLOTTI</a>-->
            <nav>

            </nav>
        </header>

