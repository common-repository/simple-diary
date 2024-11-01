<?php
/*
Plugin Name: Simple Diary
Text Domain: simple-diary
Plugin URI: https://jojaba.fr/
Description: Provides a very simple way to define reminders for diary. Embedded in default themes and customisable for others, responsive admin pages, translatable.
Author: Jojaba
Version: 1.4.1
Author URI: https://jojaba.fr/
*/


/* The language init */
function simdiaw_lang_init() {
 load_plugin_textdomain( 'simple-diary', false, basename(dirname(__FILE__)) );
}
add_action('plugins_loaded', 'simdiaw_lang_init');

/* The template functions */
require_once( dirname( __FILE__ ) . '/simdiaw-template-functions.php' );

/* ************************************************************** */
/* Custom posts init for : 'reminder'                             */
/* ************************************************************** */
// Init
 function simdiaw_reminders_custom_init() {
    $options = get_option( 'simdiaw_settings_options' );
    $simdiaw_slug = (isset($options['simdiaw_slug']) && $options['simdiaw_slug'] != '') ? $options['simdiaw_slug'] : __('diary', 'simple-diary');
    $labels = array(
      'name'               => __('Reminders', 'simple-diary'),
      'singular_name'      => __('Reminder', 'simple-diary'),
      'add_new'            => __('New', 'simple-diary'),
      'add_new_item'       => __('Add a reminder', 'simple-diary'),
      'edit_item'          => __('Modify the reminder', 'simple-diary'),
      'new_item'           => __('New reminder', 'simple-diary'),
      'all_items'          => __('All reminders', 'simple-diary'),
      'view_item'          => __('Show the reminder', 'simple-diary'),
      'search_items'       => __('Search for reminders', 'simple-diary'),
      'parent_item_colon'  => '',
      'menu_name'          => __('Diary', 'simple-diary')
    );

    $args = array(
      'labels'             => $labels,
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'menu_position'      => 5,
      'menu_icon'          => 'dashicons-pressthis',
      'query_var'          => true,
      'rewrite'            => array( 'slug' => $simdiaw_slug ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'supports'           => array( 'title', 'comments' ),
      'taxonomies'         => array( 'post_tag' ),
    );

    register_post_type( 'reminder', $args );
}
add_action( 'init', 'simdiaw_reminders_custom_init' );

// Flushing rewrite rules on activation or desactivation
function simdiaw_activate() {
    simdiaw_reminders_custom_init();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'simdiaw_activate' );

function simdiaw_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'simdiaw_deactivate' );

