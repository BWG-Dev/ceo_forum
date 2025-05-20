<?php

/*
*
* @package Yariko
*
*/

namespace Ceo\Inc\Base;

class Settings{

    public function register(){
        // Disable registration via wp-login.php
        add_filter('option_users_can_register', '__return_false');

        // Remove registration URL from login screen
        add_filter('register_url', '__return_empty_string');

        // Redirect attempts to access registration page
        add_action('login_init', array($this, 'redirect_registration'));

        //Defining the user capabilities for the role admin_level_2
        if( is_admin()){
            add_action( 'pre_get_users', array($this, 'limit_user_listing_for_admin_level_2') );
            add_filter( 'editable_roles', array($this, 'restrict_admin_level_2_editable_roles') );
            add_action( 'admin_head', array($this, 'custom_hide_roles_from_user_filter') );
            add_filter( 'user_switching_user_row_actions', array($this, 'restrict_user_switching_for_specific_roles'), 10, 2 );
        }

        //Creating/Saving the extra fields - previously handle by the user registration plugin and its addons
        add_action('show_user_profile', array($this, 'ceo_show_extra_user_profile_fields'));
        add_action('edit_user_profile', array($this, 'ceo_show_extra_user_profile_fields'));
        add_action('user_new_form', array($this, 'ceo_show_extra_user_profile_fields'));
        add_action('personal_options_update', array($this, 'ceo_save_extra_user_profile_fields'));
        add_action('edit_user_profile_update', array($this, 'ceo_save_extra_user_profile_fields'));

        //Sync the user data with donor perfect one per day when logging.
        add_action('wp_login', array($this, 'sync_user_data'), 10, 2);

        //Add the DIVI capability fro the super admin level 2
        add_filter( 'user_has_cap', array($this, 'allow_divi_edit_only'), 10, 4 );

        //Add the member directpry shotcode
        add_shortcode('member_directory', array($this, 'member_directory'));

        add_shortcode('ceo_gift_table', array($this, 'gift_table'));


    }

    function sync_user_data($user_login, $user) {
        $user_id = $user->ID;
        $last_run = get_user_meta($user_id, 'ceo_last_api_update', true);

        // If not run today or never run
        if (!$last_run || strtotime($last_run) < strtotime('today')) {

            ceo_update_user_roles($user_id);

            update_user_meta($user_id, 'ceo_last_api_update', date('Y-m-d'));
        }
    }

    function ceo_save_extra_user_profile_fields($user_id) {
        if (!current_user_can('edit_user', $user_id)) return;

        update_user_meta($user_id, 'user_registration_business_name', sanitize_text_field($_POST['user_registration_business_name'] ?? ''));
        update_user_meta($user_id, 'user_registration_city', sanitize_text_field($_POST['user_registration_city'] ?? ''));
        update_user_meta($user_id, 'user_registration_state', sanitize_text_field($_POST['user_registration_state'] ?? ''));
        update_user_meta($user_id, 'user_registration_donor_id', sanitize_text_field($_POST['user_registration_donor_id'] ?? ''));
    }

    function ceo_show_extra_user_profile_fields($user) {

       $busines_name = $user !='add-new-user' ? get_user_meta($user->ID, 'user_registration_business_name', true) : '';
       $city = $user !='add-new-user' ? get_user_meta($user->ID, 'user_registration_city', true) : '';
       $state = $user !='add-new-user' ? get_user_meta($user->ID, 'user_registration_state', true) : '';
       $id = $user !='add-new-user' ? get_user_meta($user->ID, 'user_registration_donor_id', true) : '';

        ?>
        <h2>User Extra Info</h2>
        <table class="form-table">
            <tr>
                <th><label for="user_registration_business_name">Business Name</label></th>
                <td>
                    <input type="text" name="user_registration_business_name" id="user_registration_business_name" value="<?php echo esc_attr($busines_name); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th><label for="user_registration_city">City</label></th>
                <td>
                    <input type="text" name="user_registration_city" id="user_registration_city" value="<?php echo esc_attr($city); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th><label for="user_registration_state">State</label></th>
                <td>
                    <input type="text" name="user_registration_state" id="user_registration_state" value="<?php echo esc_attr($state); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th><label for="user_registration_donor_id">Donor ID</label></th>
                <td>
                    <input type="text" name="user_registration_donor_id" id="user_registration_donor_id" value="<?php echo esc_attr($id); ?>" class="regular-text" />
                </td>
            </tr>
        </table>
        <?php
    }

