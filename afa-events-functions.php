<?php

// ========================================================================
// THE FOLLOWING FUNCTIONS ARE GLOBALLY AVAILABLE OUTSIDE OF THE PLUGIN
// ========================================================================

function afa_events_homepage() {

	$fallback_image = get_fallback_image('events');

	$is_afa = str_contains( get_site_url(), 'https://www.afa.org' );

	$events = afa_events_get_events();

	if ( empty( $events ) ) {
		echo "<!-- NO EVENTS -->";
		return;
	}

	/*
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
	*/

	$buttons = array(
	);

	if ( $is_afa ) {
		$buttons = "<a class='btn primary' href='/events/'>View All Events</a>";
	} else {
		$buttons = "<div><a class='btn primary' href='/events/'>View Local Events</a>&nbsp;&nbsp;<a class='btn primary' href='https://www.afa.org/events/'>View National Events</a></div>";

	}

	echo "<div class='views-entity-embed'>";
	echo "<div class='view-events-preview py-5' data-yesterday='{$yesterday}'>";
	echo "<div class='container'>";
	echo "<div class='view-header'><h2>Upcoming Events</h2><div>{$buttons}</div></div>";
	echo "<div class='view-content'>";

	$i = 0;
	foreach ( $events as $event ) {

		$i++;
		if ( $i > 4 ) {
			break;
		}

		$event_id = $event->ID ?: false;
		$event_title = $event['post_title'];
		$start_date = ( $event['meta_input']['event_start_date'] ) ? date( 'Y-m-d', strtotime( $event['meta_input']['event_start_date'] ) ) : '';
		$end_date = ( $event['meta_input']['event_end_date'] ) ? date( 'Y-m-d', strtotime( $event['meta_input']['event_end_date'] ) ) : '';

		$event_date_array = array(
			'start_date'  => $start_date,
			'end_date'    => $end_date,
			'start_time'  => $event['meta_input']['event_start_time'],
			'end_time'    => $event['meta_input']['event_end_time'],
			'time_zone'   => $event['meta_input']['event_time_zone'],
		);

		$event_date = afa_events_process_date( $event_date_array, true );
		$event_datestring = ( isset( $event_date_array['start_date'] ) ) ? $event_date_array['start_date'] : '';
		$event_image = $event['meta_input']['event_thumbnail_image'];
		if ( is_numeric( $event_image ) ) { $event_image = wp_get_attachment_image_url( $event_image, 'large' ); }
		if ( empty( $event_image ) ) { $event_image = $fallback_image; }
		$event_link = $event['post_url'];

		echo "<div class='views-row'>";
		echo "<article role='article' class='node event latest'>";

		// IMAGE
		echo "<div class='img'><img src='{$event_image}' title='' style=''></div>";

		// TITLE AND DATE
		echo "<div class='content' data-id='{$event_id}'><div>";
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
	if ( isset( $event_data['virtual'] ) ) {
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
	}

	if ( ! $past_event ) {
		$reg = $event_data['registration'] ?: false;
		if ( isset( $reg['live'] ) && ! empty( $reg['live'] ) ) {
			$live_info .= "<div class='text-center'><a href='{$reg['live']}'><button class='btn'>Register to Attend In-Person</button></a></div>";
		}
		if ( isset( $reg['virtual'] ) && ! empty( $reg['virtual'] ) ) {
			$virtual_info .= "<div class='text-center'><a href='{$reg['virtual']}'><button class='btn'>Register to Attend Virtually</button></a></div>";
		}
	}

	$event_image = ( isset( $event_data['event_thumbnail'] ) && ! empty( $event_data['event_thumbnail'] ) ) ? $event_data['event_thumbnail'] : $fallback_image;

	echo "<div class='{$event_style}'>";

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

function afa_events_national_events() {

	$rss_events = get_option( 'afa_national_events_feed_most_recent', array() );

	if ( ! isset( $events['date'] ) || $events['date'] < strtotime( 'yesterday' ) ) {

		$rss_events = array(
			'date'    => time(),
			'events'  => array(),
		);

		$rss = fetch_feed( 'https://www.afa.org/events/feed/' );

		if ( is_wp_error( $rss ) || empty( $rss ) ) {
			$posts = false;
		} else {
			$posts = $rss->get_items( 0, 4 );
		}

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$image_url = false;
				$thumbnail_data = false;
				$post_url = $post->get_permalink();
				$title = $post->get_title();
				$excerpt = wp_trim_words( $post->get_description(), 20 );
				$date = $post->get_date( 'F j, Y' );
				if ( $post->get_item_tags( 'http://search.yahoo.com/mrss/','thumbnail' ) ) {
					$thumbnail_item = $post->get_item_tags( 'http://search.yahoo.com/mrss/','thumbnail' );
					$thumbnail_data = reset( $thumbnail_item[0]['attribs'] );
				}
				$image = $thumbnail_data['url'];
				//$image = array( 'event_thumbnail_image' => $image );
				if ( $post->get_item_tags( 'https://www.afa.org/event-namespace/','starttime' ) ) {
					$times = array(
						'event_start_date'      => 'startdate',
						'event_start_time'      => 'starttime',
						'event_end_date'        => 'enddate',
						'event_end_time'        => 'endtime',
						'event_time_zone'       => 'timezone',
						'event_thumbnail_image' => 'thumbnail'
					);
					foreach ( $times as $key => $name ) {
						$this_data = $post->get_item_tags( 'https://www.afa.org/event-namespace/', $name );
						$this_value = $this_data[0]['attribs']['']['value'];
						$times[$key] = $this_value;
					}
					$date = date( 'F j, Y' . ' 12:00:00', strtotime( $times['event_start_date'] ) );
					$date = strtotime( $date );
				}

				if ( empty( $times['event_thumbnail_image'] ) ) {
					$times['event_thumbnail_image'] = $image;
				}

				$this_event = array(
					'post_date'     => $date,
					'post_title'    => $title,
					'post_url'      => $post_url,
					'meta_input'    => $times,
				);

				$rss_events['events'][] = $this_event;

			}
		}

		update_option( 'afa_national_events_feed_most_recent', $rss_events );

	}

	return $rss_events['events'];

}

