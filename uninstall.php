<?php

//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

defined("FIVESTERPLUGIN_SLUG__") || define("FIVESTERPLUGIN_SLUG__", "__fivesterrenspecialist_");

$opts   = wp_load_alloptions();
foreach($opts as $key=>$value){
    if(strpos($key, FIVESTERPLUGIN_SLUG__) === 0) delete_option($key);
}