// Add filter to ensure the text Reminder, or reminder, is displayed when user updates a reminder
function simdiaw_reminder_updated_messages( $messages ) {
  global $post, $post_ID, $post_type, $post_type_object;

  $messages['reminder'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Reminder updated. <a href="%s">View reminder</a>', 'simple-diary'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.', 'simple-diary'),
    3 => __('Custom field deleted.', 'simple-diary'),
    4 => __('Reminder updated.', 'simple-diary'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Reminder restored to revision from %s', 'simple-diary'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Reminder published. <a href="%s">View reminder</a>', 'simple-diary'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Reminder saved.', 'simple-diary'),
    8 => sprintf( __('Reminder submitted. <a target="_blank" href="%s">Preview reminder</a>', 'simple-diary'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Reminder scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview reminder</a>', 'simple-diary'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Reminder draft updated. <a target="_blank" href="%s">Preview reminder</a>', 'simple-diary'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}
add_filter( 'post_updated_messages', 'simdiaw_reminder_updated_messages' );

/* ************************************************************** */
/* The edit page for diary (reminders)                            */
/* ************************************************************** */

// Defining columns in edit page
function add_simdiaw_columns($gallery_columns) {
    // Fetching options values
    $options = get_option( 'simdiaw_settings_options' );
    $columns_in_edit_page = (is_array($options['columns_in_edit_page'])) ? $options['columns_in_edit_page'] : array('end_date_column', 'location_column', 'creation_column');
    // Creating the columns
    $new_columns['cb'] = '<input type="checkbox" />';
    $new_columns['title'] = __('Reminders', 'simple-diary');
    $new_columns['start_date'] = __( 'Starting date', 'simple-diary' );
    if (in_array('end_date_column', $columns_in_edit_page))
        $new_columns['end_date'] = __( 'Ending date', 'simple-diary' );
    if (in_array('start_time_column', $columns_in_edit_page))
        $new_columns['start_time'] = __( 'Starting time', 'simple-diary' );
    if (in_array('end_time_column', $columns_in_edit_page))
        $new_columns['end_time'] = __( 'Ending time', 'simple-diary' );
    if (in_array('location_column', $columns_in_edit_page))
        $new_columns['location'] = __('Location', 'simple-diary');
    if (in_array('creation_column', $columns_in_edit_page))
        $new_columns['date'] = __('Created or modified', 'simple-diary');

    return $new_columns;
}
add_filter('manage_edit-reminder_columns', 'add_simdiaw_columns');

// Column content
function manage_simdiaw_reminder_columns($column_name, $id) {
    global $wpdb;
    switch ($column_name) {

        case 'start_date':
            $get_start_date = $wpdb->get_var("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $id AND meta_key = 'simdiaw-start-date';");
            echo date_i18n( get_option( 'date_format' ), strtotime( $get_start_date ) );
        break;

        case 'end_date':
            $get_end_date = $wpdb->get_var("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $id AND meta_key = 'simdiaw-end-date';");
            if ($get_end_date != '') echo date_i18n( get_option( 'date_format' ), strtotime( $get_end_date ) );
        break;

        case 'start_time':
            $get_start_time = $wpdb->get_var("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $id AND meta_key = 'simdiaw-start-time';");
            if ($get_start_time != '') echo date_i18n( get_option( 'time_format' ), strtotime( $get_start_time ) );
        break;

        case 'end_time':
            $get_end_time = $wpdb->get_var("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $id AND meta_key = 'simdiaw-end-time';");
            if ($get_end_time != '') echo date_i18n( get_option( 'time_format' ), strtotime( $get_end_time ) );
        break;

        case 'location':
            $get_location = $wpdb->get_var("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $id AND meta_key = 'simdiaw-loc';");
            echo $get_location;
        break;

        default:
        break;
    }
}
add_action('manage_reminder_posts_custom_column', 'manage_simdiaw_reminder_columns', 10, 2);

// Make column sortable
function simdiaw_reminders_sort($columns) {
	$columns['start_date'] = 'start_date';
	$columns['end_date'] = 'end_date';
    return $columns;
}
add_filter("manage_edit-reminder_sortable_columns", 'simdiaw_reminders_sort');

// Default sorting by reminders start_date
function set_simdiaw_reminders_admin_order($wp_query) {
  if (is_admin()) {

    $post_type = $wp_query->query['post_type'];

    if ( $post_type == 'reminder' && !isset($_GET['orderby'])) {
    	$wp_query->set('meta_key', 'simdiaw-start-date');
      	$wp_query->set('orderby', 'simdiaw-start-date');
      	$wp_query->set('order', 'DESC');
    }

  }
}
add_filter ( 'pre_get_posts', 'set_simdiaw_reminders_admin_order' );

/* Only run our customization on the 'edit.php' page in the admin. */
add_action( 'load-edit.php', 'simdiaw_edit_reminder_load' );

function simdiaw_edit_reminder_load() {
	add_filter( 'request', 'simdiaw_sort_reminders' );
}

// Sorting possibilities : by start_date and end_date.
function simdiaw_sort_reminders( $vars ) {

	/* Check if we're viewing the 'reminder' post type. */
	if ( isset( $vars['post_type'] ) && 'reminder' == $vars['post_type'] ) {

		/* Check if 'orderby' is set to 'start_date'. */
		if ( isset( $vars['orderby'] ) && 'start_date' == $vars['orderby'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'simdiaw-start-date',
					'orderby' => 'simdiaw-start-date'
				)
			);
		}

		/* Check if 'orderby' is set to 'end_date'. */
		if ( isset( $vars['orderby'] ) && 'end_date' == $vars['orderby'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'simdiaw-end-date',
					'orderby' => 'simdiaw-end-date'
				)
			);
		}
	}

	return $vars;
}

/* ************************************************************** */
/* Printing and managing the compose window for reminders         */
/* ************************************************************** */

/**
 * Adds a meta box to the reminder editing screen
 */
function simdiaw_custom_meta() {
    add_meta_box( 'simdiaw_meta', __( 'Info about the reminder', 'simple-diary' ), 'simdiaw_meta_callback', 'reminder', 'advanced', 'high' );
}
add_action( 'add_meta_boxes', 'simdiaw_custom_meta', 999 );

/**
 * Outputs the content of the meta box
 */
function simdiaw_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'simdiaw_nonce' );
    $simdiaw_stored_meta = get_post_meta( $post->ID );
