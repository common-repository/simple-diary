<?php

/* ************************************************************** */
/* The options page menu item                                     */
/* ************************************************************** */

/**
 * Load up the options page
 */
if( !function_exists('simdiaw_options_add_page'))  {
	function simdiaw_options_add_page() {
		add_options_page(
			__( 'Diary options', 'simple-diary' ), // Title for the page
			__( 'Simple Diary', 'simple-diary' ), //  Page name in admin menu
			'manage_options', //  Minimum role required to see the page
			'simdiaw_options_page', // unique identifier
			'simdiaw_options_do_page'  // name of function to display the page
		);
		add_action( 'admin_init', 'simdiaw_options_settings' );
	}
}
add_action( 'admin_menu', 'simdiaw_options_add_page' );

/* ************************************************************** */
/* Option page creation                                           */
/* ************************************************************** */

if( !function_exists('simdiaw_options_do_page'))  {
	function simdiaw_options_do_page() {
	?>

<div class="wrap">

        <h2><?php _e( 'Diary options', 'simple-diary' ) ?></h2>

        <?php
        /*** To debug, here we can print the plugin options **/
        /*
        echo '<pre>';
        $options = get_option( 'simdiaw_settings_options' );
        print_r($options);
        echo '</pre>';
        */
        if (isset($_GET['settings-updated'])) {
	        // flushing rewrite rules after saving
	        simdiaw_reminders_custom_init();
            flush_rewrite_rules();
       }
         ?>

        <form method="post" action="options.php">
            <?php settings_fields( 'simdiaw_settings_options' ); ?>
		  	<?php do_settings_sections('simdiaw_setting_section'); ?>
		  	<p><input class="button-primary"  name="Submit" type="submit" value="<?php esc_attr_e(__('Save Changes', 'simple-diary')); ?>" /></p>
        </form>
        <script>
        jQuery(document).ready(function() {

        });
        </script>
</div>

<?php
	} // end simdiaw_options_do_page
}


/* ************************************************************** */
/* The options creation and managing                              */
/* ************************************************************** */

/**
 * Init plugin options to white list our options
 */
