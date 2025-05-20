<?php

/*
*
* @package Yariko
*
*/

namespace Ceo\Inc\Base;

class Enqueue{

    public function register(){

        add_action( 'wp_enqueue_scripts',  array($this,'ceo_enqueue_frontend'));
    
    }

    /**
     * Enqueueing the main scripts with all the javascript logic that this plugin offer
     */
    function ceo_enqueue_frontend(){
        wp_enqueue_style('main-css', CEO_PLUGIN_URL . '/assets/css/main.css');
      /*  wp_enqueue_script('main-js', CEO_PLUGIN_URL  . '/assets/js/main.js' ,array('jquery'),'1.0', false);
     
        wp_localize_script( 'main-js', 'parameters', ['ajax_url'=> admin_url('admin-ajax.php'), 'plugin_url' => CEO_PLUGIN_URL]);*/
 
    }

}
