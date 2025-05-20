<?php

/*
*
* @package yariko
*
*/
namespace Ceo\Inc\Base;

class Activate{

    public static function activate(){
        // Superuser role – inherit everything from administrator
        if (!get_role('superuser')) {
            add_role('superuser', 'Superuser', get_role('administrator')->capabilities);
        }

        // Admin Level 2 role
        if (!get_role('admin_level_2')) {
            add_role('admin_level_2', 'Admin Level 2', [
                'read' => true,
                'list_users' => true,
                'edit_users' => true,
                'create_users' => true,
                'delete_users' => true,
                'promote_users' => true,
                'edit_pages' => true,
                'edit_others_pages' => true,
                'edit_published_pages' => true,
                'read_private_pages' => true,
                'edit_posts' => false,
                'activate_plugins' => false,
                'edit_plugins' => false,
                'edit_theme_options' => false,
                'install_plugins' => false,
                'manage_options' => false,
                'edit_files' => false,
            ]);
        }
    }
}