if( !function_exists('simdiaw_options_settings'))  {
	function simdiaw_options_settings(){
		/* Register simdiaw settings. */
		register_setting(
			'simdiaw_settings_options',  //$option_group , A settings group name. Must exist prior to the register_setting call. This must match what's called in settings_fields()
			'simdiaw_settings_options', // $option_name The name of an option to sanitize and save.
			'simdiaw_options_validate' // $sanitize_callback  A callback function that sanitizes the option's value.
        );

        /** Add help section **/
		add_settings_section(
			'simdiaw_help_options', //  section name unique ID
			__( 'Help', 'simple-diary' ), // Title or name of the section (to be output on the page), you can leave nbsp here if not wished to display
			'simdiaw_help_text',  // callback to display the content of the section itself
			'simdiaw_setting_section' // The page name. This needs to match the text we gave to the do_settings_sections function call
        );

		/** Add frontend settings section **/
		/*  ****************************  */
		add_settings_section(
			'simdiaw_frontend_options', //  section name unique ID
			__( 'Frontend settings', 'simple-diary' ), // Title or name of the section (to be output on the page), you can leave nbsp here if not wished to display
			'simdiaw_frontend_options_text',  // callback to display the content of the section itself
			'simdiaw_setting_section' // The page name. This needs to match the text we gave to the do_settings_sections function call
        );

		/** Register each simdiaw option **/
		add_settings_field(
			'simdiaw_title',
			__( 'Diary page title', 'simple-diary' ),
			'simdiaw_func_title',
			'simdiaw_setting_section',
			'simdiaw_frontend_options'
        );

		add_settings_field(
			'simdiaw_slug',
			__( 'Slug', 'simple-diary' ),
			'simdiaw_func_slug',
			'simdiaw_setting_section',
			'simdiaw_frontend_options'
        );

         add_settings_field(
			'upcoming_reminders_count',
			__( 'Upcoming reminders count', 'simple-diary' ),
			'simdiaw_func_u_r_count',
			'simdiaw_setting_section',
			'simdiaw_frontend_options'
		);
		
		add_settings_field(
			'past_reminders_count',
			__( 'Past reminders count', 'simple-diary' ),
			'simdiaw_func_p_r_count',
			'simdiaw_setting_section',
			'simdiaw_frontend_options'
        );

        add_settings_field(
			'diary_reminders_count',
			__( 'Reminders count', 'simple-diary' ),
			'simdiaw_func_d_r_count',
			'simdiaw_setting_section',
			'simdiaw_frontend_options'
		);
		
		add_settings_field(
			'diary_custom_events',
			__( 'Custom reminders', 'simple-diary' ),
			'simdiaw_func_custom_events',
			'simdiaw_setting_section',
			'simdiaw_frontend_options'
		);
		
		add_settings_field(
			'diary_upcoming_reminders_format',
			__( 'Upcoming reminders format', 'simple-diary' ),
			'simdiaw_func_u_r_format',
			'simdiaw_setting_section',
			'simdiaw_frontend_options'
		);

		add_settings_field(
			'diary_past_reminders_format',
			__( 'Past reminders format', 'simple-diary' ),
			'simdiaw_func_p_r_format',
			'simdiaw_setting_section',
			'simdiaw_frontend_options'
		);

		/** Add backend settings section **/
		/*  *****************************  */
		add_settings_section(
			'simdiaw_backend_options', //  section name unique ID
			__( 'Backend settings', 'simple-diary' ), // Title or name of the section (to be output on the page), you can leave nbsp here if not wished to display
			'simdiaw_backend_options_text',  // callback to display the content of the section itself
			'simdiaw_setting_section' // The page name. This needs to match the text we gave to the do_settings_sections function call
		);
		
		add_settings_field(
			'enable_reminder_creation_in_post',
			__( 'Enable reminder creation in post', 'simple-diary' ),
			'simdiaw_func_enable_reminder_creation_in_post',
			'simdiaw_setting_section',
			'simdiaw_backend_options'
        );

        add_settings_field(
			'columns_in_edit_page',
			__( 'Columns in edit page', 'simple-diary' ),
			'simdiaw_func_cols_in_e_page',
			'simdiaw_setting_section',
			'simdiaw_backend_options'
        );

		add_settings_field(
			'date_format_pattern',
			__( 'Date format pattern', 'simple-diary' ),
			'simdiaw_func_date_form_pattern',
			'simdiaw_setting_section',
			'simdiaw_backend_options'
		);

		add_settings_field(
			'time_format_pattern',
			__( 'Time format pattern', 'simple-diary' ),
			'simdiaw_func_time_form_pattern',
			'simdiaw_setting_section',
			'simdiaw_backend_options'
		);

		add_settings_field(
			'time_interval',
			__( 'Time interval', 'simple-diary' ),
			'simdiaw_func_time_interval',
			'simdiaw_setting_section',
			'simdiaw_backend_options'
		);
	}
}

/**
 * Output of main sections and options fields
 */

/** the help section output **/
if( !function_exists('simdiaw_help_text'))  {
	function simdiaw_help_text(){
	echo '<p>'.__( 'If you need help using Simple Diary, we recommand you to visit the official plugin page. Here a few links (opening in new window):', 'simple-diary' ).' <a href="https://wordpress.org/plugins/simple-diary/" target="_blank">'.__( 'The description page', 'simple-diary' ).'</a>, <a href="https://wordpress.org/plugins/simple-diary/installation/" target="_blank">'.__( 'The installation page', 'simple-diary' ).'</a>, <a href="https://wordpress.org/plugins/simple-diary/faq/" target="_blank">'.__( 'The faq', 'simple-diary' ).'</a>, <a href="http://wordpress.org/support/plugin/simple-diary" target="_blank">'.__( 'The support page', 'simple-diary' ).'</a>.'."\n";
	echo '</p>'."\n";
	}
}

/*  ***************************  */
/** the frontend section output **/
/*  ***************************  */
if( !function_exists('simdiaw_frontend_options_text'))  {
	function simdiaw_frontend_options_text(){
	echo '<p>'.__( 'Here you can set some parameters that will impact the frontend templates (the public site).', 'simple-diary' ).'</p>';
	}
}

