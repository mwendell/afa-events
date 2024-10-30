<?php

/**
 * The class that defines custom taxonomies.
 *
 * @package		AFA_Events
 * @subpackage	AFA_Events/includes
 *
 * @link		http://unleashed-technologies.com
 * @since		1.0.0
 * @author		Michael Wendell <mwendell@unleashed-technologies.com>
 */
class AFA_Events_Taxonomies {

	/**
	 * Register all of the taxononmies.
	 *
	 * @since    1.0.0
	 */
	function register_afa_events_taxonomies() {

		// ---------------------------------------------------------------------------------------
		// EVENT CATEGORIES
		$event_category_labels = array(
			'name'                 => _x( 'Event Category', 'afa-events' ),
			'singular_name'        => _x( 'Event Categories', 'afa-events' ),
			'menu_name'            => __( 'Event Category', 'afa-events' ),
		);
		$event_category_args = array(
			'labels'               => $event_category_labels,
			'hierarchical'         => true,
			'public'               => true,
			'show_ui'              => true,
			'show_in_nav_menus'    => false,
			'show_in_quick_edit'   => true,
			'has_archive'          => false,
			'show_admin_column'    => true,
			'show_tagcloud'        => false,
			'show in rest'         => true,
		);
		register_taxonomy( 'event_category', array( 'event' ), $event_category_args );

	}

	/**
	 * Filter function to remove description column from taxonomy lists.
	 *
	 * @since    1.0.0
	 */
	public function remove_taxonomy_description_column ( $columns ) {
		if ( isset( $columns['description'] ) ) {
			unset( $columns['description'] );
		}
		return $columns;
	}


}