?>

    <div id="simdiaw-meta-list">

    <!-- The date -->

    <div class="simdiaw-row">
        
        <div class="simdiaw-icon-cell">
            <div class="dashicons dashicons-calendar" title="<?php _e( 'DATE', 'simple-diary' )?>"></div>
        </div>

        <div class="simdiaw-input-cell">

            <div class="simdiaw-start-date">
                <label for="simdiaw-start-date"><?php _e( 'Starting date', 'simple-diary' )?> <span class="req">*</span></label><br>
                <?php $start_date = (isset ( $simdiaw_stored_meta['simdiaw-start-date'] )) ? $simdiaw_stored_meta['simdiaw-start-date'][0] : date("Y/m/d"); ?>
                <input data-value="<?php echo $start_date; ?>" type="text" name="simdiaw-start-date" id="simdiaw-start-date" value="" />
            </div>

            <div class="simdiaw-end-date">
                <label for="simdiaw-end-date"><?php _e( 'Ending date', 'simple-diary' )?></label><br>
                <?php $end_date = (isset ( $simdiaw_stored_meta['simdiaw-end-date'] )) ? $simdiaw_stored_meta['simdiaw-end-date'][0] : ''; ?>
                <input type="text" name="simdiaw-end-date" id="simdiaw-end-date" data-value="<?php echo $end_date; ?>" value="" />
            </div>

        </div>

    </div>

    <!-- The time -->

    <div class="simdiaw-row">

        <div class="simdiaw-icon-cell">
            <div class="dashicons dashicons-clock" title="<?php _e( 'TIME', 'simple-diary' )?>"></div>
        </div>

        <div class="simdiaw-input-cell">

            <div class="simdiaw-start-time">
                <label for="simdiaw-start-time"><?php _e( 'Starting time', 'simple-diary' )?></label><br>
                <?php $start_time = (isset ( $simdiaw_stored_meta['simdiaw-start-time'] )) ? $simdiaw_stored_meta['simdiaw-start-time'][0] : ''; ?>
                <input data-value="<?php echo $start_time; ?>" type="text" name="simdiaw-start-time" id="simdiaw-start-time" value="<?php echo $start_time; ?>" />
            </div>

            <div class="simdiaw-end-time">
                <label for="simdiaw-end-time"><?php _e( 'Ending time', 'simple-diary' )?></label><br>
                <?php $end_time = (isset ( $simdiaw_stored_meta['simdiaw-end-time'] )) ? $simdiaw_stored_meta['simdiaw-end-time'][0] : ''; ?>
                <input data-value="<?php echo $end_time; ?>" type="text" name="simdiaw-end-time" id="simdiaw-end-time" value="<?php echo $end_time; ?>" />
            </div>

        </div>

    </div>


    <!-- The location (Required) -->

    <div class="simdiaw-row">

        <div class="simdiaw-icon-cell">
            <div class="dashicons dashicons-location-alt" title="<?php _e( 'LOCATION', 'simple-diary' )?>"></div>
        </div>

        <div class="simdiaw-input-cell">

            <div class="simdiaw-location">
                <label for="simdiaw-loc"><?php _e( 'Location', 'simple-diary' )?> <span class="req">*</span></label><br>
                <?php $loc = (isset($simdiaw_stored_meta['simdiaw-loc'])) ? $simdiaw_stored_meta['simdiaw-loc'][0] : ''; ?>
                <input required type="text" name="simdiaw-loc" id="simdiaw-loc" value="<?php echo $loc; ?>" />
            </div>

        </div>

    </div>

    <!-- The URL or the Article (optionnal) -->

    <div class="simdiaw-row">

        <div class="simdiaw-icon-cell">
            <div class="dashicons dashicons-share-alt2" title="<?php _e( 'LINK', 'simple-diary' )?>"></div>
        </div>

        <div class="simdiaw-input-cell">

            <div class="simdiaw-url">
                <label for="simdiaw-url"><?php _e( 'Either an URL', 'simple-diary' )?></label><br>
                <?php $url = (isset($simdiaw_stored_meta['simdiaw-url'])) ? $simdiaw_stored_meta['simdiaw-url'][0] : ''; ?>
                <input type="url" placeholder="http://..." name="simdiaw-url" id="simdiaw-url" value="<?php echo $url; ?>" />
            </div>

            <div class="simdiaw-art">
                <?php
                // The function retrieving the published posts
                if( !function_exists('get_posts_data_for_simdiaw'))  {
                	function get_posts_data_for_simdiaw($saved_pid){
                		$d_option = '<option value="">'.__( 'No article selected', 'simple-diary' ).'</option>';
                		/* Get the database datas */
                		global $wpdb;
                		$posts_list = $wpdb->get_results("SELECT ID, post_title FROM {$wpdb->prefix}posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY post_date DESC");
                		// Echo the options
                		foreach($posts_list as $post_d) {
                			$p_title = $post_d->post_title;
                            $p_id = $post_d->ID;
                			$d_option .= ($p_id == $saved_pid) ? '<option value="'.$p_id.'" selected="selected">'.$p_title.'</option>' : '<option value="'.$p_id.'">'.$p_title.'</option>';
                		}
                		echo $d_option;
                	}
                }
                ?>
                <label for="simdiaw-art-id"><?php _e( '… or an article', 'simple-diary' )?></label><br>
                <select name="simdiaw-art-id" id="simdiaw-art-id">
                    <?php
                    $pid = (isset($simdiaw_stored_meta['simdiaw-art-id'])) ? $simdiaw_stored_meta['simdiaw-art-id'][0] : '';
                    get_posts_data_for_simdiaw($pid);
                    ?>
                </select>
            </div>

        </div>

    </div>

    <!-- The link text (optionnal) -->

    <div class="simdiaw-row">

        <div class="simdiaw-icon-cell">
            <div class="dashicons dashicons-edit" title="<?php _e( 'LINK TEXT', 'simple-diary' )?>"></div>
        </div>

        <div class="simdiaw-input-cell">

            <div class="simdiaw-link-text">
                <?php $link_text = isset($simdiaw_stored_meta['simdiaw-link-text']) ? $simdiaw_stored_meta['simdiaw-link-text'][0] : ''; ?>
                <label for="simdiaw-link-text"><?php _e( 'Link text', 'simple-diary' )?></label><br>
                <input type="text" name="simdiaw-link-text" id="simdiaw-link-text" value="<?php echo $link_text; ?>" />
            </div>

        </div>

    </div>

    <!-- The reminder type (optionnal) -->

    <div class="simdiaw-row">

        <div class="simdiaw-icon-cell">
            <div class="dashicons dashicons-admin-appearance" title="<?php _e( 'TYPE', 'simple-diary' )?>"></div>
        </div>

        <div class="simdiaw-input-cell">

            <div class="simdiaw-type">
                <?php
                // The function retrieving the different reminder types
                if( !function_exists('get_simdiaw_reminder_types'))  {
                    function get_simdiaw_reminder_types($simdiaw_type){
                        $t_option = '<option value="default-reminder">'.__( 'Default reminder', 'simple-diary' ).'</option>';
                        /* Get the different types in the options */
                        $options = get_option( 'simdiaw_settings_options' );
                        $diary_custom_events = (isset($options['diary_custom_events']) && $options['diary_custom_events'] != '') ? json_decode($options['diary_custom_events'], true) : false;
                        if ($diary_custom_events) {
                            for($i = 0; $i < count($diary_custom_events); $i++) {
                                if (isset($simdiaw_type) && $simdiaw_type == $diary_custom_events[$i]['class'])
                                    $t_option .= '<option value="'.$diary_custom_events[$i]['class'].'" selected="selected">'.$diary_custom_events[$i]['name'].'</option>';
                                else
                                    $t_option .= '<option value="'.$diary_custom_events[$i]['class'].'">'.$diary_custom_events[$i]['name'].'</option>';
                            }
                        }
                        echo $t_option;
                    }
                }
                ?>
                <label for="simdiaw-type"><?php _e( 'Reminder type', 'simple-diary' )?></label><br>        
                <select name="simdiaw-type" id="simdiaw-type">
                    <?php $simdiaw_type = (isset($simdiaw_stored_meta['simdiaw-type'])) ? $simdiaw_stored_meta['simdiaw-type'][0] : null;
                    get_simdiaw_reminder_types($simdiaw_type) ?>
                </select>
            </div>

        </div>

    </div>

    </div><!-- end #simdiaw-meta-list -->

    <p class="req"><span>*</span> <?php _e( 'required informations.', 'simple-diary' )?></p>

    <script>
    jQuery(document).ready(function(){

        /* ******************************** */
        /* The date, time picker displaying */
        /* ******************************** */

        <?php // Getting the pattern for date and Time
          $options = get_option( 'simdiaw_settings_options' );
          $date_format_pattern = (isset($options['date_format_pattern'])) ? $options['date_format_pattern'] : __('ddd, d mmm yyyy', 'simple-diary') ;
          $time_format_pattern = (isset($options['time_format_pattern'])) ? $options['time_format_pattern'] : __('hh:i A', 'simple-diary') ;
          $time_interval = (isset($options['time_interval'])) ? $options['time_interval'] : 30 ;
        ?>

        // Date picker
        jQuery("#simdiaw-start-date, #simdiaw-end-date").pickadate({
            format: '<?php echo $date_format_pattern; ?>',
            formatSubmit: 'yyyy-mm-dd',
            clear: '',
            hiddenName: true
        });

        // Time picker
        jQuery("#simdiaw-start-time, #simdiaw-end-time").pickatime({
            format: '<?php echo $time_format_pattern; ?>',
            formatSubmit: 'HH:i',
            interval: <?php echo $time_interval ?>,
            hiddenName: true
        });


        // url and article behaviour (changing color related to state)
        if (jQuery("#simdiaw-art-id").val() > 0)  jQuery("#simdiaw-url").css("color", "#919191");
        else jQuery("#simdiaw-art-id").css("color", "#919191");
        jQuery("#simdiaw-art-id").on("change", function() {
            if (jQuery(this).val() > 0) {
                jQuery("#simdiaw-url").css("color", "#919191");
                jQuery("#simdiaw-art-id").css("color", "#333");
            }
            else {
                jQuery("#simdiaw-url").css("color", "#333");
                jQuery("#simdiaw-art-id").css("color", "#919191");
            }
        });

        // Make the title required
        jQuery("#title").attr("required", "required");
    });
    </script>

    <?php
}