/** The diary title **/
function simdiaw_func_title() {
	 /* Get the option value from the database. */
	$options = get_option( 'simdiaw_settings_options' );
	$simdiaw_title = (isset($options['simdiaw_title']) && $options['simdiaw_title'] != '') ? $options['simdiaw_title'] : __('Diary', 'simple-diary') ;

	/* Echo the field. */ ?>
	<input type="text" id="simdiaw_title" name="simdiaw_settings_options[simdiaw_title]" value="<?php echo $simdiaw_title ?>" />
		<p class="description">
		    <?php _e( 'The title that will be displayed on top of diary pages.', 'simple-diary' ); ?>
        </p>
<?php }

/** The diary slug **/
function simdiaw_func_slug() {
	 /* Get the option value from the database. */
	$options = get_option( 'simdiaw_settings_options' );
	$simdiaw_slug = (isset($options['simdiaw_slug']) && $options['simdiaw_slug'] != '') ? $options['simdiaw_slug'] : __('diary', 'simple-diary') ;

	/* Echo the field. */ ?>
	<input type="text" id="simdiaw_slug" name="simdiaw_settings_options[simdiaw_slug]" value="<?php echo $simdiaw_slug ?>" />
		<p class="description">
		    <?php printf(__( 'The slug that is used to identify the reminders page. This slug must be URL friendly so only lowercase characters, numbers and "-" are allowed (no spaces, no accentuated characters). If you defined your permalinks to have nice URL in the <a href="options-permalink.php">Permalink option page</a> you can get the Diary page on <code>%1$s</code>. Alternatively, this page will be always available on <code>%2$s</code>.', 'simple-diary' ), home_url( '/' ).$simdiaw_slug, home_url( '/' ).'?post_type=reminder'); ?>
        </p>
<?php }

/** The number of upcoming reminders that should be displayed by default  **/
function simdiaw_func_u_r_count() {
	 /* Get the option value from the database. */
	$options = get_option( 'simdiaw_settings_options' );
	$upcoming_reminders_count = (isset($options['upcoming_reminders_count']) && $options['upcoming_reminders_count'] != '') ? $options['upcoming_reminders_count'] : 3 ;

	/* Echo the field. */ ?>
	<input size="3" type="number" id="upcoming_reminders_count" name="simdiaw_settings_options[upcoming_reminders_count]" value="<?php echo $upcoming_reminders_count ?>" />
		<p class="description">
		    <?php _e( 'Default number of upcoming reminders that should be displayed when using for exemple handcrafted sidebar element with <code>the_simdiaw_upcoming_reminders()</code> function. Will be overriden by value set in Simple Diary widget or in the <code>the_simdiaw_upcoming_reminders()</code> template function (ex: <code>the_simdiaw_upcoming_reminders(5)</code> will display the next 5 upcoming reminders).', 'simple-diary' ); ?>
        </p>
<?php }

/** The number of past reminders that should be displayed by default  **/
function simdiaw_func_p_r_count() {
	/* Get the option value from the database. */
   $options = get_option( 'simdiaw_settings_options' );
   $past_reminders_count = (isset($options['past_reminders_count']) && $options['past_reminders_count'] != '') ? $options['past_reminders_count'] : 10 ;

   /* Echo the field. */ ?>
   <input size="3" type="number" id="past_reminders_count" name="simdiaw_settings_options[past_reminders_count]" value="<?php echo $past_reminders_count ?>" />
	   <p class="description">
		   <?php _e( 'Default number of past reminders that should be displayed when using for exemple handcrafted sidebar element with <code>the_simdiaw_past_reminders()</code> function. Will be overriden by value set in the <code>the_simdiaw_past_reminders()</code> template function (ex: <code>the_simdiaw_past_reminders(5)</code> will display the 5 past reminders).', 'simple-diary' ); ?>
	   </p>
<?php }

