<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://www.kwyjibo.com
 * @since             1.0.0
 * @package           AFA_Events
 *
 * @wordpress-plugin
 * Plugin Name:       AFA Events
 * Plugin URI:        https://https://www.afa.org
 * Description:       Manage database structure for AFA events.
 * Version:           1.0.0
 * Author:            Michael Wendell
 * Author URI:        https://https://www.kwyjibo.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       afa-events
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AFA_EVENTS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-afa-events-activator.php
 */
function activate_afa_events() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-afa-events-activator.php';
	AFA_Events_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-afa-events-deactivator.php
 */
function deactivate_afa_events() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-afa-events-deactivator.php';
	AFA_Events_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_afa_events' );
register_deactivation_hook( __FILE__, 'deactivate_afa_events' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-afa-events.php';

/**
 * Plugin utilities that can be used globally.
 */
require plugin_dir_path( __FILE__ ) . '/afa-events-functions.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_afa_events() {

	$plugin = new AFA_Events();
	$plugin->run();

}
run_afa_events();
