<?php
/*
Plugin Name: JHL Auto Login
Description: Automatically log in a user
Version: 1.0.0
Author: Jason Lawton <jason@jasonlawton.com>
*/

define( 'JHL_AL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'JHL_AL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

add_action( 'admin_menu', 'jhl_al_add_admin_menu' );
add_action( 'admin_init', 'jhl_al_settings_init' );

function jhl_al_add_admin_menu(  ) { 

	add_options_page( 'JHL Auto Login', 'JHL Auto Login', 'manage_options', 'jhl_auto_login', 'jhl_al_options_page' );

}

function jhl_al_settings_init(  ) { 

	register_setting( 'pluginPage', 'jhl_al_settings' );

	add_settings_section(
		'jhl_al_pluginPage_section', 
		__( 'Settings for JHL Auto Login', 'jhl_al' ), 
		'jhl_al_settings_section_callback', 
		'pluginPage'
	);

}

function jhl_al_settings_section_callback(  ) { 

	echo __( 'Use this page to manage settings', 'jhl_al' );

}


function jhl_al_options_page(  ) { 
    global $wpdb;

    if (!current_user_can('manage_options')) {
        wp_die('Go away.');
    }

    // if (!wp_verify_nonce( '_wp_nonce', 'jhl_al_option_page_update' )) {
    //     wp_die('Nonce verification failed');
    // }

    // "normal" fields

    if (isset($_POST['jhl_al_enable'])) {
        update_option('jhl_al_enable', $_POST['jhl_al_enable']);
        $value = $_POST['jhl_al_enable'];
    } 

    // $value = get_option( 'jhl_al_enable' );

    if (isset($_POST['jhl_al_user'])) {
        update_option('jhl_al_user', $_POST['jhl_al_user']);
        $value = $_POST['jhl_al_user'];
    } 

    // $value = get_option( 'jhl_al_user' );

    // user meta key
    if (isset($_POST['jhl_al_meta_key'])) {
        update_option('jhl_al_meta_key', $_POST['jhl_al_meta_key']);
        $value = $_POST['jhl_al_meta_key'];
    } 

    // query string parameter
    if (isset($_POST['jhl_al_query_string_paramter'])) {
        update_option('jhl_al_query_string_paramter', $_POST['jhl_al_query_string_paramter']);
        $value = $_POST['jhl_al_query_string_paramter'];
    } 

    include 'options-form.php';

}

// add_action('after_setup_theme', 'jhl_al_check');
// hooks tried
// list of hooks from https://wordpress.stackexchange.com/questions/162862/how-to-get-wordpress-hook-run-sequence
// after_setup_theme
// plugins_loaded
// init
// set_current_user
add_action( 'init' , 'jhl_al_check' );

function jhl_al_check() {
    // var_dump('jhl_al_check');
    // if there's a user logged in, we don't even need to bother with this
    if ( !is_user_logged_in() ) {
        // var_dump('not logged in');
        $enabled = get_option( 'jhl_al_enable', false );
        // var_dump($enabled);
        if ( $enabled ) {
            // var_dump('enabled');
            // get the user login name
            $user_id = get_option( 'jhl_al_user', false );
            $query_string_param = get_option( 'jhl_al_query_string_paramter', false );
            // var_dump($query_string_param);
            // var_dump($_GET[$query_string_param]);exit;
            if ( $user_id ) {
                // var_dump('user_id = ' . $user_id);
                $jhl_al_user = get_user_by( 'id', $user_id );
                
                // $user_login = $jhl_al_user->user_login;

                // $user = get_userdatabylogin($user_login);
                // $user_id = $user->ID; 
                wp_set_current_user($jhl_al_user->ID, $jhl_al_user->user_login);
                wp_set_auth_cookie($jhl_al_user->ID); 
                do_action('wp_login', $jhl_al_user->user_login); 
            } else if ( $query_string_param && ! empty( $_GET[$query_string_param] ) ) {
                // var_dump($query_string_param);
                // var_dump($_GET[$query_string_param]);exit;
                // see if we can get a user by their user meta
                $user_meta_key = get_option( 'jhl_al_meta_key', false );
                // var_dump( $user_meta_key );
                if ( $user_meta_key ) {
                    $jhl_al_users = get_users( array(
                        'meta_key'   => $user_meta_key,
                        'meta_value' => $_GET[$query_string_param],
                    ));
                    if ( is_array( $jhl_al_users ) && count( $jhl_al_users ) ) {
                        $jhl_al_user = $jhl_al_users[0];
                        // var_dump($jhl_al_user);
                        // var_dump($jhl_al_user->ID);
                        // var_dump($jhl_al_user->user_login);exit;
                        wp_set_current_user($jhl_al_user->ID, $jhl_al_user->user_login);
                        wp_set_auth_cookie($jhl_al_user->ID); 
                        do_action('wp_login', $jhl_al_user->user_login);
                    } else {
                        // var_dump('no user found');
                    }
                } else {
                    // var_dump('no user meta key');
                }
            } else {
                // var_dump('no user id');
            }
        } else {
            // var_dump('not enabled');
        }
    }
}

// add field to user page
// Hooks near the bottom of profile page (if current user)
add_action('show_user_profile', 'custom_user_profile_fields');

// Hooks near the bottom of the profile page (if not current user)
add_action('edit_user_profile', 'custom_user_profile_fields');

// @param WP_User $user
function custom_user_profile_fields($user)
{
    ?>
    <table class="form-table">
        <tr>
            <th>
                <label for="invite_hash"><?php _e('Invite Hash');?></label>
            </th>
            <td>
                <input type="text" name="invite_hash" id="invite_hash" value="<?php echo esc_attr(get_the_author_meta('invite_hash', $user->ID)); ?>" class="regular-text" />
                <button type="button" id="generate_hash">Generate Hash</button>
            </td>
        </tr>
    </table>
    <script>
    jQuery( document ).ready(function() {
        jQuery('#generate_hash').on( 'click', function() {
            jQuery('#invite_hash').val( Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15) );
        });
    });
    </script>
<?php
}

// Hook is used to save custom fields that have been added to the WordPress profile page (if current user)
add_action('personal_options_update', 'update_extra_profile_fields');

// Hook is used to save custom fields that have been added to the WordPress profile page (if not current user)
add_action('edit_user_profile_update', 'update_extra_profile_fields');

function update_extra_profile_fields($user_id)
{
    if (current_user_can('edit_user', $user_id)) {
        update_user_meta($user_id, 'invite_hash', $_POST['invite_hash']);
    }

}