/** The number of reminders that should be displayed by default in diary  **/
function simdiaw_func_d_r_count() {
	 /* Get the option value from the database. */
	$options = get_option( 'simdiaw_settings_options' );
	$diary_reminders_count = (isset($options['diary_reminders_count']) && $options['diary_reminders_count'] != '') ? $options['diary_reminders_count'] : 10 ;

	/* Echo the field. */ ?>
	<input size="3" type="number" id="diary_reminders_count" name="simdiaw_settings_options[diary_reminders_count]" value="<?php echo $diary_reminders_count ?>" />
		<p class="description">
		    <?php _e( 'Default number of reminders that should be displayed in Diary pages. Will be overriden by value set in the <code>the_simdiaw_reminders_query()</code> template function (ex: <code>the_simdiaw_reminders_query(6)</code> will display 6 reminders in each page).', 'simple-diary' ); ?>
        </p>
<?php }

/** Custom reminders settings  **/
function simdiaw_func_custom_events() {
	/* Get the option value from the database. */
   $options = get_option( 'simdiaw_settings_options' );
   $diary_custom_events = (isset($options['diary_custom_events']) && $options['diary_custom_events'] != '') ? json_decode($options['diary_custom_events'], true) : array();
   /* Echo the fields. */
   for($i = 0; $i < 12; $i++) {
	   if(isset($diary_custom_events[$i]['name']) && isset($diary_custom_events[$i]['class'])) {
		   $name_value = $diary_custom_events[$i]['name'];
		   $class_value = $diary_custom_events[$i]['class'];
	   }  else {
		   $name_value = '';
		   $class_value = '';
	   }	
   ?>
	<p><span style="display: inline-block; background: #0073AA; color: #FFF; padding: 0 3px; margin-right: 8px; border-radius: 2px;"><?php echo sprintf("%02d", ($i+1)) ?></span><?php _e('Custom reminder name:', 'simple-diary') ?> <input  style="width: 25%; margin-right: 20px;" type="text" id="diary_custom_event_name_<?php echo $i ?>" name="simdiaw_settings_options[custom_event_name_<?php echo $i ?>]" value="<?php echo $name_value ?>" /> <?php _e('Class:', 'simple-diary') ?> <input style="width: 20%;" type="text" id="diary_custom_event_class_<?php echo $i ?>" name="simdiaw_settings_options[custom_event_class_<?php echo $i ?>]" value="<?php echo $class_value ?>" /></p>
   <?php } // end for $i ?>
   <p class="description">
	   <?php _e( 'Here you can set custom reminder names. They are paired with a class that could be used for styling purposes. The class has no spaces, no accentuated or special characters, it should only contain lowercase characters, digits and dashes (valid examples : <code>important</code>, <code>the-best-event</code>, <code>anniversary-1</code>). Both, name and class can be retrieved in the frontend template by using respectively the <code>get_simdiaw_reminder_type()</code> and <code>get_simdiaw_reminder_class()</code> functions.', 'simple-diary' ); ?>
   </p>
<?php }

/** The upcomming reminder format in reminders listing **/
function simdiaw_func_u_r_format() {
	/* Get the option value from the database. */
   $options = get_option( 'simdiaw_settings_options' );
   $diary_upcoming_reminders_format = (isset($options['diary_upcoming_reminders_format']) && $options['diary_upcoming_reminders_format'] != '') ? $options['diary_upcoming_reminders_format'] : __('<a href="#url">#title</a><br>Date&nbsp;: #date<br>Location&nbsp;: #location', 'simple-diary') ;

   /* Echo the field. */
   ?>
   <input type="text" style="width: 90%" id="diary_upcoming_reminders_format" name="simdiaw_settings_options[diary_upcoming_reminders_format]" value='<?php echo $diary_upcoming_reminders_format ?>' />
	   <p class="description">
		   <?php echo __( 'The format of each reminder in the <strong>upcomming</strong> reminders listing. All HTML tags are allowed, and the following placeholders can be used to display there substitute: <code>#url</code> will be replaced by the <em>reminder url</em> (address to the reminder), <code>#title</code> will be replaced by the <em>reminder title</em>, <code>#date</code> will be replaced by the <em>reminder date</em> (if there is an end date, both, start and end date will be displayed with a dash for separation character), <code>#location</code> will be replaced by the <em>reminder location<em>.', 'simple-diary') ?>
	   </p>
<?php }

