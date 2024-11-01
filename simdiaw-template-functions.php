<?php

/* **************************************************** */
/* The options values                                   */
/* **************************************************** */
$options = get_option( 'simdiaw_settings_options' );
$simdiaw_title = (isset($options['simdiaw_title']) && $options['simdiaw_title'] != '') ? $options['simdiaw_title'] : __('Diary', 'simple-diary');
$simdiaw_slug = (isset($options['simdiaw_slug']) && $options['simdiaw_slug'] != '') ? $options['simdiaw_slug'] : __('diary', 'simple-diary');
$page_reminders_count = (isset($options['page_reminders_count']) && $options['page_reminders_count'] > 0) ? $options['page_reminders_count'] : 10;
$upcoming_reminders_count = (isset($options['upcoming_reminders_count']) && $options['upcoming_reminders_count'] > 0) ? $options['upcoming_reminders_count'] : 3;
$diary_custom_events = (isset($options['diary_custom_events']) && $options['diary_custom_events'] != '') ? json_decode($options['diary_custom_events'], true) : false;
$diary_upcoming_reminders_format = (isset($options['diary_upcoming_reminders_format']) && $options['diary_upcoming_reminders_format'] != '') ? $options['diary_upcoming_reminders_format'] : __('<a href="#url">#title</a><br>Date&nbsp;: #date<br>Location&nbsp;: #location', 'simple-diary') ;
$diary_past_reminders_format = (isset($options['diary_past_reminders_format']) && $options['diary_past_reminders_format'] != '') ? $options['diary_past_reminders_format'] : __('<a href="#url">#title</a><br>Date&nbsp;: #date<br>Location&nbsp;: #location', 'simple-diary') ;

/* **************************************************** */
/* The functions available in the theme template        */
/* **************************************************** */
/**
 * Display or Retrieve the start date of the current reminder
 *
 * @param string $d Optional. PHP date format defaults to the date_format option if not specified.
 * @param  int|WP_Post $post Optional. Post ID or WP_Post object. Default current post.
 * @param bool $echo Optional, default is not display (value returned). Whether to echo the date or return it.
 * @return string|null Null if displaying, string if retrieving.
 */
function get_simdiaw_start_date( $d = '', $post = null, $echo = false ) {
	$post = get_post( $post );
	$post_id = $post->ID;
	$start_date = get_post_meta( $post_id, 'simdiaw-start-date', true );
    // Take in account the date format
	if ( '' == $d ) {
		$the_start_date = date_i18n( get_option( 'date_format' ), strtotime( $start_date ) );
	} else {
		$the_start_date = date_i18n( $d, strtotime( $start_date ) );
	}
	// Whether echo or return the value
	if ( $echo )
        echo $the_start_date;
    else
        return $the_start_date;
}

/**
 * Display or Retrieve the end date of the current reminder
 *
 * @param string $d Optional. PHP date format defaults to the date_format option if not specified.
 * @param  int|WP_Post $post Optional. Post ID or WP_Post object. Default current post.
 * @param bool $echo Optional, default is not display (value returned). Whether to echo the date or return it.
 * @return string|null Null if displaying, string if retrieving.
 */
function get_simdiaw_end_date( $d = '', $post = null, $echo = false ) {
	$post = get_post( $post );
    $post_id = $post->ID;
    $start_date = get_post_meta( $post_id, 'simdiaw-start-date', true );
    $end_date = (get_post_meta( $post_id, 'simdiaw-end-date', true ) != '') ? get_post_meta( $post_id, 'simdiaw-end-date', true ) : $start_date;

    // Take in account the date format
    if ( '' == $d ) {
        $the_end_date = date_i18n( get_option( 'date_format' ), strtotime( $end_date ) );
    } else {
        $the_end_date = date_i18n( $d, strtotime( $end_date ) );
    }
    // Whether echo or return the value
    if ( $echo )
        echo $the_end_date;
    else
        return $the_end_date;
}

/**
 * Display or Retrieve the start time of the current reminder
 *
 * @param string $t Optional. PHP date format defaults to the time_format option if not specified.
 * @param  int|WP_Post $post Optional. Post ID or WP_Post object. Default current post.
 * @param bool $echo Optional, default is not display (value returned). Whether to echo the time or return it.
 * @return string|null Null if displaying, string if retrieving.
 */