function afa_events_get_events() {

	global $wpdb;

	$yesterday = intval( date( 'Ymd' ) ) - 1;

	$sql = "SELECT
			p.ID, p.post_title, p.post_content, p.post_excerpt, p.post_date,
			msd.meta_value AS 'times_start_date',
			med.meta_value AS 'times_end_date',
			mst.meta_value AS 'times_start_time',
			met.meta_value AS 'times_end_time',
			mtz.meta_value AS 'times_time_zone',
			mth.meta_value AS 'event_thumbnail'
			mol.meta_value AS 'offsite_link'
		FROM {$wpdb->posts} p
			JOIN {$wpdb->postmeta} AS msd on (p.ID = msd.post_id) AND (msd.meta_key = 'times_start_date')
			LEFT JOIN {$wpdb->postmeta} AS med on (p.ID = med.post_id) AND (med.meta_key = 'times_end_date')
			LEFT JOIN {$wpdb->postmeta} AS mst on (p.ID = mst.post_id) AND (mst.meta_key = 'times_start_time')
			LEFT JOIN {$wpdb->postmeta} AS met on (p.ID = met.post_id) AND (met.meta_key = 'times_end_time')
			LEFT JOIN {$wpdb->postmeta} AS mtz on (p.ID = mtz.post_id) AND (mtz.meta_key = 'times_time_zone')
			LEFT JOIN {$wpdb->postmeta} AS mth on (p.ID = mth.post_id) AND (mth.meta_key = 'event_thumbnail')
			LEFT JOIN {$wpdb->postmeta} AS mol on (p.ID = mth.post_id) AND (mol.meta_key = 'offsite_link')
		WHERE
			(p.post_type = 'event') AND
			(p.post_status = 'publish') AND
			( msd.meta_value > {$yesterday} OR med.meta_value > {$yesterday} )
		ORDER BY msd.meta_value ASC
		LIMIT 4;";
	$local_events = $wpdb->get_results( $sql );

	if ( is_wp_error( $local_events ) ) { $local_events = array(); }

	$events = array();

	if ( ! empty( $local_events ) ) {
		foreach( $local_events as $event ) {

			$post_url = get_permalink( $event->ID );
			if ( $event->offsite_link ) { $post_url = $event->offsite_link; }
			$title = $event->post_title;
			$excerpt = ( ! empty( $event->post_excerpt ) ) ? $event->post_excerpt : $event->post_content;
			$excerpt = wp_trim_words( $excerpt, 20 );
			$date = date( 'F j, Y', strtotime( $event->post_date ) );

			$times = array(
				'event_start_date' => $event->times_start_date,
				'event_start_time' => $event->times_start_time,
				'event_end_date'   => $event->times_end_date,
				'event_end_time'   => $event->times_end_time,
				'event_time_zone'  => $event->times_time_zone,
			);
			$date = date( 'F j, Y' . ' 00:00:00', strtotime( $times['event_start_date'] ) );
			$date = strtotime( $date );

			$thumbnail = $event->event_thumbnail ?? get_the_post_thumbnail_url( $event->ID );
			$image = array( 'event_thumbnail_image' => $thumbnail );

			$this_event = array(
				'post_date'     => $date,
				'post_title'    => $title,
				'post_url'      => $post_url,
				'meta_input'    => array_merge( $times, $image ),
			);

			$events[] = $this_event;

		}
	}

	$is_afa = str_contains( get_site_url(), 'https://www.afa.org' );

	if ( ! $is_afa ) {

		$rss_events = afa_events_national_events();

		$events = array_merge( $events, $rss_events );

	}

	$events = wp_list_sort( $events, 'post_date', 'ASC' );

	return $events;

}