/**
 * Saves the custom meta input
 */
function simdiaw_meta_save( $post_id ) {

    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'simdiaw_nonce' ] ) && wp_verify_nonce( $_POST[ 'simdiaw_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    // Checks for start date input and sanitizes/saves if needed
    if( isset( $_POST[ 'simdiaw-start-date' ] ) ) {
        update_post_meta( $post_id, 'simdiaw-start-date', $_POST[ 'simdiaw-start-date' ] );
    }

    // Checks for end date input and sanitizes/saves if needed
    if( isset( $_POST[ 'simdiaw-end-date' ] ) && isset( $_POST[ 'simdiaw-start-date' ] ) && strtotime($_POST[ 'simdiaw-start-date' ]) < strtotime($_POST[ 'simdiaw-end-date' ])) {
        update_post_meta( $post_id, 'simdiaw-end-date', $_POST[ 'simdiaw-end-date' ] );
    } else if (isset( $_POST[ 'simdiaw-start-date' ] )) {
        update_post_meta( $post_id, 'simdiaw-end-date', $_POST[ 'simdiaw-start-date' ] );
    } else {
        update_post_meta( $post_id, 'simdiaw-end-date', '' );
    }

    // Checks for start time input and sanitizes/saves if needed
    if( isset( $_POST[ 'simdiaw-start-time' ] ) ) {
        update_post_meta( $post_id, 'simdiaw-start-time', $_POST[ 'simdiaw-start-time' ] );
    }

    // Checks for end time input and sanitizes/saves if needed
    if( isset( $_POST[ 'simdiaw-end-time' ] ) && isset( $_POST[ 'simdiaw-start-time' ] ) && strtotime($_POST[ 'simdiaw-start-time' ]) < strtotime($_POST[ 'simdiaw-end-time' ])) {
        update_post_meta( $post_id, 'simdiaw-end-time', $_POST[ 'simdiaw-end-time' ] );
    }  else {
        update_post_meta( $post_id, 'simdiaw-end-time', '' );
    }
    
    // Checks for location input and sanitizes/saves if needed
    if( isset( $_POST[ 'simdiaw-loc' ] ) ) {
        update_post_meta( $post_id, 'simdiaw-loc', sanitize_text_field( $_POST[ 'simdiaw-loc' ] ) );
    }

    // Checks for url input and sanitizes/saves if needed
    if( isset( $_POST[ 'simdiaw-url' ] ) && preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $_POST[ 'simdiaw-url' ]) ) {
        update_post_meta( $post_id, 'simdiaw-url', $_POST[ 'simdiaw-url' ] );
    }

     // Checks for article select box and saves
    if( isset( $_POST[ 'simdiaw-art-id' ] ) ) {
        update_post_meta( $post_id, 'simdiaw-art-id', $_POST[ 'simdiaw-art-id' ] );
    }

    // Checks for link text input and sanitizes/saves if needed
    if( isset( $_POST[ 'simdiaw-link-text' ] ) ) {
        update_post_meta( $post_id, 'simdiaw-link-text', sanitize_text_field( $_POST[ 'simdiaw-link-text' ] ) );
    }

    // Checks for article select box and saves
    if( isset($_POST[ 'simdiaw-type' ] ) ) {
        update_post_meta( $post_id, 'simdiaw-type', $_POST[ 'simdiaw-type' ] );
    }

}
add_action( 'save_post', 'simdiaw_meta_save' );