/** The past reminder format in reminders listing **/
function simdiaw_func_p_r_format() {
	/* Get the option value from the database. */
   $options = get_option( 'simdiaw_settings_options' );
   $diary_past_reminders_format = (isset($options['diary_past_reminders_format']) && $options['diary_past_reminders_format'] != '') ? $options['diary_past_reminders_format'] : __('<a href="#url">#title</a><br>Date&nbsp;: #date<br>Location&nbsp;: #location', 'simple-diary') ;

   /* Echo the field. */
   ?>
   <input type="text" style="width: 90%" id="diary_past_reminders_format" name="simdiaw_settings_options[diary_past_reminders_format]" value='<?php echo $diary_past_reminders_format ?>' />
	   <p class="description">
		   <?php echo __( 'The format of each reminder in the <strong>past</strong> reminders listing. All HTML tags are allowed, and the following placeholders can be used to display there substitute: <code>#url</code> will be replaced by the <em>reminder url</em> (address to the reminder), <code>#title</code> will be replaced by the <em>reminder title</em>, <code>#date</code> will be replaced by the <em>reminder date</em> (if there is an end date, both, start and end date will be displayed with a dash for separation character), <code>#location</code> will be replaced by the <em>reminder location<em>.', 'simple-diary') ?>
	   </p>
<?php }

/*  **************************  */
/** the backend section output **/
/*  **************************  */
if( !function_exists('simdiaw_backend_options_text'))  {
	function simdiaw_backend_options_text(){
	echo '<p>'.__( 'Here you can set some parameters that will impact the backend system (management of the reminders on administration side).', 'simple-diary' ).'</p>';
	}
}

/** Enable the reminder creation in post **/
if( !function_exists('simdiaw_func_cols_in_e_page'))  {
	function simdiaw_func_enable_reminder_creation_in_post() {
	/* Get the option value from the database. */
   $options = get_option( 'simdiaw_settings_options' );
   $checked_if_enabled = (isset($options['enable_reminder_creation_in_post']) && $options['enable_reminder_creation_in_post'] == 1) ? ' checked="checked"' : '' ;

   /* Echo the field. */ ?>
   <input type="checkbox" id="enable_reminder_creation_in_post" name="simdiaw_settings_options[enable_reminder_creation_in_post]" value="1"<?php echo $checked_if_enabled ?> /><?php echo __( 'Enable reminder creation in post', 'simple-diary' ); ?> 
	   <p class="description">
		   <?php echo __( 'Enable or disable the creation of a reminder in a post compose window (in sidebar or in admin Toolbar) ', 'simple-diary' ); ?>
	   </p>
<?php }
}


/** The column that should display in edit page **/
if( !function_exists('simdiaw_func_cols_in_e_page'))  {
	function simdiaw_func_cols_in_e_page(){
	/* Get the option value from the database. */
		$options = get_option( 'simdiaw_settings_options' );
		$columns_in_edit_page = (isset($options['columns_in_edit_page']) && is_array($options['columns_in_edit_page'])) ? $options['columns_in_edit_page'] : array('end_date_column', 'location_column');
		/* Echo the field. */ ?>
		<label style="padding-left: 10px;" for="end_date_column"><?php _e( 'Ending date', 'simple-diary' ); ?></label>
		<input id="end_date_column" name="simdiaw_settings_options[columns_in_edit_page][]" type="checkbox" value="end_date_column" <?php if (in_array('end_date_column', $columns_in_edit_page)) echo'checked="checked"' ; ?> />
		<label style="padding-left: 10px;" for="start_time_column"><?php _e( 'Starting time', 'simple-diary' ); ?></label>
		<input id="start_time_column" name="simdiaw_settings_options[columns_in_edit_page][]" type="checkbox" value="start_time_column" <?php if (in_array('start_time_column', $columns_in_edit_page)) echo'checked="checked"' ; ?> />
		<label style="padding-left: 10px;" for="end_time_column"><?php _e( 'Ending time', 'simple-diary' ); ?></label>
		<input id="end_time_column" name="simdiaw_settings_options[columns_in_edit_page][]" type="checkbox" value="end_time_column" <?php if (in_array('end_time_column', $columns_in_edit_page)) echo'checked="checked"' ; ?> />
		<label style="padding-left: 10px;" for="location_column"><?php _e( 'Location', 'simple-diary' ); ?></label>
		<input id="location_column" name="simdiaw_settings_options[columns_in_edit_page][]" type="checkbox" value="location_column" <?php if (in_array('location_column', $columns_in_edit_page)) echo'checked="checked"' ; ?> />
		<label style="padding-left: 10px;" for="creation_column"><?php _e( 'Created or modified', 'simple-diary' ); ?></label>
		<input id="creation_column" name="simdiaw_settings_options[columns_in_edit_page][]" type="checkbox" value="creation_column" <?php if (in_array('creation_column', $columns_in_edit_page)) echo'checked="checked"' ; ?> />
		<p class="description">
		    <?php _e( 'The columns that should be displayed in edit page. Title and starting date displayed by default.', 'simple-diary' ); ?>
        </p>
	<?php }
}

