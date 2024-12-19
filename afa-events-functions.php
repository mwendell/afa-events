<?php

// ========================================================================
// THE FOLLOWING FUNCTIONS ARE GLOBALLY AVAILABLE OUTSIDE OF THE PLUGIN
// ========================================================================

function afa_events_homepage() {

	$fallback_image = get_fallback_image('events');

	global $wpdb;

	$yesterday = intval( date( 'Ymd' ) ) - 1;

	$sql = "SELECT * FROM wp_posts p
		JOIN wp_postmeta ms on (p.ID = ms.post_id) AND (ms.meta_key = 'times_start_date')
		JOIN wp_postmeta me on (p.ID = me.post_id) AND (me.meta_key = 'times_end_date')
		WHERE (p.post_type = 'event') AND (p.post_status = 'publish') AND ( ms.meta_value > {$yesterday} OR me.meta_value > {$yesterday} )
		ORDER BY ms.meta_value ASC
		LIMIT 4;";
	$events = $wpdb->get_results( $sql );

	if ( is_wp_error( $events ) || empty( $events ) ) { return; }

	echo "<div class='views-entity-embed'>";
	echo "<div class='view-events-preview py-5' data-yesterday='{$yesterday}'>";
	echo "<div class='container'>";
	echo "<div class='view-header'><h2>Upcoming Events</h2><a class='btn primary' href='/events/'>View All Events</a></div>";
	echo "<div class='view-content'>";

	foreach ( $events as $event ) {
		$event_id = $event->ID;
		$event_title = $event->post_title;
		$offsite_link = wp_http_validate_url( get_field( 'offsite_link', $event_id ) );
		$event_date_array = get_field( 'times', $event_id );
		$event_date = afa_events_process_date( $event_date_array, true );
		$event_datestring = ( isset( $event_date_array['start_date'] ) ) ? $event_date_array['start_date'] : '';
		$event_image = get_field( 'event_thumbnail', $event_id );
		if ( empty( $event_image ) ) { $event_image = $fallback_image; }
		$event_link = ( $offsite_link ) ? $offsite_link : get_the_permalink( $event_id );


		echo "<div class='views-row'>";
		echo "<article role='article' class='node event latest'>";

		// IMAGE
		echo "<div class='img'><img src='{$event_image}' title='' style=''></div>";

		// TITLE AND DATE
		echo "<div class='content'><div>";
		echo "<h3><span class='field field--name-title field--type-string field--label-hidden'>{$event_title}</span></h3>";
		echo "<div class='date-time'><div class='date'><i class='icon-calendar'></i>&nbsp;<time datetime='{$event_datestring}' class='datetime'>{$event_date}</time></div></div>";
		echo "</div></div>"; // .content

		// BUTTON
		echo "<a href='{$event_link}'>Learn More</a>";

		echo "</article>";
		echo "</div>";

	}

	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</div>";

}

function afa_events_process_date( $date = false, $hide_time = false ){

	if ( ! $date || ! is_array( $date ) || ! $date['start_date'] ) { return false; }

	$start_date = array_filter( explode( '-', $date['start_date'] ) );
	$start_time = ( $date['start_time'] ) ? strtolower( date( 'g:i A', strtotime( $date['start_time'] ) ) ) : '';
	$end_date = array_filter( explode( '-', $date['end_date'] ) );
	$end_time = ( $date['end_time'] ) ? strtolower( date( 'g:i A', strtotime( $date['end_time'] ) ) ) : '';
	$time_zone = $date['time_zone'];

	$output = '';

	$months = array( 0,'January','February','March','April','May','June','July','August','September','October','November','December' );

	$start_date = array_map( function( $n ) { return intval( $n ); }, $start_date );
	$end_date = array_map( function( $n ) { return intval( $n ); }, $end_date );

	if ( empty( $end_date ) || $end_date == $start_date ) {

		$output = $months[$start_date[1]] . ' ' . $start_date[2] . ', ' . $start_date[0];

	} else {

		if ( $start_date[0] != $end_date[0] ) { // different years

			$output = $months[$start_date[1]] . ' ' . $start_date[2] . ', ' . $start_date[0] . ' to ' . $months[$end_date[1]] . ' ' . $end_date[2] . ', ' . $end_date[0];

		} elseif ( $start_date[1] != $end_date[1] ) { // different months

			$output = $months[$start_date[1]] . ' ' . $start_date[2] . ' to ' . $months[$end_date[1]] . ' ' . $end_date[2] . ', ' . $end_date[0];

		} elseif ( $start_date[2] != $end_date[2] ) { // different days

			$output = $months[$start_date[1]] . ' ' . $start_date[2] . ' to ' . $end_date[2] . ', ' . $end_date[0];

		}

	}

	if ( $start_time && ! $hide_time ) {

		if ( empty( $end_time ) || $end_time == $start_time ) {
			$output .= ' at ' . $start_time;
		} else {
			$output .= ' from ' . $start_time . ' to ' . $end_time;
		}

		if ( ! empty( $time_zone ) ) {
			$output .= ' ' . $time_zone;
		}

	}

	return $output;

}

