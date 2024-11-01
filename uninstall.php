<?php
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();
// Delete simdiaw options
delete_option( 'simdiaw_settings_options' );
// Delete all simdiaw meta keys
delete_post_meta_by_key( 'simdiaw-start-date' );
delete_post_meta_by_key( 'simdiaw-end-date' );
delete_post_meta_by_key( 'simdiaw-start-time' );
delete_post_meta_by_key( 'simdiaw-end-time' );
delete_post_meta_by_key( 'simdiaw-loc' );
delete_post_meta_by_key( 'simdiaw-url' );
delete_post_meta_by_key( 'simdiaw-art-id' );
delete_post_meta_by_key( 'simdiaw-link-text' );
delete_post_meta_by_key( 'simdiaw-type' );
// Delete all reminders
global $wpdb;
$wpdb->delete( $wpdb->prefix.'posts', array( 'post_type' => 'reminder' ) );
 ?>
