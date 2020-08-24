<?php
// make sure to not include translations
$args['presets']['demo-1'] = array(
    'title' => 'Default',
    'demo' => 'http://demo.mythemeshop.com/ecommerce/',
    'thumbnail' => get_template_directory_uri().'/images/preset-thumb.jpg', // could use external url, to minimize theme zip size
    'menus' => array( 'primary-menu' => 'Secondary Menu', 'secondary-menu' => 'Primary Menu' ), // menu location slug => Demo menu name
    'options' => array( 'show_on_front' => 'posts', 'posts_per_page' => 4 ),
);

$args['presets']['demo-2'] = array(
    'title' => 'Demo 2',
    'demo' => 'http://demo.mythemeshop.com/ecommerce-demo2/',
    'thumbnail' => get_template_directory_uri().'/images/preset-thumb-2.jpg', // could use external url, to minimize theme zip size
    'menus' => array( 'primary-menu' => 'Secondary Menu', 'secondary-menu' => 'Primary Menu' ), // menu location slug => Demo menu name
    'options' => array( 'show_on_front' => 'posts', 'posts_per_page' => 4 ),
);

$args['presets']['demo-3'] = array(
    'title' => 'Demo 3',
    'demo' => 'http://demo.mythemeshop.com/ecommerce-demo3/',
    'thumbnail' => get_template_directory_uri().'/images/preset-thumb-3.jpg',
    'menus' => array( 'primary-menu' => 'Secondary Menu', 'secondary-menu' => 'Primary Menu' ), // menu location slug => Demo menu name
    'options' => array( 'show_on_front' => 'posts', 'posts_per_page' => 4 ),
);

$args['presets']['demo-4'] = array(
    'title' => 'Demo 4',
    'demo' => 'http://demo.mythemeshop.com/ecommerce-demo4/',
    'thumbnail' => get_template_directory_uri().'/images/preset-thumb-4.jpg',
    'menus' => array( 'primary-menu' => 'Secondary Menu', 'secondary-menu' => 'Primary Menu' ), // menu location slug => Demo menu name
    'options' => array( 'show_on_front' => 'posts', 'posts_per_page' => 4 ),
);

$args['presets']['demo-5'] = array(
    'title' => 'Demo 5',
    'demo' => 'http://demo.mythemeshop.com/ecommerce-demo5/',
    'thumbnail' => get_template_directory_uri().'/images/preset-thumb-5.jpg',
    'menus' => array( 'primary-menu' => 'Secondary Menu', 'secondary-menu' => 'Primary Menu' ), // menu location slug => Demo menu name
    'options' => array( 'show_on_front' => 'posts', 'posts_per_page' => 10 ),
);


global $mts_presets;
$mts_presets = $args['presets'];