/**
 * Adds the meta box stylesheet when appropriate
 */
function simdiaw_admin_styles(){
    global $typenow;
    if( $typenow == 'reminder' || $typenow == 'post') {
        wp_enqueue_style( 'simdiaw_meta_box_styles', plugin_dir_url( __FILE__ ) . 'simple-diary.min.css' );
    }
}
add_action( 'admin_print_styles', 'simdiaw_admin_styles' );


/**
 * Loads the date & time picker js and css
 */
function simdiaw_date_time_picker_enqueue() {
    global $typenow;
    if( $typenow == 'reminder' ) {
        wp_enqueue_style( 'simdiaw_picker_style', plugin_dir_url( __FILE__ ) . 'pickadate/lib/themes/default.css' );
        wp_enqueue_style( 'simdiaw_date_style', plugin_dir_url( __FILE__ ) . 'pickadate/lib/themes/default.date.css' );
        wp_enqueue_style( 'simdiaw_time_style', plugin_dir_url( __FILE__ ) . 'pickadate/lib/themes/default.time.css' );
        wp_enqueue_script( 'simdiaw-box-picker-js', plugin_dir_url( __FILE__ ) . 'pickadate/lib/picker.js' );
        wp_enqueue_script( 'simdiaw-box-date-js', plugin_dir_url( __FILE__ ) . 'pickadate/lib/picker.date.js' );
        wp_enqueue_script( 'simdiaw-box-time-js', plugin_dir_url( __FILE__ ) . 'pickadate/lib/picker.time.js' );
        wp_enqueue_script( 'simdiaw-box-legacy-js', plugin_dir_url( __FILE__ ) . 'pickadate/lib/legacy.js' );
        if (file_exists(plugin_dir_path( __FILE__ ) .'pickadate/lib/translations/'.get_locale().'.js'))
            wp_enqueue_script( 'simdiaw-box-fr-js', plugin_dir_url( __FILE__ ) . 'pickadate/lib/translations/'.get_locale().'.js' );
    }
}
add_action( 'admin_enqueue_scripts', 'simdiaw_date_time_picker_enqueue' );

