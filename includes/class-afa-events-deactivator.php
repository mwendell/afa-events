<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://https://www.kwyjibo.com
 * @since      1.0.0
 *
 * @package    AFA_Events
 * @subpackage AFA_Events/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    AFA_Events
 * @subpackage AFA_Events/includes
 * @author     Michael Wendell <mwendell@kwyjibo.com>
 */
class AFA_Events_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		flush_rewrite_rules();

	}

}
