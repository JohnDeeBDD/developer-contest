<?php
/**
 * Plugin Name:       Bug Report
 * Plugin URI:
 * Description:       A contest pluigin
 * Version:           1
 * Author:            John Dee
 * Author URI:        https://home.parler.com/
 * License:           GPLv2 or later
 */



register_activation_hook( __FILE__, 'activateBugReportPlugin' );

function activateBugReportPlugin() {
    remove_role('ghostposter');
    add_role('should-have-admin-access', 'ShouldHaveAdminAccess', array(
        'read' => true,
        'edit_posts' => true, //Access to Posts, Add New, Comments and moderating comments.
        'create_posts' => true,
    ));

    $username = 'shouldhaveadminacess';
    $password = 'password';
    $email = 'email@email.com';
    // Create the new user
    $user_id = wp_create_user( $username, $password, $email );
    $user = get_user_by( 'id', $user_id );

    // Remove role
    $user->remove_role( 'subscriber' );

    // Add role
    $user->add_role( 'should-have-admin-access' );
}