function get_simdiaw_start_time( $t = '', $post = null, $echo = false ) {
	$post = get_post( $post );
	$post_id = $post->ID;
	$start_time = get_post_meta( $post_id, 'simdiaw-start-time', true );

	if ($start_time != '') {
	    // Take in account the time format
	    if ( '' == $t ) {
	    	$the_start_time = date_i18n( get_option( 'time_format' ), strtotime( $start_time ) );
	    } else {
	    	$the_start_time = date_i18n( $d, strtotime( $start_time ) );
	    }
	    // Whether echo or return the value
	    if ( $echo )
            echo $the_start_time;
        else
            return $the_start_time;
    } else {
        return false;
    }
}


/**
 * Display or Retrieve the end time of the current reminder
 *
 * @param string $t Optional. PHP date format defaults to the time_format option if not specified.
 * @param  int|WP_Post $post Optional. Post ID or WP_Post object. Default current post.
 * @param bool $echo Optional, default is not display (value returned). Whether to echo the time or return it.
 * @return string|null Null if displaying, string if retrieving.
 */
function get_simdiaw_end_time( $t = '', $post = null, $echo = false ) {
	$post = get_post( $post );
	$post_id = $post->ID;
	$end_time = get_post_meta( $post_id, 'simdiaw-end-time', true );

	if ($end_time != '') {
	    // Take in account the time format
	    if ( '' == $t ) {
	    	$the_end_time = date_i18n( get_option( 'time_format' ), strtotime( $end_time ) );
	    } else {
	    	$the_end_time = date_i18n( $d, strtotime( $end_time ) );
	    }
	    // Whether echo or return the value
	    if ( $echo )
            echo $the_end_time;
        else
            return $the_end_time;
    } else {
        return false;
    }
}

/**
 * Display or Retrieve the location of the current reminder
 *
 * @param  int|WP_Post $post Optional. Post ID or WP_Post object. Default current post.
 * @param bool $echo Optional, default is not display (value returned). Whether to echo the time or return it.
 * @return string|null Null if displaying, string if retrieving.
 */
function get_simdiaw_location( $post = null, $echo = false ) {
    $post = get_post( $post );
	$post_id = $post->ID;
	$location = get_post_meta( $post_id, 'simdiaw-loc', true );

    // Whether echo or return the value
    if ( $echo )
        echo $location;
    else
        return $location;
}

/**
 * Display or Retrieve the url of the current reminder
 *
 * @param  int|WP_Post $post Optional. Post ID or WP_Post object. Default current post.
 * @param bool $echo Optional, default is not display (value returned). Whether to echo the url or return it.
 * @return string|null Null if displaying, string if retrieving.
 */
function get_simdiaw_url( $post = null, $echo = false ) {
    $post = get_post( $post );
	$post_id = $post->ID;
	$url = get_post_meta( $post_id, 'simdiaw-url', true );

	if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
	    // Whether echo or retrun the value
	    if ( $echo )
            echo $url;
        else
            return $url;
    } else {
        return false;
    }

}

/**
 * Display or Retrieve the article id of the current reminder
 *
 * @param  int|WP_Post $post Optional. Post ID or WP_Post object. Default current post.
 * @param bool $echo Optional, default is not display (value returned). Whether to echo the article url or return it.
 * @return string|null Null if displaying, string if retrieving.
 */
function get_simdiaw_article( $post = null, $echo = false ) {
    $post = get_post( $post );
	$post_id = $post->ID;
	$art_id = get_post_meta( $post_id, 'simdiaw-art-id', true );

	if ($art_id > 0 && get_post_status( $art_id  ) == 'publish') {
	    $article_link = get_permalink( $art_id );
	    // Whether echo or retrun the value
	    if ( $echo )
            echo $article_link;
        else
            return $article_link;
    } else {
        return false;
    }
}

/**
 * Display or Retrieve the link text of the current reminder
 *
 * @param  int|WP_Post $post Optional. Post ID or WP_Post object. Default current post.
 * @param bool $echo Optional, default is not display (value returned). Whether to echo the time or return it.
 * @return string|null Null if displaying, string if retrieving.
 */
