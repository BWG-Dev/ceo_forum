<?php
/*
*
* @package yariko


Plugin Name:  Web Bennett Group Core
Plugin URI:   https://thomasgbennett.com/
Description:  Contain all the logic to integrate DonorPerfect to the CEO Forum enviroment
Version:      1.0.0
Author:       Web Bennett Group
Author URI:   https://thomasgbennett.com/
Tested up to: 5.3.2
Text Domain:  ceo_tgb_core
Domain Path:  /languages
*/

defined('ABSPATH') or die('You do not have access, sally human!!!');

define ( 'CEO_PLUGIN_VERSION', '1.0.0');

if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php') ){
    require_once  dirname( __FILE__ ) . '/vendor/autoload.php';
}


define('CEO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define('CEO_PLUGIN_URL' , plugin_dir_url(  __FILE__  ) );
define('CEO_ADMIN_URL' , get_admin_url() );
define('CEO_PLUGIN_DIR_BASENAME' , dirname(plugin_basename(__FILE__)) );

//include the helpers
include 'inc/util/helpers.php';

if( class_exists( 'Ceo\\Inc\\Init' ) ){
    register_activation_hook( __FILE__ , array('Ceo\\Inc\\Base\\Activate','activate') );
    Ceo\Inc\Init::register_services();
}