function afa_events_has_agenda( $event_id = false ) {

	if ( ! $event_id || ! is_numeric( $event_id ) ) { return false; }

	$children = get_children( array( 'post_parent' => $event_id ) );

	if ( is_array( $children ) && ! empty( $children ) ) {
		return true;
	} else {
		return false;
	}

}

function afa_events_show_agenda( $event_id = false ) {

	if ( ! $event_id || ! is_numeric( $event_id ) ) { return false; }

	$args = array(
		'post_parent'     => $event_id,
		'post_type'       => 'agenda',
		'post_status'     => 'publish',
		'posts_per_page'  => -1,
		'meta_query'      => array(
			'relation'    => 'AND',
			'event_start_date_clause' => array(
				'key'     => 'times_start_date',
				'compare' => 'EXISTS',
			),
			'event_all_day_clause' => array(
				'key'     => 'all_day',
				'compare' => 'EXISTS',
			),
			'event_start_time_clause' => array(
				'key'     => 'times_start_time',
				'compare' => 'EXISTS',
			),
			'event_location_clause' => array(
				'key'     => 'location_live',
				'compare' => 'EXISTS',
			),
		),
		'orderby' => array(
			'event_start_date_clause' => 'ASC',
			'event_all_day_clause'    => 'DESC',
			'event_start_time_clause' => 'ASC',
			'event_location_clause'   => 'ASC',
		),
	);

	$agenda_items = new WP_Query( $args );

	if ( ! $agenda_items ) { return false; }

	$output = '';
	$day_buttons = "<div class='wp-block-buttons is-horizontal is-content-justification-center is-layout-flex wp-container-core-buttons-is-layout-1 wp-block-buttons-is-layout-flex'>";
	$show_day_buttons = 0;
	$show_date = 0;

	$editor = current_user_can( 'edit_posts' );

	$display_date = false;
	$previous_display_times = false;

	$i = 0;

	if ( $agenda_items->have_posts() ) {
		while ( $agenda_items->have_posts() ) {
			$agenda_items->the_post();
			$post_id = get_the_ID();
			$times = get_field( 'times' );

			if ( ! isset( $times['end_date'] ) || ! $times['end_date'] ) {
				$times['end_date'] = $times['start_date'];
			}

			$past_event = ( strtotime( $times['end_date'] ) < time() ) ? true : false;

			if ( ! $times['end_date'] == $times['start_date'] ) {
				if ( $display_date != $times['start_date'] ) {
					$i = 0;
					$display_date = $times['start_date'];
					$date_title = date( 'l, F j, Y', strtotime( $display_date ) );
					$id = explode( ',', $date_title );
					$id = array_shift( $id );
					$output .= "<!-- BUTTONS-GO-HERE --></div>";
					$output .= "<h2 class='wp-block-heading' id='{$id}'>{$date_title}</h2>";
					$day_buttons .= "<div class='wp-block-button'><a class='wp-block-button__link wp-element-button' href='#{$id}'>{$id}</a></div>";
					$show_day_buttons++;
				}
			}

			$i++;
			$note = get_field( 'note' );
			$location = get_field( 'location_live' );
			$title = get_the_title();
			$all_day = get_field( 'all_day' );
			$row_style = ( $i % 2 == 0 ) ? '' : "light-grey-background";
			if ( $all_day ) {
				$row_style = 'all_day';
				$i--;
			};

			$edit_link = ( $editor ) ? "<a href='/wp/wp-admin/post.php?post={$post_id}&action=edit' class='agenda_edit_link'><span class='dashicons dashicons-edit'></span></a>" : false;

			$display_times = afa_events_process_times( array( $times['start_time'], $times['end_time'] ) );
			$duplicate_style = ( $display_times == $previous_display_times ) ? 'duplicate' : '';

			$show_speakers = get_field( 'show_speakers' );

			// for mitchellaerospacepower.org
			if ( str_contains( $_SERVER['HTTP_HOST'], 'mitchell' ) || str_contains( $_SERVER['HTTP_HOST'], '5em2ouy' ) ) {
				$show_speakers = true;
			}

			$speakers = ( $show_speakers ) ? get_field( 'speakers' ) : false ;

			$output .= "<div class='agenda {$row_style}'>";
			$output .= "<div class='agenda_time {$duplicate_style}'><nobr class='{$duplicate_style}'>{$display_times}</nobr></div>";
			$output .= "<div class='agenda_title'><span class='agenda_title'>{$title}{$edit_link}</span>";
			if ( $note ) { $output .= "<div class='small agenda_note'>{$note}</div>"; }
			if ( $speakers ) {
				// $output .= "<pre>" . print_r( $speakers, 1 ) . "</pre>";
				$output .= "<div class='agenda_speakers'><ul>";
				foreach ( $speakers as $speaker ) {
					$speaker_id = ( isset( $speaker['speaker_id'] ) ) ? $speaker['speaker_id'] : $speaker['speaker'];
					$suffix = ( isset( $speaker['suffix'] ) ) ? $speaker['suffix'] : get_field( 'suffix', $speaker_id );
					$speaker_name = trim( $speaker['rank'] . ' ' . get_the_title( $speaker_id ) );
					if ( $suffix ) { $speaker_name .= ', ' . $suffix; }
					$output .= "<li>";
					$moderator = ( $speaker['moderator'] ) ? 'Moderator ' : '';
					$output .= "<span class='agenda_speaker_name'>{$speaker_name}</span>";
					if ( $speaker['position'] || $moderator ) {
						if ( $speaker['position'] && $moderator ) { $moderator .= ', '; }
						$output .= "<br/><span class='agenda_speaker_position'>{$moderator}{$speaker['position']}</span>";
					}
					$output .= "</li>";
				}
				$output .= "</ul></div>";
			}
			$output .= "</div>";
			if ( $past_event ) {

				$media = '';

				$video = get_field( 'video' );
				if ( $video ) {
					if ( ! str_contains( $video, 'youtu' ) ) {
						$video = "https://www.youtube.com/watch?v={$video}";
					}
					$media .= "<a href='{$video}' target='_blank' class='button agenda_button'>Watch</a>";
				}

				$audio = get_field( 'audio' );
				if ( $audio ) {
					if ( ! str_contains( $audio, 'podbean' ) ) {
						$audio = "https://www.podbean.com/pu/pbblog-{$audio}";
					}
					$media .= "<a href='{$audio}' target='_blank' class='button agenda_button'>Listen</a>";
				}

				$output .= "<div class='agenda_media'>{$media}</div>";
			} else {
				$output .= "<div class='agenda_location'>{$location}</div>";
			}
			$output .= "</div>";

			$previous_display_times = $display_times ?: $previous_display_times;

		}
	}

	if ( $show_day_buttons > 1 ) {
		$output = str_replace( "<!-- BUTTONS-GO-HERE -->", $day_buttons, $output );
	}

	echo $output;

}
