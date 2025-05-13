<?php

/*
*
* @package Yariko
*
*/

namespace Ceo\Inc\Base;

class Ajax{

    public function register(){

        /**
         * Ajax actions
         */
        add_action( 'wp_ajax_action', array($this,'action'));
        add_action( 'wp_ajax_nopriv_action', array($this,'action'));



    }

    /**
     * Get the ffl dealers
     */
    function action(){

        echo json_encode(array('success' => true));
        wp_die();
    }


}