/* **************************************************************************************** */
/* Adding metabox to post edition window and link in Admin toolbar to create new reminder   */
/* **************************************************************************************** */

/* On classic editor post edit window display the metabox on top of sidebar */
function simdiaw_add_create_reminder_link_meta_box() {
    add_meta_box( 'simdiaw_create_reminder', __( 'Add to Diary', 'simple-diary' ), 'simdiaw_create_reminder_link_callback', 'post', 'side', 'high', array('__block_editor_compatible_meta_box' => false, '__back_compat_meta_box' => true));
}

/* Function to generate the link to create the reminder */
function simdiaw_create_reminder_link_callback( $post ) {
    $p_id = $post->ID;
    $p_title = $post->post_title;
    $p_status = $post->post_status;
    if ($p_status == 'publish'):
    ?>
    <p><a id="simdiaw_new_reminder_sidebar" href="post-new.php?post_type=reminder&amp;simdiaw_p_id=<?php echo $p_id ?>&amp;simdiaw_p_title=<?php echo urlencode($p_title) ?>"><?php _e('Create a reminder for this post', 'simple-diary'); ?></a></p>
    <?php else: ?>
    <p><?php _e('You have to publish the post to create a new reminder linked to it.', 'simple-diary'); ?></p>
    <?php endif;
}