/** The date format pattern **/
function simdiaw_func_date_form_pattern() {
	 /* Get the option value from the database. */
	$options = get_option( 'simdiaw_settings_options' );
	$date_format_pattern = (isset($options['date_format_pattern']) && $options['date_format_pattern'] != '') ? $options['date_format_pattern'] : __('ddd, d mmm yyyy', 'simple-diary') ;

	/* Echo the field. */ ?>
	<input type="text" id="date_format_pattern" name="simdiaw_settings_options[date_format_pattern]" value="<?php echo $date_format_pattern ?>" />
		<p class="description">
		    <?php echo sprintf(__( 'The pattern that should be used to display the date in the date field. To get the rules to format it, please go to the <a href="%s" target="_blanck">Date formatting rules section of pickadate Website</a>.', 'simple-diary' ), 'http://amsul.ca/pickadate.js/date/#formatting-rules'); ?>
        </p>
<?php }

/** The time format pattern **/
function simdiaw_func_time_form_pattern() {
	 /* Get the option value from the database. */
	$options = get_option( 'simdiaw_settings_options' );
	$time_format_pattern = (isset($options['time_format_pattern']) && $options['time_format_pattern'] != '') ? $options['time_format_pattern'] : __('hh:i A', 'simple-diary') ;

	/* Echo the field. */ ?>
	<input type="text" id="time_format_pattern" name="simdiaw_settings_options[time_format_pattern]" value="<?php echo $time_format_pattern ?>" />
		<p class="description">
		    <?php echo sprintf(__( 'The pattern that should be used to display the time in the date field. To get the rules to format it, please go to the <a href="%s" target="_blanck">Time formatting rules section of pickadate Website</a>.', 'simple-diary' ), 'http://amsul.ca/pickadate.js/time/#formatting-rules'); ?>
        </p>
<?php }

