<?php

/**
 * The class that defines custom post types.
 *
 * @package		AFA_Events
 * @subpackage	AFA_Events/includes
 *
 * @link		http://unleashed-technologies.com
 * @since		1.0.0
 * @author		Michael Wendell <mwendell@unleashed-technologies.com>
 */
class AFA_Events_Post_Types {

	/**
	 * Register the Custom Post Type for events.
	 *
	 * @since    1.0.0
	 */
	public static function register_afa_events_post_types() {

		// ---------------------------------------------------------------------------------------
		// EVENTS
		$event_labels = array(
			'name'                  => _x( 'Events', 'afa-events' ),
			'singular_name'         => _x( 'Event', 'afa-events' ),
			'all_items'             => _x( 'All Events', 'afa-events' ),
		);
		$event_rewrite = array(
			'slug'                  => 'events',
			'with_front'            => 'true',
		);
		$event_args = array(
			'label'                 => __( 'Event', 'afa-events' ),
			'description'           => __( 'AFA events', 'afa-events' ),
			'labels'                => $event_labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'taxonomies'            => array(),
			'hierarchical'          => false,
			'public'                => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-tickets-alt',
			'has_archive'           => 'events',
			'rewrite'               => $event_rewrite,
			'capability_type'       => 'post',
			'show_in_rest'			=> false,
		);
		register_post_type( 'event', $event_args );

		// ---------------------------------------------------------------------------------------
		// AGENDA ITEMS
		$agenda_labels = array(
			'name'                  => _x( 'Agenda Items', 'afa-events' ),
			'singular_name'         => _x( 'Agenda Item', 'afa-events' ),
			'name_admin_bar'        => __( 'Agenda', 'afa-events' ),
		);
		$agenda_rewrite = array(
			'slug'                  => 'agenda',
		);
		$agenda_args = array(
			'label'                 => __( 'Agenda Items', 'afa-events' ),
			'description'           => __( 'AFA event agenda items', 'afa-events' ),
			'labels'                => $agenda_labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'taxonomies'            => array(),
			'hierarchical'          => false,
			'public'                => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-clock',
			'has_archive'           => false,
			'rewrite'               => $agenda_rewrite,
			'capability_type'       => 'post',
			'show_in_menu'          => 'edit.php?post_type=event',
			'show_in_rest'			=> false,
		);
		register_post_type( 'agenda', $agenda_args );

		// ---------------------------------------------------------------------------------------
		// EVENT SPEAKERS
		$speaker_labels = array(
			'name'                  => _x( 'Event Speakers', 'afa-events' ),
			'singular_name'         => _x( 'Event Speaker', 'afa-events' ),
			'name_admin_bar'        => __( 'Speakers', 'afa-events' ),
		);
		$speaker_rewrite = array(
			'slug'                  => 'speaker',
		);
		$speaker_args = array(
			'label'                 => __( 'Event Speakers', 'afa-events' ),
			'description'           => __( 'AFA event speakers', 'afa-events' ),
			'labels'                => $speaker_labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'taxonomies'            => array(),
			'hierarchical'          => false,
			'public'                => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-media-document',
			'has_archive'           => false,
			'rewrite'               => $speaker_rewrite,
			'capability_type'       => 'post',
			'show_in_menu'          => 'edit.php?post_type=event',
			'show_in_rest'			=> false,
		);
		register_post_type( 'speaker', $speaker_args );

		// ---------------------------------------------------------------------------------------
		// COMPANIES
		$company_labels = array(
			'name'                  => _x( 'Sponsor Companies', 'afa-events' ),
			'singular_name'         => _x( 'Sponsor Company', 'afa-events' ),
			'menu_name'             => __( 'Companies', 'afa-events' ),
			'name_admin_bar'        => __( 'Companies', 'afa-events' ),
		);
		$company_rewrite = array(
			'slug'                  => 'company',
		);
		$company_args = array(
			'label'                 => __( 'Sponsor Company', 'afa-events' ),
			'description'           => __( "AFA event sponsor companies", 'afa-events' ),
			'labels'                => $company_labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'taxonomies'            => array(),
			'hierarchical'          => false,
			'public'                => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-block-default',
			'has_archive'           => false,
			'rewrite'               => $company_rewrite,
			'capability_type'       => 'post',
			'show_in_rest'			=> false,
		);
		register_post_type( 'company', $company_args );

	}

