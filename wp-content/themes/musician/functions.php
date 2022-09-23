<?php

function musician_assets() {
	wp_enqueue_style('bootstrap.min.css', get_template_directory_uri() . '/assets/css/bootstrap.min.css');
    wp_enqueue_style( 'style', get_stylesheet_uri() );
    wp_enqueue_style('profile.css', get_template_directory_uri() . '/assets/css/profile.css');
    wp_enqueue_style('payment.css', get_template_directory_uri() . '/assets/css/payment.css');
    wp_enqueue_style('font-awesome.min.css', get_template_directory_uri() . '/assets/font-awesome-4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('jquery-ui.min.css', get_template_directory_uri() . '/assets/jquery-ui-1.13.1.custom/jquery-ui.min.css');

    wp_enqueue_script( 'jquery-3.6.0.min.js', get_template_directory_uri() . '/assets/js/jquery-3.6.0.min.js');
    wp_enqueue_script( 'popper.min.js', get_template_directory_uri() . '/assets/js/popper.min.js');
    wp_enqueue_script( 'bootstrap.min.js', get_template_directory_uri() . '/assets/js/bootstrap.min.js');
    wp_enqueue_script( 'autocomplete.js', get_template_directory_uri() . '/assets/js/autocomplete.js');
    wp_enqueue_script( 'jquery.js', get_template_directory_uri() . '/assets/jquery-ui-1.13.1.custom/external/jquery/jquery.js');
    wp_enqueue_script( 'jquery-ui.min.js', get_template_directory_uri() . '/assets/jquery-ui-1.13.1.custom/jquery-ui.min.js');

    wp_enqueue_script( 'modal.js', get_template_directory_uri() . '/assets/js/modal.js');
    wp_localize_script( 'modal.js', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
}
add_action( 'wp_enqueue_scripts', 'musician_assets' );

remove_action( 'wp_head', 'wp_resource_hints', 2 );

// remove wordpress default header in frontend panel
function remove_admin_login_header() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'remove_admin_login_header');

// handle login ajax
function wpdocs_custom_login() {
    if (isset($_POST['useremail'])) {
        $creds = array(
            'user_login'    => $_POST['useremail'],
            'user_password' => $_POST['userpass'],
            'remember'      => false
        );

        $user = wp_signon( $creds, false );

        if ( is_wp_error( $user ) ) {
            echo $user->get_error_message();
            exit;
        } else {
            wp_clear_auth_cookie();
            do_action('wp_login', $user->user_login, $user);
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID, true);
            echo "success";
            exit;
        }
    }
}
add_action( 'wp_ajax_nopriv_user_signin', 'wpdocs_custom_login' );

// handle sign up ajax
function wpdocs_custom_signup() {
    if (isset($_POST['username']) && isset($_POST['useremail'])) {
        if (!is_email($_POST['useremail'])) {
            echo "Email format is incorrect.";
            exit;
        }

        if (email_exists($_POST['useremail'])) {
            echo "This email is already existing.";
            exit;
        }

        $user_id = wp_create_user($_POST['username'], $_POST['userpass'], $_POST['useremail']);
        // set user role
        $new_user = new WP_User($user_id);
        if ($_POST['userrole'] === "musician")
            $new_user->set_role('editor');
        else if ($_POST['userrole'] === "advertiser")
            $new_user->set_role('author');

        if ( is_wp_error( $user_id ) ) {
            echo $user_id->get_error_message();
            exit;
        } else {
            $user = get_user_by('id', $user_id);
            wp_clear_auth_cookie();
            do_action('wp_login', $user->user_login, $user);
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID, true);
            echo "success";
            exit;
        }
    }
}
add_action( 'wp_ajax_nopriv_user_signup', 'wpdocs_custom_signup' );

add_filter('template_redirect', function () {
  ob_start(null, 0, 0);
});