    function gift_table($atts) {
        $atts = shortcode_atts([
            'donor_id' => ''
        ], $atts);

        $user_id = '';
        $donor_id = '';

        if( is_user_logged_in() ){
            $user_id = get_current_user_id();
            $donor_id = get_user_meta($user_id, 'user_registration_donor_id', true);
        }

        if(empty($donor_id)){
            return '';
        }

        $gifts = ceo_get_gifts($donor_id);

        if (empty($gifts)) {
            return '<p>No gifts found for this donor.</p>';
        }

        ob_start();
        echo '<table class="dp_user_gifts" border="1" cellpadding="5" cellspacing="0">';
        echo '<thead><tr><th>Date</th><th>Amount</th><th>General Ledger</th></tr></thead>';
        echo '<tbody>';

        foreach ($gifts as $gift) {
            $fields = $gift['field'];
            $date = $amount = $gl = '';

            foreach ($fields as $field) {
                $attr = $field['@attributes'];
                if ($attr['name'] === 'gift_date2') {
                    $date = $attr['value'];
                } elseif ($attr['name'] === 'amount') {

                    $amount = $attr['value'];
                } elseif ($attr['name'] === 'gl') {
                    $gl = $attr['value'];
                }
            }

            if(empty($amount)){ continue; }

            echo '<tr>';
            echo '<td>' . esc_html($date) . '</td>';
            echo '<td>' . esc_html(ceo_format_currency_no_decimals($amount)) . '</td>';
            echo '<td>' . esc_html($gl) . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
        return ob_get_clean();
    }

    function member_directory() {
        ob_start();

        $args = [
            'post_type'      => 'upt_user', // Replace with your actual post type
            'posts_per_page' => 28,
            'post_status'    => 'publish',
        ];

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            echo '<div class="fwpl-layout el-92cq2d">';

            $areas_list = ['atlanta','austin','bentonville','boston','chicago','denver','dfw','houston','new_york_city','northeast','orlando','phoenix','san_jose','st_louis','washington_dc'];

            while ($query->have_posts()) {
                $query->the_post();

                $u = function_exists('UPT') ? UPT()->get_user_id() : null;
                if (!$u) continue;

                $user_info = get_userdata($u);
                if (!$user_info) continue;

                $areas = '';
                foreach ($user_info->roles as $role) {
                    if (in_array($role, $areas_list)) {
                        $role_name = wp_roles()->get_names()[$role] ?? ucfirst($role);
                        $areas .= ($areas === '') ? $role_name : ', ' . $role_name;
                    }
                }

                $image_id = get_user_meta($u, 'wp_metronet_image_id', true);
                $img_url = wp_get_attachment_image_url($image_id, 'medium');

                echo '<div class="fwpl-result">';
                if ($img_url) {
                    echo '<img src="' . esc_url($img_url) . '" width="250" height="250" />';
                }

                echo '<h3 class="member-directory">' . esc_html(get_the_title()) . '</h3>';
                echo '<h4 class="member-directory">' . esc_html(get_user_meta($u, 'user_registration_business_name', true)) . '</h4>';
                echo '<p class="member-directory"><a href="mailto:' . esc_attr($user_info->user_email) . '">' . esc_html($user_info->user_email) . '</a></p>';
                // Uncomment below if you want to show area(s)
                // echo '<p class="member-directory area">' . esc_html($areas) . '</p>';
                echo '</div>';
            }

            echo '</div>';
        } else {
            echo '<p>' . __('Sorry, no members found.', 'textdomain') . '</p>';
        }

        wp_reset_postdata();
        return ob_get_clean();
    }

    function allow_divi_edit_only( $allcaps, $cap, $args, $user ) {
        if ( in_array( 'admin_level_2', (array) $user->roles ) ) {
            $is_divi_page = false;

            // Detect Divi front-end builder, preview, or backend pages
            if (
                ( isset( $_GET['et_fb'] ) && $_GET['et_fb'] ) ||
                ( isset( $_GET['et_pb_preview'] ) && $_GET['et_pb_preview'] ) ||
                ( isset( $_GET['page'] ) && strpos( $_GET['page'], 'et_divi_' ) !== false ) ||
                ( isset( $_GET['action'] ) && $_GET['action'] === 'et_fb' )
            ) {
                $is_divi_page = true;
            }

            if ( $is_divi_page ) {
                $allcaps['edit_posts'] = true;
            }
        }

        return $allcaps;
    }

    function restrict_user_switching_for_specific_roles( $actions, $user ) {
        exit;
        if (  current_user_can( 'admin_level_2' ) ) {
            unset( $actions['switch_to_user'] );
        }

        return $actions;
    }

