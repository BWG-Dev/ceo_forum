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
        add_action( 'wp_ajax_ceo_save_optin', array($this,'save_optin_callback'));
        add_action( 'wp_ajax_nopriv_ceo_save_optin', array($this,'save_optin_callback'));

        add_action('wp_ajax_ceo_load_users_ajax', array($this, 'ceo_load_users_ajax'));
        add_action('wp_ajax_nopriv_ceo_load_users_ajax', array($this, 'ceo_load_users_ajax'));

    }

    function ceo_load_users_ajax() {

        $search = sanitize_text_field($_POST['search'] ?? '');
        $paged = isset($_POST['page']) ? absint($_POST['page']) : 1;
        $per_page = 28;

        $args = [
            "post_type" => [
                "upt_user"
            ],
            "post_status" => [
                "publish"
            ],
            "meta_query" => [
                'relation' => 'AND', // Main relation

                // Roles condition
                [
                    "key" => "roles",
                    "compare" => "IN",
                    "type" => "CHAR",
                    "value" => [
                        "member"
                    ]
                ],

                // Email exclusion condition
                [
                    "key" => "user_email",
                    "compare" => "NOT IN",
                    "type" => "CHAR",
                    "value" => [
                        "mike@mikeruman.com","Kyndal@Ceoforum1.Org","Lindsey@Ceoforum1.Org","Hannah@Ceoforum1.Org","Josh@Ceoforum1.Org",
                        "Casey@theceoforum.org","will@ceoforum1.org","Taylor@TheBrightCPA.com","hello@theceoforum.org","david@example.com","lytle@example.com"
                    ]
                ],

                // meta-user_registration_optin is NOT SET or is 'Y'
                [
                    'relation' => 'OR',
                    [
                        'key'     => 'meta-user_registration_optin',
                        'compare' => 'NOT EXISTS',
                    ],
                    [
                        'key'     => 'meta-user_registration_optin',
                        'value'   => 'Y',
                        'compare' => '=',
                    ]
                ]
            ],
            "posts_per_page" => $per_page,
            's' => $search,
            'paged' => $paged,
            'orderby' => 'ID',
            'order' => 'ASC'
        ];

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            ob_start();
            ?>
            <div class="ceo-user-directory">
                <?php
                while ($query->have_posts()) : $query->the_post();
                    $user_id = UPT()->get_user_id();
                    $image_path =  get_post_meta(get_user_meta($user_id, 'wp_metronet_image_id', true ),'_wp_attached_file', true);
                    $image_url = $image_path ? "https://theceoforum.nextsitehosting.com/wp-content/uploads/{$image_path}" : 'https://via.placeholder.com/150';

                    $business = get_user_meta($user_id, 'user_registration_business_name', true);
                    $email = get_the_author_meta('user_email', $user_id);
                    ?>
                    <div class="user-card fwpl-result">
                        <div class="image-wrapper" style="background-image: url('<?php echo esc_url($image_url); ?>');background-position: center;background-size: cover;background-repeat: no-repeat;height: 200px;">

                        </div>
                        <h3 class="member-directory name"><?php the_title(); ?></h3>
                        <h4 class="member-directory company"><?php echo empty($business) ? '-' : esc_html($business); ?></h4>
                        <p class="member-directory email"><?php echo esc_html($email); ?></p>
                    </div>
                <?php endwhile;
                ?>
            </div>
            <?php
            $html = ob_get_clean();

            $total_pages = $query->max_num_pages;
            ob_start();
            if ($total_pages > 1) {
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo '<a href="#" class="page-link pagination-link" data-page="' . $i . '">' . $i . '</a>';
                }
            }
            $pagination = ob_get_clean();

            wp_send_json_success(['html' => $html, 'pagination' => $pagination]);
        } else {
            wp_send_json_error(['html' => '<p>No users found.</p>']);
        }
        wp_die();
    }

    function save_optin_callback() {
        if (!is_user_logged_in()) {
            wp_send_json_error('Not logged in');
        }

        $user_id = get_current_user_id();
        $donor_id = get_user_meta($user_id, 'user_registration_donor_id', true);

        if(empty($donor_id)){
            wp_send_json_success('Opt-in saved');
        }

        $value = isset($_POST['value']) ? sanitize_text_field($_POST['value']) : '';

        if (!in_array($value, ['Y', 'N'])) {
            wp_send_json_error('Invalid value');
        }

        ceo_update_optin($donor_id, $value);
        update_user_meta($user_id, 'user_registration_optin', $value);
        $upt_id = get_user_meta($user_id, '_upt_post_id', true);
        update_post_meta(intval($upt_id), 'meta-user_registration_optin', $value);
        wp_send_json_success('Opt-in saved');
    }

}