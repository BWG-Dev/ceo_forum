<?php

/*
*
* @package yariko
*
*/

namespace Ceo\Inc\Base;

class Deactivate{

    public static function deactivate(){
        flush_rewrite_rules();
    }
}