function afa_events_process_times( $times = false ) {

	if ( ! $times || ! is_array( $times ) ) {
		return false;
	}

	$output = false;
	$meridiems = array();
	$tm = array();

	foreach ( $times as $key => $time ) {
		$result = afa_events_process_military_time( $time );
		$times[$key] = $result[0];
		$meridiems[$key] = $result[1];
		$tm[$key] = implode( ' ', $result );
	}

	$check_meridiem = count( array_unique( $meridiems ) );

	if ( $check_meridiem > 1 ) {
		$output = implode( ' – ', $tm );
	} else {
		$output = implode( ' – ', $times ) . ' ' . $meridiems[$key];
	}

	return $output;

}

function afa_events_process_military_time( $time = false, $return_array = true ) {

	if ( ! $time ) { return false; }

	$meridiem = 'am';

	$time_array = explode( ':', $time );

	$time_array[0] = ltrim( $time_array[0], '0' );

	if ( $time_array[0] > 12 ) {
		$time_array[0] = $time_array[0] - 12;
		$meridiem = 'pm';
	} elseif ( $time_array[0] == 12 ) {
		$meridiem = 'pm';
	}

	$time = implode( ':', $time_array );

	if ( $return_array ) {
		$output = array( $time, $meridiem );
	} else {
		$output = $time . ' ' . $meridiem;
	}

	return $output;

}