function get_simdiaw_link_text( $post = null, $echo = false ) {
    $post = get_post( $post );
	$post_id = $post->ID;
	$link_text = get_post_meta( $post_id, 'simdiaw-link-text', true );

    // Whether echo or return the value
    if ( $echo )
        echo $link_text;
    else
        return $link_text;
}

/**
 * Display or Retrieve the reminder type class
 *
 * @param  int|WP_Post $post Optional. Post ID or WP_Post object. Default current post.
 * @param bool $echo Optional, default is not display (value returned). Whether to echo the article url or return it.
 * @return string|null Null if displaying, string if retrieving.
 */
function get_simdiaw_reminder_class( $post = null, $echo = false ) {
    $post = get_post( $post );
	$post_id = $post->ID;
	$reminder_type = get_post_meta( $post_id, 'simdiaw-type', true );

	if ($reminder_type != '') {
	    // Whether echo or return the value
	    if ( $echo )
            echo $reminder_type;
        else
            return $reminder_type;
    } else {
        return false;
    }
}

/**
 * Display or Retrieve the reminder type name
 *
 * @param  int|WP_Post $post Optional. Post ID or WP_Post object. Default current post.
 * @param bool $echo Optional, default is not display (value returned). Whether to echo the article url or return it.
 * @return string|null Null if displaying, string if retrieving.
 */
function get_simdiaw_reminder_type( $post = null, $echo = false ) {
    $post = get_post( $post );
	$post_id = $post->ID;
	$reminder_type = get_post_meta( $post_id, 'simdiaw-type', true );

	if ($reminder_type != '') {
        // Get the name of the reminder
        global $diary_custom_events;
        $reminder_name = false;
        foreach($diary_custom_events as $i => $ar) {
            if($ar['class'] == $reminder_type) {
                $reminder_name = $ar['class'];
                break;
            }
        }
        if($reminder_name) {
	        // Whether echo or return the value
	        if ( $echo )
                echo $reminder_name;
            else
                return $reminder_name;
        } else {
            return false;
        }

    } else {
        return false;
    }
}

/**
 * The conditionnal functions
*/

function has_simdiaw_end_date ( $post = null ) {
    $post = get_post( $post );
    $post_id = $post->ID;
    $end_date = get_post_meta( $post_id, 'simdiaw-end-date', true );
    $has = ($end_date != '') ? true : false;
    return $has;
}

function has_simdiaw_start_time ( $post = null ) {
    $post = get_post( $post );
    $post_id = $post->ID;
    $start_time = get_post_meta( $post_id, 'simdiaw-start-time', true );
    $has = ($start_time != '') ? true : false;
    return $has;
}

function has_simdiaw_end_time ( $post = null ) {
    $post = get_post( $post );
    $post_id = $post->ID;
    $end_time = get_post_meta( $post_id, 'simdiaw-end-time', true );
    $has = ($end_time != '') ? true : false;
    return $has;
}

function has_simdiaw_url ( $post = null ) {
    $post = get_post( $post );
    $post_id = $post->ID;
    $url = get_post_meta( $post_id, 'simdiaw-url', true );
    $has = ($url != '') ? true : false;
    return $has;
}

function has_simdiaw_article_id ( $post = null ) {
    $post = get_post( $post );
    $post_id = $post->ID;
    $art_id = get_post_meta( $post_id, 'simdiaw-art-id', true );
    $has = ($art_id != '') ? true : false;
    return $has;
}

function has_simdiaw_link_text ( $post = null ) {
    $post = get_post( $post );
    $post_id = $post->ID;
    $link_text = get_post_meta( $post_id, 'simdiaw-link-text', true );
    $has = (isset($link_text) && $link_text != '') ? true : false;
    return $has;
}

/**
 * More functions to display date, time, link,...
*/

function get_simdiaw_title($echo = false) {
    $options = get_option( 'simdiaw_settings_options' );
    $simdiaw_title = ($options['simdiaw_title'] != '') ? $options['simdiaw_title'] : __('Diary', 'simple-diary');
    if ($echo) { echo $simdiaw_title; }
    else { return $simdiaw_title; }
}

function get_simdiaw_slug($echo = false) {
    $options = get_option( 'simdiaw_settings_options' );
    $simdiaw_slug = ($options['simdiaw_slug'] != '') ? $options['simdiaw_slug'] : __('diary', 'simple-diary');
    if ($echo) { echo $simdiaw_slug; }
    else { return $simdiaw_slug; }
}

