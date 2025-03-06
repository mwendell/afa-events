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
			'name'                     => _x( 'Events', 'afa-events' ),
			'singular_name'            => _x( 'Event', 'afa-events' ),
			'all_items'                => _x( 'All Events', 'afa-events' ),
			'add_new'                  => _x( 'Add New', 'afa-events' ),
			'add_new_item'             => _x( 'Add New Event', 'afa-events' ),
			'edit_item'                => _x( 'Edit Event', 'afa-events' ),
			'new_item'                 => _x( 'New Event', 'afa-events' ),
			'view_item'                => _x( 'View Event', 'afa-events' ),
			'view_items'               => _x( 'View Events', 'afa-events' ),
			'search_items'             => _x( 'Search Events', 'afa-events' ),
			'not_found'                => _x( 'No Events Found', 'afa-events' ),
			'not_found_in_trash'       => _x( 'No Events found in Trash', 'afa-events' ),
			'parent_item_colon'        => _x( 'Parent Event:', 'afa-events' ),
			'all_items'                => _x( 'All Events', 'afa-events' ),
			'archives'                 => _x( 'Event Archives', 'afa-events' ),
			'attributes'               => _x( 'Event Attributes', 'afa-events' ),
			'insert_into_item'         => _x( 'Insert into Event', 'afa-events' ),
			'uploaded_to_this_item'    => _x( 'Uploaded to this Event', 'afa-events' ),
			'featured_image'           => _x( 'Banner Image', 'afa-events' ),
			'set_featured_image'       => _x( 'Set Banner Image', 'afa-events' ),
			'remove_featured_image'    => _x( 'Remove Banner Image', 'afa-events' ),
			'use_featured_image'       => _x( 'Usa as Banner Image', 'afa-events' ),
			'menu_name'                => _x( 'Events', 'afa-events' ),
			'filter_items_list'        => _x( 'Filter Events List', 'afa-events' ),
			'filter_by_date'           => _x( 'Filter Events by Posting Date', 'afa-events' ),
			'items_list_navigation'    => _x( 'Events List Navigation', 'afa-events' ),
			'items_list'               => _x( 'Events List', 'afa-events' ),
			'item_published'           => _x( 'Event Published', 'afa-events' ),
			'item_published_privately' => _x( 'Event Published Privately', 'afa-events' ),
			'item_reverted_to_draft'   => _x( 'Event Reverted to Draft', 'afa-events' ),
			'item_trashed'             => _x( 'Event Trashed', 'afa-events' ),
			'item_scheduled'           => _x( 'Event Posting Scheduled', 'afa-events' ),
			'item_updated'             => _x( 'Event Updated', 'afa-events' ),
			'item_link'                => _x( 'Event Link', 'afa-events' ),
			'item_link_description'    => _x( 'A Link to an Event', 'afa-events' ),
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
			'taxonomies'            => array( 'post_tag' ),
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
			'name'                     => _x( 'Agenda Items', 'afa-events' ),
			'singular_name'            => _x( 'Agenda Item', 'afa-events' ),
			'name_admin_bar'           => _x( 'Agenda', 'afa-events' ),
			'add_new'                  => _x( 'Add New', 'afa-events' ),
			'add_new_item'             => _x( 'Add New Agenda Item', 'afa-events' ),
			'edit_item'                => _x( 'Edit Agenda Item', 'afa-events' ),
			'new_item'                 => _x( 'New Agenda Item', 'afa-events' ),
			'view_item'                => _x( 'View Agenda Item', 'afa-events' ),
			'view_items'               => _x( 'View Agenda Item', 'afa-events' ),
			'search_items'             => _x( 'Search Agenda Items', 'afa-events' ),
			'not_found'                => _x( 'No Agenda Items Found', 'afa-events' ),
			'not_found_in_trash'       => _x( 'No Agenda Items found in Trash', 'afa-events' ),
			'parent_item_colon'        => _x( 'Parent Agenda Item:', 'afa-events' ),
			'all_items'                => _x( 'All Agenda Items', 'afa-events' ),
			'archives'                 => _x( 'Agenda Item Archives', 'afa-events' ),
			'attributes'               => _x( 'Agenda Item Attributes', 'afa-events' ),
			'insert_into_item'         => _x( 'Insert into Agenda Item', 'afa-events' ),
			'uploaded_to_this_item'    => _x( 'Uploaded to this Agenda Item', 'afa-events' ),
			'featured_image'           => _x( 'Banner Image', 'afa-events' ),
			'set_featured_image'       => _x( 'Set Banner Image', 'afa-events' ),
			'remove_featured_image'    => _x( 'Remove Banner Image', 'afa-events' ),
			'use_featured_image'       => _x( 'Usa as Banner Image', 'afa-events' ),
			'menu_name'                => _x( 'Agenda Items', 'afa-events' ),
			'filter_items_list'        => _x( 'Filter Agenda Items List', 'afa-events' ),
			'filter_by_date'           => _x( 'Filter Agenda Items by Posting Date', 'afa-events' ),
			'items_list_navigation'    => _x( 'Agenda Items List Navigation', 'afa-events' ),
			'items_list'               => _x( 'Agenda Items List', 'afa-events' ),
			'item_published'           => _x( 'Agenda Item Published', 'afa-events' ),
			'item_published_privately' => _x( 'Agenda Item Published Privately', 'afa-events' ),
			'item_reverted_to_draft'   => _x( 'Agenda Item Reverted to Draft', 'afa-events' ),
			'item_trashed'             => _x( 'Agenda Item Trashed', 'afa-events' ),
			'item_scheduled'           => _x( 'Agenda Item Posting Scheduled', 'afa-events' ),
			'item_updated'             => _x( 'Agenda Item Updated', 'afa-events' ),
			'item_link'                => _x( 'Agenda Item Link', 'afa-events' ),
			'item_link_description'    => _x( 'A Link to an Agenda Item', 'afa-events' ),
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
		// PERSONS formerly EVENT SPEAKERS
		$person_labels = array(
			'name'                     => _x( 'People', 'afa-events' ),
			'singular_name'            => _x( 'Person', 'afa-events' ),
			'name_admin_bar'           => _x( 'People', 'afa-events' ),
			'add_new'                  => _x( 'Add New', 'afa-events' ),
			'add_new_item'             => _x( 'Add New Person', 'afa-events' ),
			'edit_item'                => _x( 'Edit Person', 'afa-events' ),
			'new_item'                 => _x( 'New Person', 'afa-events' ),
			'view_item'                => _x( 'View Person', 'afa-events' ),
			'view_items'               => _x( 'View Person', 'afa-events' ),
			'search_items'             => _x( 'Search People', 'afa-events' ),
			'not_found'                => _x( 'No People Found', 'afa-events' ),
			'not_found_in_trash'       => _x( 'No People found in Trash', 'afa-events' ),
			'parent_item_colon'        => _x( 'Parent Person:', 'afa-events' ),
			'all_items'                => _x( 'All People', 'afa-events' ),
			'archives'                 => _x( 'Person Archives', 'afa-events' ),
			'attributes'               => _x( 'Person Attributes', 'afa-events' ),
			'insert_into_item'         => _x( 'Insert into Person', 'afa-events' ),
			'uploaded_to_this_item'    => _x( 'Uploaded to this Person', 'afa-events' ),
			'featured_image'           => _x( 'Banner Image', 'afa-events' ),
			'set_featured_image'       => _x( 'Set Banner Image', 'afa-events' ),
			'remove_featured_image'    => _x( 'Remove Banner Image', 'afa-events' ),
			'use_featured_image'       => _x( 'Usa as Banner Image', 'afa-events' ),
			'menu_name'                => _x( 'People', 'afa-events' ),
			'filter_items_list'        => _x( 'Filter People List', 'afa-events' ),
			'filter_by_date'           => _x( 'Filter People by Posting Date', 'afa-events' ),
			'items_list_navigation'    => _x( 'People List Navigation', 'afa-events' ),
			'items_list'               => _x( 'People List', 'afa-events' ),
			'item_published'           => _x( 'Person Published', 'afa-events' ),
			'item_published_privately' => _x( 'Person Published Privately', 'afa-events' ),
			'item_reverted_to_draft'   => _x( 'Person Reverted to Draft', 'afa-events' ),
			'item_trashed'             => _x( 'Person Trashed', 'afa-events' ),
			'item_scheduled'           => _x( 'Person Posting Scheduled', 'afa-events' ),
			'item_updated'             => _x( 'Person Updated', 'afa-events' ),
			'item_link'                => _x( 'Person Link', 'afa-events' ),
			'item_link_description'    => _x( 'A Link to a Person', 'afa-events' ),
		);
		$person_rewrite = array(
			'slug'                  => 'person',
		);
		$person_args = array(
			'label'                 => __( 'People', 'afa-events' ),
			'description'           => __( 'AFA People and Bios', 'afa-events' ),
			'labels'                => $person_labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'taxonomies'            => array(),
			'hierarchical'          => false,
			'public'                => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-groups',
			'has_archive'           => false,
			'rewrite'               => $person_rewrite,
			'capability_type'       => 'post',
			'show_in_rest'			=> false,
		);
		register_post_type( 'person', $person_args );

		// ---------------------------------------------------------------------------------------
		// COMPANIES
		$company_labels = array(
			'name'                     => _x( 'Companies', 'afa-events' ),
			'singular_name'            => _x( 'Company', 'afa-events' ),
			'name_admin_bar'           => _x( 'Companies', 'afa-events' ),
			'add_new'                  => _x( 'Add New', 'afa-events' ),
			'add_new_item'             => _x( 'Add New Company', 'afa-events' ),
			'edit_item'                => _x( 'Edit Company', 'afa-events' ),
			'new_item'                 => _x( 'New Company', 'afa-events' ),
			'view_item'                => _x( 'View Company', 'afa-events' ),
			'view_items'               => _x( 'View Company', 'afa-events' ),
			'search_items'             => _x( 'Search Companies', 'afa-events' ),
			'not_found'                => _x( 'No Companies Found', 'afa-events' ),
			'not_found_in_trash'       => _x( 'No Companies found in Trash', 'afa-events' ),
			'parent_item_colon'        => _x( 'Parent Company:', 'afa-events' ),
			'all_items'                => _x( 'All Companies', 'afa-events' ),
			'archives'                 => _x( 'Company Archives', 'afa-events' ),
			'attributes'               => _x( 'Company Attributes', 'afa-events' ),
			'insert_into_item'         => _x( 'Insert into Company', 'afa-events' ),
			'uploaded_to_this_item'    => _x( 'Uploaded to this Company', 'afa-events' ),
			'featured_image'           => _x( 'Banner Image', 'afa-events' ),
			'set_featured_image'       => _x( 'Set Banner Image', 'afa-events' ),
			'remove_featured_image'    => _x( 'Remove Banner Image', 'afa-events' ),
			'use_featured_image'       => _x( 'Usa as Banner Image', 'afa-events' ),
			'menu_name'                => _x( 'Companies', 'afa-events' ),
			'filter_items_list'        => _x( 'Filter Companies List', 'afa-events' ),
			'filter_by_date'           => _x( 'Filter Companies by Posting Date', 'afa-events' ),
			'items_list_navigation'    => _x( 'Companies List Navigation', 'afa-events' ),
			'items_list'               => _x( 'Companies List', 'afa-events' ),
			'item_published'           => _x( 'Company Published', 'afa-events' ),
			'item_published_privately' => _x( 'Company Published Privately', 'afa-events' ),
			'item_reverted_to_draft'   => _x( 'Company Reverted to Draft', 'afa-events' ),
			'item_trashed'             => _x( 'Company Trashed', 'afa-events' ),
			'item_scheduled'           => _x( 'Company Posting Scheduled', 'afa-events' ),
			'item_updated'             => _x( 'Company Updated', 'afa-events' ),
			'item_link'                => _x( 'Company Link', 'afa-events' ),
			'item_link_description'    => _x( 'A Link to a Company', 'afa-events' ),
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
			'menu_icon'             => 'dashicons-calendar-alt',
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

		$views['future'] = '<a href="edit.php?timeslice=future&post_type=agenda">Current and Future Agenda Items</a>';
		$views['past'] = '<a href="edit.php?timeslice=past&post_type=agenda">Past Agenda Items</a>';
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

	/*

	THIS WAS AN ATTEMPT TO ADD AN UNLISTED EVENT STATUS TO THE PLUGIN.
	CUSTOM EVENT STATUSES AREN'T HANDLED WELL BY WORDPRESS, AND IT'S A
	LONG STANDING ISSUE. IT WORKED, BUT THERE ARE ISSUES AROUND EVENTS
	SHOWING UP IN SEARCH, ETC., LEAVING IT FALLOW FOR THE TIME BEING.

	TO REENABLE, ADD THE FOLLOWING LINES TO THE POST TYPES SECTION OF class-afa-events.php
		// $this->loader->add_action( 'init', $plugin_post_types, 'afa_events_custom_post_status', 9 );
		// $this->loader->add_action( 'post_submitbox_misc_actions', $plugin_post_types, 'afa_events_custom_post_status_script', 9 );
		// $this->loader->add_action( 'ppre_get_posts', $plugin_post_types, 'afa_events_hide_unlisted', 9 );


	public static function afa_events_custom_post_status(){
		$args = array(
			'label'                     => _x( 'Unlisted', 'post' ),
			'label_count'               => _n_noop( 'Unlisted (%s)', 'Unlisted (%s)' ),
			'exclude_from_search'       => true,  //default: $internal
			'public'                    => true,  //default: false
			'internal'                  => false, //default: false
			'protected'                 => false, //default: false
			'private'                   => false, //default: false
			'publicly_queryable'        => false, //default: $public
			'show_in_admin_all_list'    => true,  //default: opposite of $internal
			'show_in_admin_status_list' => true,  //default: opposite of $internal
			'date_floating'             => false, //default: false
		);
		register_post_status( 'unlisted', $args );
	}

	public static function afa_events_custom_post_status_script(){

		global $post;

		//only when editing an event
		if ( $post->post_type == 'event' ){

			// custom post status: unlisted
			$complete = '';
			$script = '';

			if (  $post->post_status == 'unlisted' ){
				$complete = 'selected=\"selected\"';
				$script = "jQuery('#post-status-display').text('Unlisted');";
			}

			echo '<script>'.
				'jQuery(document).ready(function($){'.
					'jQuery("select#post_status").append('.
						'"<option value=\"unlisted\" '.$complete.'>'.
							'Unlisted'.
						'</option>"'.
					');'.
					$script.
				'});'.
			'</script>';
		}
	}

	public static function afa_events_hide_unlisted( $query ) {
		if ( ! $query->is_single() && ! is_admin() ) {
			$query->set( 'post_status', array('publish') );
			return $query;
		}
	}
	*/

}