function afa_events_sidebar( $event_id = false, $banner = false  ) {

	if ( ! $event_id ) { return; }

	$event_style = ( $banner ) ? 'event-data-banner' : 'event-data-sidebar';

	$fallback_image = get_fallback_image('events');

	$event_data = get_fields( $event_id );

	//echo "<pre>" . print_r( $event_data, 1 ) . "</pre>";

	$event_title = strtoupper( get_the_title( $event_id ) );

	$event_date_raw = afa_events_process_date( $event_data['times'] );
	$event_date_array = explode( ' at ', $event_date_raw );
	$event_date = array_shift( $event_date_array );
	$event_time = implode( '', $event_date_array );
	$event_datestring = ( isset( $event_date_array['start_date'] ) ) ? $event_date_array['start_date'] : '';

	$now = date( 'Y-m-d' );
	$past_event = false;
	if ( isset( $event_data['times']['start_date'] ) && $event_data['times']['start_date'] < $now ) {
		if ( isset( $event_data['times']['end_date'] ) ) {
			if ( $event_data['times']['end_date'] < $now ) {
				$past_event = true;
			}
		} else {
			$past_event = true;
		}
	}

	$online_default = ( $past_event ) ? '' : '<b>ONLINE:</b> Register for online event details.';
	$live_default = ( $past_event ) ? '' : '<b>LOCATION:</b> To Be Announced';

	$live_info = '';
	$virtual_info = '';
	switch ( $event_data['virtual'] ) {
		case 'virtual':
			$virtual_info = ( ! empty( $event_data['location']['virtual'] ) ) ? "<b>ONLINE:</b> <a href='{$event_data['location']['virtual']}'>{$event_data['location']['virtual']}</a>" : $online_default;
			break;
		case 'live':
			$live_info = ( ! empty( $event_data['location']['live'] ) ) ? '<b>LOCATION:</b> ' . $event_data['location']['live'] : $live_default;
			break;
		case 'hybrid':
			$virtual_info = ( ! empty( $event_data['location']['virtual'] ) ) ? "<b>ONLINE:</b> <a href='{$event_data['location']['virtual']}'>{$event_data['location']['virtual']}</a>" : $online_default;
			$live_info = ( ! empty( $event_data['location']['live'] ) ) ? '<b>LOCATION:</b> ' . $event_data['location']['live'] : $live_default;
			break;
		default:
			break;
	}

	if ( ! $past_event ) {
		$reg = $event_data['registration'];
		if ( isset( $reg['live'] ) && ! empty( $reg['live'] ) ) {
			$live_info .= "<div class='text-center'><a href='{$reg['live']}'><button class='btn'>Register to Attend In-Person</button></a></div>";
		}
		if ( isset( $reg['virtual'] ) && ! empty( $reg['virtual'] ) ) {
			$virtual_info .= "<div class='text-center'><a href='{$reg['virtual']}'><button class='btn'>Register to Attend Virtually</button></a></div>";
		}
	}


	$event_image = ( isset( $event_data['event_thumbnail'] ) ) ? $event_data['event_thumbnail'] : $fallback_image;

	echo "<div class='{$event_style}'>";
	?>



	<?php
	if ( $banner ) { // banner

		echo "<div class='wp-block-media-text is-stacked-on-mobile is-image-fill'>";
		echo "<figure class='wp-block-media-text__media' style='background-image:url({$event_image});background-position:50% 50%'>";
		echo "<img fetchpriority='high' decoding='async' src='{$event_image}' alt='' class='size-full'>";
		echo "</figure>";
		echo "<div class='wp-block-media-text__content'>";

		if ( $past_event ) {
			echo "<p><b class='red'>This event has already taken place.</b></p>";
		}
		echo "<p><b>DATE:</b> {$event_date}</p>";
		if ( $event_time ) {
			echo "<p><b>TIME:</b> {$event_time}</p>";
		}
		if ( $live_info ) {
			echo "<p>{$live_info}</p>";
		}
		if ( $virtual_info ) {
			echo "<p>{$virtual_info}</p>";
		}

		echo "</div>";
		echo "</div>";

	} else { // sidebar
		echo "<p><img src='{$event_image}' title='' style=''></p>";
		echo "<p><b>{$event_title}</b></p>";
		if ( $past_event ) {
			echo "<p><b class='red'>This event has already taken place.</b></p>";
		}
		echo "<p><b>DATE:</b> {$event_date}</p>";
		if ( $event_time ) {
			echo "<p><b>TIME:</b> {$event_time}</p>";
		}
		if ( $live_info ) {
			echo "<p>{$live_info}</p>";
		}
		if ( $virtual_info ) {
			echo "<p>{$virtual_info}</p>";
		}
	}

	echo "</div>";

}

