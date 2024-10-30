<?php

/**
 * The class that handles ACF customization.
 *
 * @package    AFA_Events
 * @subpackage  AFA_Events/includes
 *
 * @link    http://unleashed-technologies.com
 * @since    1.0.0
 * @author    Michael Wendell <mwendell@unleashed-technologies.com>
 */
class AFA_Events_ACF {

	function register_acf_block_types() {

		if ( ! function_exists( 'acf_register_block_type' ) ) { return; }

		/*
		// register an image CTA block.
		acf_register_block_type( array(
			'name'              => 'af_image_cta',
			'title'             => __('Three Column Image CTA'),
			'description'       => __('CTAs used to link to internal pages'),
			'render_template'   => 'template-parts/blocks/afa-events/image-cta.php',
			'category'          => 'formatting',
			'icon'              => 'admin-comments',
			'keywords'          => array(),
		));

		// register a banner image CTA block.
		acf_register_block_type( array(
			'name'              => 'af_banner_cta',
			'title'             => __('Banner CTA'),
			'description'       => __('Full width banner CTA'),
			'render_template'   => 'template-parts/blocks/afa-events/banner-cta.php',
			'category'          => 'formatting',
			'icon'              => 'admin-comments',
			'keywords'          => array(),
		));

		// register a full-width image block.
		acf_register_block_type( array(
			'name'              => 'af_full_width_image',
			'title'             => __('Full Width Image'),
			'description'       => __('Custom full width image for AF Magazine.'),
			'render_template'   => 'template-parts/blocks/afa-events/full-width-image.php',
			'category'          => 'formatting',
			'icon'              => 'admin-comments',
			'keywords'          => array(),
		));
		*/

	}

	/*
	function add_acf_options_pages() {
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page(
				array(
				'page_title' 	=> 'Paywall Settings',
				'menu_title'	=> 'Paywall',
				'menu_slug' 	=> 'paywall-settings',
				'capability'	=> 'edit_posts',
				'redirect'		=> false,
				)
			);
		}
	}
	*/

	function update_agenda_items_on_edit_event_page( $field ) {

		if ( ! isset( $_GET['post'] ) ) { return $field; }

		$post_id = $_GET['post'];

		if ( ! is_numeric( $post_id ) ) { return $field; }

		$agenda = '';

		global $wpdb;
		$sql = "SELECT m.post_id, p.post_title, m2.meta_value AS 'start_date', m3.meta_value AS 'start_time'
			FROM wp_postmeta m
			JOIN wp_posts p ON m.post_id = p.ID
			LEFT JOIN wp_postmeta m2 ON p.ID = m2.post_id AND m2.meta_key = 'times_start_date'
			LEFT JOIN wp_postmeta m3 ON p.ID = m3.post_id AND m3.meta_key = 'times_start_time'
			WHERE ( p.post_type = 'agenda' ) AND (m.meta_key = 'event') AND (m.meta_value = %d)
			ORDER BY start_date, start_time, post_title;";
		$sql = $wpdb->prepare( $sql, $post_id );
		$agenda_items = $wpdb->get_results( $sql, ARRAY_A );
		foreach( $agenda_items as $a ) {
			$agenda .= "<a href='/wp/wp-admin/post.php?post={$a['post_id']}&action=edit'>{$a['post_title']}</a><br/>";
		}

		$field['readonly'] = true;
		$field['message'] = $agenda;
		return $field;
	}

	// FILTER ADDED IN class-afa-events.php
	// add_filter('acf/load_field', 'update_agenda_items_on_edit_event_page');
	// add_filter('acf/load_field/type=select', 'update_agenda_items_on_edit_event_page');
	// add_filter('acf/load_field/name=custom_select', 'update_agenda_items_on_edit_event_page');
	// add_filter('acf/load_field/key=field_64c9253ed54f9', 'update_agenda_items_on_edit_event_page');

}