    function custom_hide_roles_from_user_filter() {
        if (  current_user_can( 'admin_level_2' ) ) {
            ?>
            <style>
                /* Hide the specific roles from the user role filter list */
                .subsubsub li.administrator, .subsubsub li.admin_level_2, .subsubsub li.superuser, .user-switching-wrap,.row-actions .switch_to_user,
                .user-admin-color-wrap, .user-comment-shortcuts-wrap, .show-admin-bar.user-admin-bar-front-wrap, #application-passwords-section{
                    display: none;
                }
            </style>
            <?php
        }

    }


    function restrict_admin_level_2_editable_roles( $roles ) {
        // Only affect users with the 'admin_level_2' role


        $excluded_roles = [ 'administrator', 'superuser', 'admin_level_2' ];

        foreach ( $excluded_roles as $role ) {
            if ( isset( $roles[ $role ] ) ) {
                unset( $roles[ $role ] );
            }
        }

        return $roles;
    }

    function limit_user_listing_for_admin_level_2($query){

        global $pagenow;
        if ( $pagenow !== 'users.php' ) return;

        $current_user = wp_get_current_user();
        if ( ! in_array( 'admin_level_2', $current_user->roles ) ) return;

        $excluded_roles = [ 'administrator', 'superuser', 'admin_level_2' ];

        $meta_query = [ 'relation' => 'AND' ];

        // Exclude each role using NOT LIKE
        foreach ( $excluded_roles as $role ) {
            $meta_query[] = [
                'key'     => $GLOBALS['wpdb']->prefix . 'capabilities',
                'value'   => '"' . $role . '"',
                'compare' => 'NOT LIKE',
            ];
        }

        // Merge with existing meta_query if needed
        $existing_meta_query = $query->get( 'meta_query' );
        if ( ! empty( $existing_meta_query ) ) {
            $meta_query[] = $existing_meta_query;
        }

        $query->set( 'meta_query', $meta_query );
    }

    function redirect_registration( ) {
        if ($_SERVER['REQUEST_URI'] && strpos($_SERVER['REQUEST_URI'], 'wp-login.php?action=register') !== false) {
            wp_redirect(home_url());
            exit;
        }
    }

    function admin_menu_user_page(){
        add_menu_page('Add Member', 'Add Member', 'create_users', 'custom_add_member', array($this, 'render_custom_add_member_page'), 'dashicons-admin-users', 80
        );
    }

    // 2. Render the Form Page
    function render_custom_add_member_page() {
        if (!current_user_can('create_users')) {
            wp_die('Access denied.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('custom_add_member_action')) {
            $username = sanitize_user($_POST['username']);
            $email = sanitize_email($_POST['email']);
            $password = $_POST['password'];
            $role = sanitize_text_field($_POST['role']);

            $allowed_roles = ['subscriber', 'customer']; // Extend if needed

            if (!in_array($role, $allowed_roles)) {
                echo '<div class="notice notice-error"><p>Invalid role selected.</p></div>';
            } elseif (username_exists($username) || email_exists($email)) {
                echo '<div class="notice notice-error"><p>Username or email already exists.</p></div>';
            } else {
                $user_id = wp_insert_user([
                    'user_login' => $username,
                    'user_pass' => $password,
                    'user_email' => $email,
                    'role' => $role,
                ]);

                if (is_wp_error($user_id)) {
                    echo '<div class="notice notice-error"><p>Error: ' . $user_id->get_error_message() . '</p></div>';
                } else {
                    echo '<div class="notice notice-success"><p>User successfully created (ID: ' . $user_id . ').</p></div>';
                }
            }
        }

        ?>
        <div class="wrap">
            <h1>Add Member</h1>
            <form method="post">
                <?php wp_nonce_field('custom_add_member_action'); ?>
                <table class="form-table">
                    <tr>
                        <th><label for="username">Username</label></th>
                        <td><input type="text" name="username" required class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="email">Email</label></th>
                        <td><input type="email" name="email" required class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="password">Password</label></th>
                        <td><input type="password" name="password" required class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="role">Role</label></th>
                        <td>
                            <select name="role">
                                <option value="subscriber">Subscriber</option>
                                <option value="customer">Customer</option>
                                <!-- Add more roles if needed -->
                            </select>
                        </td>
                    </tr>
                </table>
                <p><button type="submit" class="button button-primary">Create Member</button></p>
            </form>
        </div>
        <?php
    }

}