function afa_events_corporate_sponsors( $sponsor_level ) {

	if ( ! $sponsor_level || ! is_numeric( $sponsor_level ) ) { return; }

	$output = '';

	$query_args = array(
		'post_type'      => 'company',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'nopaging'       => true,
		'meta_key'       => 'corporate_sponsor',
		'meta_value'     => $sponsor_level,
		'meta_compare'   => '=',
		'orderby'        => 'post_title',
		'order'          => 'ASC',
	);

	$sponsors = new WP_Query( $query_args );

	if ( $sponsors->have_posts() ) {
		$latter_letter = '';
		$all_letters = array();
		while ( $sponsors->have_posts() ) {
			$sponsors->the_post();
			$sponsor_id = get_the_ID();

			$edit_url = get_edit_post_link( $sponsor_id );
			$edit_company = ( $edit_url ) ? "&nbsp;<a href='{$edit_url}' target='blank'><span class='dashicons dashicons-edit'></span></a>" : '';

			$company = get_the_title();
			$blurb = get_the_content();
			$company_url = get_post_meta( $sponsor_id, 'company_url', true );
			$image_url = get_the_post_thumbnail_url();

			$this_first_letter = strtoupper( mb_substr( $company, 0, 1 ) );
			if ( $this_first_letter != $latter_letter ) {
				$all_letters[] = $this_first_letter;
				$output .= "<h2 class='wp-block-heading has-text-align-center' id='{$this_first_letter}'>-{$this_first_letter}-</h2>";
			}
			$latter_letter = $this_first_letter;

			if ( $image_url ) {
				$image_type = explode( '.', $image_url );
				$image_type = end( $image_type );
				$svg_shadow = ( $image_type == 'svg' ) ? "filter: drop-shadow(1px 1px 2px rgb(0 0 0 / 0.5));" : "";
				$output .= "<img class='afa-exhibitor-logo' src='{$image_url}' title='{$company} Logo' style='margin-bottom: 10px; max-width: 200px; max-height: 200px; {$svg_shadow}'>";
			}
			if ( $company_url ) {
				$company = "<a href='{$company_url}'>{$company}</a>";
			}
			$output .= "<div class='afa-exhibitor' style='margin-top: 0;'><h4>{$company}{$edit_company}</h4><p>{$blurb}</p></div>";
		}
	}

	if ( ! empty( $all_letters ) ) {
		$page_nav = "<p class='has-text-align-center'><strong>Navigation:</strong></p>";
		$page_nav .= "<p class='has-text-align-center'>";
		$i = 0;
		$number_of_letters = count( $all_letters );
		foreach( $all_letters as $a ) {
			$i++;
			$page_nav .= "<a href='#{$a}'>{$a}</a>";
			if ( $i != $number_of_letters ) {
				if ( $i == ceil( $number_of_letters/2 ) ) {
					$page_nav .= "<br/>";
				} else {
					$page_nav .= "&nbsp;|&nbsp;";
				}
			}
		}
		$page_nav .= "<br>Press back arrow to return to top.</p>";
		echo $page_nav;
	}

	echo $output;

}

function afa_corp_sponsors_doolittle() { afa_events_corporate_sponsors( 1 ); }
function afa_corp_sponsors_rickenbacker() { afa_events_corporate_sponsors( 2 ); }
function afa_corp_sponsors_arnold() { afa_events_corporate_sponsors( 3 ); }

add_shortcode( 'afa_corp_sponsors_doolittle', 'afa_corp_sponsors_doolittle' );
add_shortcode( 'afa_corp_sponsors_rickenbacker', 'afa_corp_sponsors_rickenbacker' );
add_shortcode( 'afa_corp_sponsors_arnold', 'afa_corp_sponsors_arnold' );

add_action( 'wp_ajax_nopriv_get_event_for_agenda', 'afa_events_get_event_for_agenda' );
add_action( 'wp_ajax_get_event_for_agenda', 'afa_events_get_event_for_agenda' );

function afa_events_get_event_for_agenda() {
	$post_id = $_POST['id'];
	$fields = get_fields( $post_id );
	$fields['prettystart'] = '';
	$fields['prettyend'] = '';
	if ( isset( $fields['times']['start_date'] ) && $fields['times']['start_date'] ) {
		$fields['prettystart'] = date( 'F j, Y', strtotime( $fields['times']['start_date'] ) );
	}
	if ( isset( $fields['times']['end_date'] ) && $fields['times']['end_date'] ) {
		$fields['prettyend'] = date( 'F j, Y', strtotime( $fields['times']['end_date'] ) );
	}

	wp_send_json_success( $fields );
}

if ( ! function_exists( 'get_fallback_image' ) ) {
	function get_fallback_image( $fallback_type = false ) {
		if ( ! function_exists( 'get_field' ) ) { return false; }
		$fallback_images = get_field( 'fallback_images', 'options' );
		if ( ! is_array( $fallback_images ) ) { return false; }
		if ( $fallback_type == 'news' || $fallback_type == 'events' ) {
			return $fallback_images[$fallback_type];
		} elseif ( $fallback_type ) {
			return $fallback_images;
		}
	}
}