// add 'All Profiles' menu and corresponding screen in admin panel
function users_profiles() {
	if ( !current_user_can( 'list_users' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	echo '<h2>Profiles</h2>';
	echo '</div>';
    echo do_shortcode('[wp_table id=43/]');
}
function all_profiles_menu() {
	add_users_page( 'Profiles', 'All Profiles', 'list_users', 'users_profiles', 'users_profiles' );
}
add_action( 'admin_menu', 'all_profiles_menu' );

// handle profile submission form
function handle_profile_form() {
    global $wpdb;
    $user_id = wp_get_current_user()->ID;

    $user_profile = null;
    $profile_id = $_POST['profile_id'];
    if (!empty($profile_id)) {
        $user_profile = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}profile WHERE ID=$profile_id", OBJECT);
    }

    $profile_name = $_POST['profile_name'];
    $band_name = $_POST['band_name'];
    $band_size = $_POST['band_size'];
    $youtube_code = $_POST['youtube_code'];
    $gig_cost_min = $_POST['gig_cost_min'];
    $gig_cost_max = $_POST['gig_cost_max'];
    $budget = $_POST['budget'];
    $genre = $_POST['genre'];
    $event_type = $_POST['event_type'];
    $avatar_upload = [];
    $avatar_upload['url'] = is_null($user_profile) ? '' : $user_profile->avatar_link;
    $pdf_upload = [];
    $pdf_upload['url'] = is_null($user_profile) ? '' : $user_profile->pdf_link;

    if ($_FILES['profile_avatar']['name'] !== "") {
        // remove already uploaded file
        if (!is_null($user_profile)) {
            $path = parse_url($user_profile->avatar_link, PHP_URL_PATH); // Remove "http://localhost"
            $fullPath = ABSPATH . $path;
            unlink($fullPath);
        }

        $avatar_filename = time() . $_FILES['profile_avatar']['name'];
        $avatar_upload = wp_upload_bits($avatar_filename, null, file_get_contents($_FILES['profile_avatar']['tmp_name']));
    }

    if ($_FILES['profile_pdf']['name'] !== "") {
        // remove already uploaded file
        if (!is_null($user_profile)) {
            $path = parse_url($user_profile->pdf_link, PHP_URL_PATH); // Remove "http://localhost"
            $fullPath = ABSPATH . $path;
            unlink($fullPath);
        }

        $pdf_filename = time() . $_FILES['profile_pdf']['name'];
        $pdf_upload = wp_upload_bits($pdf_filename, null, file_get_contents($_FILES['profile_pdf']['tmp_name']));
    }

    if (!empty($profile_id)) {
        // update database
        $wpdb->update(
            'wp_profile',
            array(
                'profile_name' => $profile_name,
                'band_name' => $band_name,
                'band_size' => $band_size,
                'youtube_code' => $youtube_code,
                'gig_cost_min' => $gig_cost_min,
                'gig_cost_max' => $gig_cost_max,
                'genre' => $genre,
                'budget' => $budget,
                'event_type' => $event_type,
                'avatar_link' => $avatar_upload['url'],
                'pdf_link' => $pdf_upload['url'],
                'user_id' => $user_id
            ),
            array( 'ID' => $profile_id ),
            array( '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%d', '%s', '%s', '%s', '%d' ),
            array( '%d' )
        );
    } else {
        // insert database
        $wpdb->insert(
            'wp_profile',
            array(
                'profile_name' => $profile_name,
                'band_name' => $band_name,
                'band_size' => $band_size,
                'youtube_code' => $youtube_code,
                'gig_cost_min' => $gig_cost_min,
                'gig_cost_max' => $gig_cost_max,
                'genre' => $genre,
                'budget' => $budget,
                'event_type' => $event_type,
                'avatar_link' => $avatar_upload['url'],
                'pdf_link' => $pdf_upload['url'],
                'user_id' => $user_id
            ),
            array( '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%d', '%s', '%s', '%s', '%d' )
        );
    }

    wp_redirect( get_permalink( get_page_by_path( 'profiles' ) ), 301 );
    exit;
}
add_action('admin_post_submit_profile_form', 'handle_profile_form');

// handle delete profile ajax
function handle_delete_profile_form() {
    if (isset($_POST['delete_profile_id'])) {
        global $wpdb;
        $profile_id = $_POST['delete_profile_id'];

        $profile = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}profile WHERE ID=$profile_id", OBJECT);
        if (!is_null($profile->avatar_link) && !empty($profile->avatar_link)) {
            $path = parse_url($profile->avatar_link, PHP_URL_PATH); // Remove "http://localhost"
            $fullPath = ABSPATH . $path;
            unlink($fullPath);
        }

        if (!is_null($profile->pdf_link) && !empty($profile->pdf_link)) {
            $path = parse_url($profile->pdf_link, PHP_URL_PATH); // Remove "http://localhost"
            $fullPath = ABSPATH . $path;
            unlink($fullPath);
        }

        $wpdb->delete( 'wp_profile', array( 'ID' => $profile_id ) );

        wp_redirect( get_permalink( get_page_by_path( 'profiles' ) ), 301 );
        exit;
    }
}
add_action('admin_post_submit_delete_profile_form', 'handle_delete_profile_form');

// handle submit performance ajax
function wpajax_performance_submit() {
    if (isset($_POST['start_date']) && isset($_POST['venue'])) {
        global $wpdb;
        $wpdb->insert(
            'wp_performance',
            array(
                'start_date' => $_POST['start_date'],
                'venue' => $_POST['venue'],
                'profile_id' => $_POST['profile_id'],
            ),
            array( '%s', '%s', '%d' )
        );

        $result = [];
        $result['id'] = $wpdb->insert_id;
        $result['status'] = "success";

        echo json_encode($result);
        exit;
    }
}
add_action( 'wp_ajax_performance_submit', 'wpajax_performance_submit' );

// handle submit performance ajax
function wpajax_performance_delete() {
    if (isset($_POST['performance_id'])) {
        $id = $_POST['performance_id'];
        global $wpdb;
        $wpdb->delete( 'wp_performance', array( 'ID' => $id ) );

        $result = [];
        $result['id'] = $id;
        $result['status'] = "success";

        echo json_encode($result);
        exit;
    }
}
add_action( 'wp_ajax_performance_delete', 'wpajax_performance_delete' );

// handle submit performance ajax
function wpajax_get_performances() {
    if (isset($_POST['profile_id'])) {
        $id = $_POST['profile_id'];
        global $wpdb;
        $performances = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}performance WHERE profile_id = $id ORDER BY start_date ASC", ARRAY_A);

        $result = [];
        $result['performances'] = $performances;
        $result['status'] = "success";

        echo json_encode($result);
        exit;
    }
}
add_action( 'wp_ajax_get_performances', 'wpajax_get_performances' );