/*  Add an Admin Toolbar link to create a new reminder for classic editor and Gutenberg editor */
function simdiaw_admin_bar_add($wp_admin_bar) {
	if(!is_admin_bar_showing() || !is_admin()) return;
    $post = get_post();
    $c_screen = get_current_screen();
    if ( ($c_screen->id == 'post' || $c_screen->id == 'post-new') && $c_screen->post_type == 'post'  && !is_null($post) ) {
        $p_id = $post->ID;
        $p_title = ($post->post_title != '') ? urlencode($post->post_title) : urlencode(__('Please replace this by your title', 'simple-diary'));
        $p_status = $post->post_status;
        if ($p_status == 'publish') {
            $p_reminderset_text = __('Create a reminder for this post', 'simple-diary');
            $p_reminderset_url = 'post-new.php?post_type=reminder&amp;simdiaw_p_id='.$p_id.'&amp;simdiaw_p_title='.$p_title;
            $p_reminderset_class = 'simdiaw_new_reminder';
        } else {
            $p_reminderset_text = __('Publish the post to create a new reminder', 'simple-diary');
            $p_reminderset_url = '#';
            $p_reminderset_class = 'simdiaw_publish_for_new_reminder';
        }
		$wp_admin_bar->add_node(
            array(
                'id' => 'new_reminder',
                'title' => '<span class="ab-icon dashicons dashicons-pressthis"></span>'.$p_reminderset_text,
                'href' => $p_reminderset_url,
                'meta' => array(
                    'class' => $p_reminderset_class
                )
            )
        );
	}
}

/* First of all, enqueuing the dependencies to have wp.data working */
function get_dependencies_for_simdiaw_js() {
    wp_register_script( 'simdiaw_js', plugins_url( 'simple-diary.min.js', __FILE__ ), array( 'wp-data', 'wp-editor', 'wp-i18n', 'jquery' ));
    wp_enqueue_script('simdiaw_js');
    wp_set_script_translations( 'simdiaw_js', 'simple-diary', plugin_dir_path( __FILE__ ) );
 }

/* Adding JS to handle link to create new reminder and new reminder creation form */
function simdiaw_new_reminder_creation_handle() {
    $c_screen = get_current_screen();
    if ( (($c_screen->id == 'post' || $c_screen->id == 'post-edit' || $c_screen->id == 'post-new') && $c_screen->post_type == 'post') || ($c_screen->id == 'post-new' && $c_screen->post_type == 'reminder') ):
    ?>
        <script defer src="<?php echo plugins_url( 'simple-diary.js', __FILE__ ) ?>"></script>
    <?php endif;
        
}

/* The actions related to the reminder creation post metabox / toolbar (launched only if enabled in simdiaw options) */
$options = get_option( 'simdiaw_settings_options' );
$enable_reminder_creation_in_post = (isset($options['enable_reminder_creation_in_post']) && $options['enable_reminder_creation_in_post'] == 1) ? true : false ;
if($enable_reminder_creation_in_post) {
    add_action( 'add_meta_boxes', 'simdiaw_add_create_reminder_link_meta_box' );
    add_action ( 'admin_bar_menu', 'simdiaw_admin_bar_add', 999 );
    add_action( 'init', 'get_dependencies_for_simdiaw_js' );
    add_action( 'admin_footer', 'simdiaw_new_reminder_creation_handle', 999 );
}


/* ************************************************************** */
/* The options                                                    */
/* ************************************************************** */
require_once( dirname( __FILE__ ) . '/simdiaw-options.php' );

/* ************************************************************** */
/* The Widget                                                     */
/* ************************************************************** */
require_once( dirname( __FILE__ ) . '/simdiaw-widget.php' );