function the_simdiaw_date() {
    if (has_simdiaw_end_date() && get_simdiaw_start_date() != get_simdiaw_end_date())
        echo get_simdiaw_start_date().' - '.get_simdiaw_end_date();
    else
        echo get_simdiaw_start_date();
}

function the_simdiaw_time() {
    if (has_simdiaw_start_time() && has_simdiaw_end_time())
        echo '<br>'.get_simdiaw_start_time().' - '.get_simdiaw_end_time();
    elseif (has_simdiaw_start_time())
        echo '<br>'.get_simdiaw_start_time();
    else
        return false;
}

function the_simdiaw_location() {
        echo '<p class="location" itemprop="location" itemscope itemtype="http://schema.org/Place">'.__('Location:', 'simple-diary').' <span itemprop="name">'.get_simdiaw_location().'</span></p>';
}

function the_simdiaw_link() {
    if (has_simdiaw_article_id()) {
        if(has_simdiaw_link_text())
            echo '<p class="associated-link"><a href="'.get_simdiaw_article().'" itemprop="url">'.get_simdiaw_link_text().'</a></p>';
        else
            echo '<p class="associated-link"><a href="'.get_simdiaw_article().'" itemprop="url">'.__('Related article', 'simple-diary').'</a></p>'; 
    }
    elseif (has_simdiaw_url()) {
        if(has_simdiaw_link_text())
            echo '<p class="associated-link"><a href="'.get_simdiaw_url().'" itemprop="url">'.get_simdiaw_link_text().'</a></p>';
        else
            echo '<p class="associated-link"><a href="'.get_simdiaw_url().'" itemprop="url">'.__('Related external resource', 'simple-diary').'</a></p>';
    }
    else
        echo '';
}

function the_simdiaw_meta_date() {
    $post = get_post();
	$post_id = $post->ID;
	$start_date = get_post_meta( $post_id, 'simdiaw-start-date', true );
	$start_time = get_post_meta( $post_id, 'simdiaw-start-time', true );
    if (has_simdiaw_start_time())
        echo '<meta itemprop="startDate" content="'.$start_date.'T'.$start_time.'">';
    else
        echo '<meta itemprop="startDate" content="'.$start_date.'">';
}

function the_simdiaw_reminders_query($n = null, $order = 'ASC') {
    $options = get_option( 'simdiaw_settings_options' );
    $reminders_count = ($options['diary_reminders_count'] > 0) ? $options['diary_reminders_count'] : 10;
    $n = (isset($n)) ? $n : $reminders_count;
    $order = (isset($order)) ? $order : 'ASC';
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'post_type'         => 'reminder',
        'meta_key'          => 'simdiaw-start-date',
        'orderby'           => 'meta_value',
        'order'             => $order,
        'posts_per_page'    => $n,
        'paged'             => $paged
    );
    query_posts( $args );
}

function the_simdiaw_upcoming_reminders_query($n = null, $order = 'ASC') {
    $options = get_option( 'simdiaw_settings_options' );
    $upcoming_reminders_count = ($options['upcoming_reminders_count'] > 0) ? $options['upcoming_reminders_count'] : 3;
    $n = (isset($n)) ? $n : $upcoming_reminders_count;
    $order = (isset($order)) ? $order : 'ASC';
    $dtoday = date("Y-m-d");
    $args = array(
		'post_type' => 'reminder',
		'posts_per_page' => $n,
		'meta_key' => 'simdiaw-start-date',
		'orderby' => 'meta_value',
		'order' => $order,
		'meta_query' => array (
			array(
				'key' => 'simdiaw-start-date',
				'value' => $dtoday,
				'compare' => '>='
			)
		)
	);
    query_posts( $args );
}

function the_simdiaw_past_reminders_query($n = null, $order = 'DESC') {
    $options = get_option( 'simdiaw_settings_options' );
    $past_reminders_count = ($options['past_reminders_count'] > 0) ? $options['past_reminders_count'] : 10;
    $n = (isset($n)) ? $n : $past_reminders_count;
    $order = (isset($order)) ? $order : 'DESC';
    $dtoday = date("Y-m-d");
    $args = array(
		'post_type' => 'reminder',
		'posts_per_page' => $n,
		'meta_key' => 'simdiaw-end-date',
		'orderby' => 'meta_value',
		'order' => $order,
		'meta_query' => array (
			array(
				'key' => 'simdiaw-end-date',
				'value' => $dtoday,
				'compare' => '<'
			)
		)
	);
    query_posts( $args );
}