	public static function clean_up_events_menu() {
		remove_submenu_page( 'edit.php?post_type=event', 'post-new.php?post_type=event' );
	}

	// ========================================================================
	// EVENTS ADMIN PAGE COLUMNS, SORTING, AND FILTERS
	// ========================================================================

	public static function add_event_columns( $columns ) {
		unset ( $columns['date'] );
		$columns['title'] = "Event Title";
		$columns['event_date'] = "Event Date & Time";
		return $columns;
	}

	public static function sortable_event_columns( $columns ) {
		$columns['event_date'] = "event_date";
		return $columns;
	}

	public static function populate_event_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'event_date' :
				$t = get_field( 'times' );
				//echo '<pre>'.print_r( $t, 1 ).'</pre>';
				echo afa_events_process_date( $t );
				break;
		}
	}

	public static function sort_event_columns( $query ) {

		if ( ! is_admin() ) { return; }

		$orderby = $query->get( 'orderby' );

		if ( 'event_time' == $orderby ) {

			$meta_query = array(
				'relation' => 'OR',
				array(
					'key' => 'times_start_date',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key' => 'times_start_date',
				),
			);

			$query->set( 'meta_query', $meta_query );
			$query->set( 'orderby', 'meta_value' );
		}
	}

	public static function event_columns_default_sort_order( $query ) {

		if ( ! is_admin() ) { return; }

		if ( $query->get( 'post_type' ) == 'event' ) {

			$meta_query = array(
				'relation' => 'OR',
				array(
					'key' => 'times_start_date',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key' => 'times_start_date',
				),
			);

			$query->set( 'meta_query', $meta_query );
			$query->set( 'orderby', 'meta_value' );

		}
	}

	public static function event_view_show_filters( $views ) {

		$views['future'] = '<a href="edit.php?timeslice=future&post_type=event">Current and Future Events</a>';
		$views['past'] = '<a href="edit.php?timeslice=past&post_type=event">Past Events</a>';
		// $views['dateless'] = '<a href="edit.php?timeslice=missing&post_type=event">Dateless Events</a>';
		return $views;

	}

	public static function event_view_filter( $where ) {

		if ( ! is_admin() ) { return $where; }

		global $typenow;

		if ( $typenow != 'event' ) { return $where; }

		if ( isset( $_GET['timeslice'] ) && ! empty( $_GET['timeslice'] ) ) {

			global $wpdb;

			$yesterday = date( 'Ymd', strtotime( 'yesterday' ) );
			$tomorrow = date( 'Ymd', strtotime( 'tomorrow' ) );

			switch( $_GET['timeslice'] ) {
				case 'future' :
					$where .= " AND ID IN ( SELECT post_id FROM {$wpdb->postmeta} WHERE ((meta_key='times_start_date') AND (meta_value >= '{$yesterday}')) )";
					break;
				case 'past' ;
					$where .= " AND ID IN ( SELECT post_id FROM {$wpdb->postmeta} WHERE ((meta_key='times_start_date') AND (meta_value <= '{$tomorrow}')) )";
					break;
				case 'dateless' ; // this doesn't seem to work, disabling link to this filter above
					$where .= " AND ID NOT IN ( SELECT post_id FROM $wpdb->postmeta WHERE ((meta_key='times_start_date') AND NOT (meta_value = '')) )";
					break;
			}

		}

		return $where;

	}

	// ========================================================================
	// AGENDA ITEMS ADMIN PAGE COLUMNS, SORTING, AND FILTERS
	// ========================================================================

	public static function add_agenda_columns( $columns ) {
		unset ( $columns['date'] );
		$columns['event'] = "Parent Event";
		$columns['title'] = "Agenda Item Title";
		$columns['agenda_date'] = "Agenda Item Date & Time";
		return $columns;
	}

	public static function sortable_agenda_columns( $columns ) {
		$columns['event'] = "event";
		$columns['agenda_date'] = "agenda_date";
		return $columns;
	}

	public static function populate_agenda_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'event' :
				$parent_title = get_the_title( wp_get_post_parent_id( $post_id ) );
				if ( empty( $parent_title ) || is_wp_error( $parent_title ) ) {
					$parent_title = '';
				}
				echo $parent_title;
				break;
			case 'agenda_date' :
				$t = get_field( 'times' );
				//echo '<pre>'.print_r( $t, 1 ).'</pre>';
				echo afa_events_process_date( $t );
				break;
		}
	}

	public static function sort_agenda_columns( $query ) {

		if ( ! is_admin() ) { return; }

		$orderby = $query->get( 'orderby' );

		if ( 'agenda_date' == $orderby ) {

			$meta_query = array(
				'relation' => 'OR',
				array(
					'key' => 'times_start_date',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key' => 'times_start_date',
				),
			);

			$query->set( 'meta_query', $meta_query );
			$query->set( 'orderby', 'meta_value' );
		}
	}

	public static function agenda_columns_default_sort_order( $query ) {

		if ( ! is_admin() ) { return; }

		if ( $query->get( 'post_type' ) == 'agenda' ) {

			$meta_query = array(
				'relation' => 'OR',
				array(
					'key' => 'times_start_date',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key' => 'times_start_date',
				),
			);

			$query->set( 'meta_query', $meta_query );
			$query->set( 'orderby', 'meta_value' );

		}
	}

	public static function agenda_view_show_filters( $views ) {

		$views['future'] = '<a href="edit.php?timeslice=future&post_type=event">Current and Future Agenda Items</a>';
		$views['past'] = '<a href="edit.php?timeslice=past&post_type=event">Past Agenda Items</a>';
		// $views['dateless'] = '<a href="edit.php?timeslice=missing&post_type=event">Dateless Agenda Items</a>';
		return $views;

	}

	public static function agenda_view_filter( $where ) {

		if ( ! is_admin() ) { return $where; }

		global $typenow;

		if ( $typenow != 'agenda' ) { return $where; }

		if ( isset( $_GET['timeslice'] ) && ! empty( $_GET['timeslice'] ) ) {

			global $wpdb;

			$yesterday = date( 'Ymd', strtotime( 'yesterday' ) );
			$tomorrow = date( 'Ymd', strtotime( 'tomorrow' ) );

			switch( $_GET['timeslice'] ) {
				case 'future' :
					$where .= " AND ID IN ( SELECT post_id FROM {$wpdb->postmeta} WHERE ((meta_key='times_start_date') AND (meta_value >= '{$yesterday}')) )";
					break;
				case 'past' ;
					$where .= " AND ID IN ( SELECT post_id FROM {$wpdb->postmeta} WHERE ((meta_key='times_start_date') AND (meta_value <= '{$tomorrow}')) )";
					break;
				case 'dateless' ; // this doesn't seem to work, disabling link to this filter above
					$where .= " AND ID NOT IN ( SELECT post_id FROM $wpdb->postmeta WHERE ((meta_key='times_start_date') AND NOT (meta_value = '')) )";
					break;
			}

		}

		return $where;

	}

	// ========================================================================
	// AGENDA ITEMS - UPDATE PARENT ON SAVE
	// ========================================================================

	function set_agenda_item_parent( $post_id, $post, $update ) {

		if ( $post->post_status == 'auto-draft' ) { return; }

		$parent_id = get_post_meta( $post_id, 'event', true );

		if ( $parent_id > 0 ) {
			global $wpdb;
			$sql = "UPDATE wp_posts SET post_parent = %d WHERE (ID = %d)";
			$sql = $wpdb->prepare( $sql, $parent_id, $post_id );
			$wpdb->query( $sql );
		}

	}

	// ========================================================================
	// CUSTOM QUERY FOR ARCHIVE PAGE
	// ========================================================================

	function afa_events_custom_archive( $query ) {
		if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'event' ) ) {

			if ( ( isset( $query->query['post_type'] ) ) && ( $query->query['post_type'] == 'event' )) {

				$now = date( 'Y-m-d' );

				// THIS IS THE DEFAULT PAGE VIEW
				$meta_query = array(
					'relation'    => 'OR',
					array(
						'key'     => 'times_start_date',
						'value'   => date( $now ),
						'type'    => 'date',
						'compare' => '>',
					),
					array(
						'key'     => 'times_end_date',
						'value'   => date( $now ),
						'type'    => 'date',
						'compare' => '>',
					)
				);
				$order = 'ASC';

				if ( isset( $_GET['event_past'] ) && ! empty( $_GET['event_past'] ) ) {

					$meta_query = array(
						'relation'    => 'OR',
						array(
							'key'     => 'times_start_date',
							'value'   => date( $now ),
							'type'    => 'date',
							'compare' => '<=',
						),
					);
					$order = 'DESC';

				}

				$query->set( 'meta_query', $meta_query );
				$query->set( 'orderby', 'meta_value' );
				$query->set( 'order', $order );

				if ( isset( $_GET['event_search'] ) && ! empty( $_GET['event_search'] ) ) {
					$query->set( 's', $_GET['event_search'] );
				}

				if ( isset( $_GET['event_cat'] ) && ! empty( $_GET['event_cat'] ) ) {
					$tax_query = array(
						array(
							'taxonomy' => 'event_category',
							'field'    => 'term_id',
							'terms'    => array( $_GET['event_cat'] )
						),
					);
					$query->set( 'tax_query', $tax_query );

				}

			}

		}

		return $query;

	}

	function afa_events_search_query_vars( $query_vars ) {
		$query_vars[] = 'event_search';
		$query_vars[] = 'evvent_cat';
		$query_vars[] = 'event_past';

		return $query_vars;
	}

	// ========================================================================
	// COMPANY PAGE COLUMNS, SORTING, AND FILTERS
	// ========================================================================

	public static function add_company_columns( $columns ) {
		unset ( $columns['date'] );
		$columns['title'] = "Company";
		$columns['corporate_sponsor'] = "Current Sponsor Level";
		return $columns;
	}

	public static function sortable_company_columns( $columns ) {
		$columns['title'] = "title";
		$columns['corporate_sponsor'] = "corporate_sponsor";
		return $columns;
	}

	public static function populate_company_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'corporate_sponsor' :
				$sp = get_field( 'corporate_sponsor' );
				if ( $sp > 0 ) {
					switch ( $sp ) {
						case 1 : echo 'Doolittle'; break;
						case 2 : echo 'Rickenbacker'; break;
						case 3 : echo "Arnold"; break;
					}
				} else {
					echo "-";
				};
				break;
		}
	}

	public static function sort_company_columns( $query ) {

		if ( ! is_admin() ) { return; }

		$orderby = $query->get( 'orderby' );

		if ( 'corporate_sponsor' == $orderby ) {

			$meta_query = array(
				array(
					'key'     => 'corporate_sponsor',
					'compare' => 'EXISTS',
				),
			);

			$query->set( 'meta_query', $meta_query );
			$query->set( 'orderby', 'meta_value' );
		}
	}

	public static function company_columns_default_sort_order( $query ) {

		if ( ! is_admin() ) { return; }

		if ( $query->get( 'post_type' ) == 'company' ) {

			$query->set( 'order', 'ASC' );
			$query->set( 'orderby', 'post_title' );

		}
	}

	public static function company_view_show_filters( $views ) {

		$views['sponsor_0'] = '<a href="edit.php?cs=0&post_type=company">Non-Sponsors</a>';
		$views['sponsor_1'] = '<a href="edit.php?cs=1&post_type=company">Doolittle Sponsors</a>';
		$views['sponsor_2'] = '<a href="edit.php?cs=2&post_type=company">Rickenbacker Sponsors</a>';
		$views['sponsor_3'] = '<a href="edit.php?cs=3&post_type=company">Arnold Sponsors</a>';
		// $views['dateless'] = '<a href="edit.php?timeslice=missing&post_type=event">Dateless Events</a>';
		return $views;

	}

	public static function company_view_filter( $where ) {

		if ( ! is_admin() ) { return $where; }

		global $typenow;

		if ( $typenow != 'company' ) { return $where; }

		if ( isset( $_GET['cs'] ) ) {

			global $wpdb;

			$where .= " AND ID IN ( SELECT post_id FROM {$wpdb->postmeta} WHERE ((meta_key='corporate_sponsor') AND (meta_value = {$_GET['cs']})) )";

		}

		return $where;

	}


}