/** The time interval **/
function simdiaw_func_time_interval() {
	/* Get the option value from the database. */
   $options = get_option( 'simdiaw_settings_options' );
   $time_interval = (isset($options['time_interval']) && $options['time_interval'] > 0) ? $options['time_interval'] : 30 ;

   /* Echo the field. */ ?>
   <input type="number" id="time_interval" name="simdiaw_settings_options[time_interval]" value="<?php echo $time_interval ?>"  maxlength="4" />
	   <p class="description">
		   <?php echo __( 'The interval between each time display in minutes.', 'simple-diary' ); ?>
	   </p>
<?php }

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
if( !function_exists('simdiaw_options_validate'))  {
	function simdiaw_options_validate( $input ) {
	    $options = get_option( 'simdiaw_settings_options' );

	     /** Title validation **/
	     if (!isset($options['simdiaw_title']) || $options['simdiaw_title'] != $input['simdiaw_title']) {
	        $options['simdiaw_title'] = ($input['simdiaw_title'] != '') ? wp_filter_nohtml_kses($input['simdiaw_title']) : __('Diary', 'simple-diary');
        }

	    /** Slug validation **/
	    if (!isset($options['simdiaw_slug']) || $options['simdiaw_slug'] != $input['simdiaw_slug']) {
	        $options['simdiaw_slug'] = ($input['simdiaw_slug'] != '') ? urlencode($input['simdiaw_slug']) : __('diary', 'simple-diary');
        }

        /** upcoming reminders count validation **/
        if (!isset($options['upcoming_reminders_count']) || $options['upcoming_reminders_count'] != $input['upcoming_reminders_count']) {
            $options['upcoming_reminders_count'] = ($input['upcoming_reminders_count'] > 0) ? wp_filter_nohtml_kses(intval($input['upcoming_reminders_count'])) : 3;
		}
		
		/** past reminders count validation **/
        if (!isset($options['past_reminders_count']) || $options['past_reminders_count'] != $input['past_reminders_count']) {
            $options['past_reminders_count'] = ($input['past_reminders_count'] > 0) ? wp_filter_nohtml_kses(intval($input['past_reminders_count'])) : 3;
        }

	    /** diary reminders count validation **/
        if (!isset($options['diary_reminders_count']) || $options['diary_reminders_count'] != $input['diary_reminders_count']) {
            $options['diary_reminders_count'] = ($input['diary_reminders_count'] > 0) ? wp_filter_nohtml_kses(intval($input['diary_reminders_count'])) : 10;
		}
		
		/** custom events validation **/
		$custom_events_array = array();
		for($i = 0; $i < 12; $i++) {
        	if (isset($input['custom_event_name_'.$i]) && isset($input['custom_event_class_'.$i]) && $input['custom_event_class_'.$i] != '' && $input	['custom_event_name_'.$i] != '') {
				// filter the name and class format
				$name = $input['custom_event_name_'.$i];
				$class = strtolower(str_replace(' ', '-', $input['custom_event_class_'.$i]));
				// Define the name and class in array
				$custom_events_array[$i]['name'] = $name;
				$custom_events_array[$i]['class'] = $class;
        	}
		}
		$custom_events_json = json_encode($custom_events_array);
		$options['diary_custom_events'] = $custom_events_json;
		// test
		//print_r(json_decode($custom_events_json, true));

		/** upcoming reminders format validation **/
	    if (!isset($options['diary_upcoming_reminders_format']) || $options['diary_upcoming_reminders_format'] != $input['diary_upcoming_reminders_format']) {
	        $options['diary_upcoming_reminders_format'] = ($input['diary_upcoming_reminders_format'] != '') ? $input['diary_upcoming_reminders_format'] : __('<a href="#url">#title</a><br>Date&nbsp;: #date<br>Location&nbsp;: #location', 'simple-diary');
		}
		
		/** past reminders format validation **/
	    if (!isset($options['diary_past_reminders_format']) || $options['diary_past_reminders_format'] != $input['diary_past_reminders_format']) {
	        $options['diary_past_reminders_format'] = ($input['diary_past_reminders_format'] != '') ? $input['diary_past_reminders_format'] : __('<a href="#url">#title</a><br>Date&nbsp;: #date<br>Location&nbsp;: #location', 'simple-diary');
		}

		
		/** enable reminder creation in post validation **/
        if (isset($input['enable_reminder_creation_in_post'])) {
			$options['enable_reminder_creation_in_post'] = 1;
		} else {
			$options['enable_reminder_creation_in_post'] = 0;
		}

        /** edit page columns validation **/
        if (!isset($options['columns_in_edit_page']) || $options['columns_in_edit_page'] != $input['columns_in_edit_page']) {
            $options['columns_in_edit_page'] = $input['columns_in_edit_page'];
        }

		/** Date format pattern validation **/
        if (!isset($options['date_format_pattern']) || ($options['date_format_pattern'] != $input['date_format_pattern'] && $input['date_format_pattern'] != '')) {
            $options['date_format_pattern'] = $input['date_format_pattern'];
        }

		/** Time format pattern validation **/
        if (!isset($options['time_format_pattern']) || ($options['time_format_pattern'] != $input['time_format_pattern'] && $input['time_format_pattern'] != '')) {
            $options['time_format_pattern'] = $input['time_format_pattern'];
		}
		
		/** Time interval **/
        if (!isset($options['time_interval']) || ($options['time_interval'] != $input['time_interval'] && $input['time_interval'] > 0)) {
            $options['time_interval'] = $input['time_interval'];
        }

	    return $options;
	}
}