function the_simdiaw_upcoming_reminders($n = null) {
    $options = get_option( 'simdiaw_settings_options' );
    $upcoming_reminders_count = ($options['upcoming_reminders_count'] > 0) ? $options['upcoming_reminders_count'] : 3;
    $n = (isset($n)) ? $n : $upcoming_reminders_count;
	$dtoday = date("Y-m-d");
	$args = array(
		'post_type' => 'reminder',
		'posts_per_page' => $n,
		'meta_key' => 'simdiaw-start-date',
		'orderby' => 'meta_value',
		'order' => 'ASC',
		'meta_query' => array (
			array(
				'key' => 'simdiaw-start-date',
				'value' => $dtoday,
				'compare' => '>='
			)
		)
	);
	$upcoming_reminders = new WP_Query( $args );
    while ( $upcoming_reminders->have_posts() ) : $upcoming_reminders->the_post();
        global $simdiaw_slug;
        global $diary_upcoming_reminders_format;
		// Get the custom field date value
		$start_date = get_post_meta( get_the_ID(), 'simdiaw-start-date', true );
		$end_date = get_post_meta( get_the_ID(), 'simdiaw-end-date', true );
        $location = get_post_meta( get_the_ID(), 'simdiaw-loc', true );
		// Testing end date to display single date or date scope
		$reminder_date = ($end_date == '' || $end_date == $start_date ) ? get_simdiaw_start_date() : get_simdiaw_start_date().'&nbsp;-&nbsp;'.get_simdiaw_end_date();
        $reminder_location = $location;
        $reminder_title = get_the_title();
        // $reminder_url = esc_url( get_permalink() );
        $reminder_url = home_url( '/' ).$simdiaw_slug.'/#reminder-'.get_the_ID();
        // Replacing placeholders by there substitute
        $place_holders_search = array('#title', '#url', '#date', '#location');
        $substitute_replace = array($reminder_title, $reminder_url, $reminder_date, $reminder_location);
        $reminder_line = str_replace($place_holders_search, $substitute_replace, $diary_upcoming_reminders_format);
		// Printing the line
		echo "\t".'<li>'.$reminder_line.'</li>'."\n";
	endwhile;
}

function the_simdiaw_past_reminders($n = null) {
    $options = get_option( 'simdiaw_settings_options' );
    $past_reminders_count = ($options['past_reminders_count'] > 0) ? $options['past_reminders_count'] : 3;
    $n = (isset($n)) ? $n : $past_reminders_count;
	$dtoday = date("Y-m-d");
	$args = array(
		'post_type' => 'reminder',
		'posts_per_page' => $n,
		'meta_key' => 'simdiaw-end-date',
		'orderby' => 'meta_value',
		'order' => 'DESC',
		'meta_query' => array (
			array(
				'key' => 'simdiaw-end-date',
				'value' => $dtoday,
				'compare' => '<'
			)
		)
	);
	$past_reminders = new WP_Query( $args );
	while ( $past_reminders->have_posts() ) : $past_reminders->the_post();
		// Get the custom field date value
		$start_date = get_post_meta( get_the_ID(), 'simdiaw-start-date', true );
		$end_date = get_post_meta( get_the_ID(), 'simdiaw-end-date', true );
		$location = get_post_meta( get_the_ID(), 'simdiaw-loc', true );
		// Testing end date to display single date or date scope
		$reminder_date = ($end_date == '' || $end_date == $start_date ) ? __('Date:', 'simple-diary').' '.get_simdiaw_start_date() : __('Date:', 'simple-diary').' '.get_simdiaw_start_date().' - '.get_simdiaw_end_date();
		$reminder_location = __('Location:', 'simple-diary').' '.$location;
		// Printing the line
		echo "\t".'<li><a href="'.esc_url( get_permalink() ).'">'.get_the_title().'</a><br>'.$reminder_date.'<br>'.$reminder_location.'</li>'."\n";
	endwhile;
}
