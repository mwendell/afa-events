<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://https://www.kwyjibo.com
 * @since      1.0.0
 *
 * @package    AFA_Events
 * @subpackage AFA_Events/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    AFA_Events
 * @subpackage AFA_Events/includes
 * @author     Michael Wendell <mwendell@kwyjibo.com>
 */
class AFA_Events {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      AFA_Events_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'AFA_EVENTS_VERSION' ) ) {
			$this->version = AFA_EVENTS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'afa-events';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - AFA_Events_Loader. Orchestrates the hooks of the plugin.
	 * - AFA_Events_i18n. Defines internationalization functionality.
	 * - AFA_Events_Admin. Defines all hooks for the admin area.
	 * - AFA_Events_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-afa-events-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-afa-events-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-afa-events-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-afa-events-public.php';

		/**
		 * The class responsible for defining all plugin post types.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-afa-events-post-types.php';

		/**
		 * The class responsible for defining all plugin taxonomies.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-afa-events-taxonomies.php';

		/**
		 * The class responsible for defining all plugin ACF relationships.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-afa-events-acf.php';

		$this->loader = new AFA_Events_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the AFA_Events_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new AFA_Events_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new AFA_Events_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );


		// custom post types - see class-AFA_Events-post-types.php in plugin's includes folder
		$plugin_post_types = new AFA_Events_Post_Types();
		$this->loader->add_action( 'init', $plugin_post_types, 'register_afa_events_post_types', 9 );
		$this->loader->add_action( 'admin_init', $plugin_post_types, 'clean_up_events_menu', 9 );

		$this->loader->add_filter( 'manage_event_posts_columns', $plugin_post_types, 'add_event_columns' );
		$this->loader->add_filter( 'manage_edit-event_sortable_columns', $plugin_post_types, 'sortable_event_columns' );
		$this->loader->add_filter( 'views_edit-event', $plugin_post_types, 'event_view_show_filters' );
		$this->loader->add_filter( 'posts_where', $plugin_post_types, 'event_view_filter' );
		$this->loader->add_action( 'manage_event_posts_custom_column', $plugin_post_types, 'populate_event_columns', 10, 2 );
		$this->loader->add_action( 'pre_get_posts', $plugin_post_types, 'event_columns_default_sort_order', 10, 2 );

		$this->loader->add_filter( 'manage_agenda_posts_columns', $plugin_post_types, 'add_agenda_columns' );
		$this->loader->add_filter( 'manage_edit-agenda_sortable_columns', $plugin_post_types, 'sortable_agenda_columns' );
		$this->loader->add_filter( 'views_edit-agenda', $plugin_post_types, 'agenda_view_show_filters' );
		$this->loader->add_filter( 'posts_where', $plugin_post_types, 'agenda_view_filter' );
		$this->loader->add_action( 'manage_agenda_posts_custom_column', $plugin_post_types, 'populate_agenda_columns', 10, 2 );
		$this->loader->add_action( 'pre_get_posts', $plugin_post_types, 'agenda_columns_default_sort_order', 10, 2 );

		$this->loader->add_filter( 'manage_company_posts_columns', $plugin_post_types, 'add_company_columns' );
		$this->loader->add_filter( 'manage_edit-company_sortable_columns', $plugin_post_types, 'sortable_company_columns' );
		$this->loader->add_filter( 'views_edit-company', $plugin_post_types, 'company_view_show_filters' );
		$this->loader->add_filter( 'posts_where', $plugin_post_types, 'company_view_filter' );
		$this->loader->add_action( 'manage_company_posts_custom_column', $plugin_post_types, 'populate_company_columns', 10, 2 );
		$this->loader->add_action( 'pre_get_posts', $plugin_post_types, 'company_columns_default_sort_order', 10, 2 );

		$this->loader->add_action( 'save_post_agenda', $plugin_post_types, 'set_agenda_item_parent', 10, 3 );

		$this->loader->add_action( 'pre_get_posts', $plugin_post_types, 'afa_events_custom_archive', 10, 2 );
		$this->loader->add_filter( 'query_vars', $plugin_post_types, 'afa_events_search_query_vars' );

		// custom taxonomies - see class-AFA_Events-taxonomies.php in plugin's includes folder
		$plugin_taxonomies = new AFA_Events_Taxonomies();
		$this->loader->add_action( 'init', $plugin_taxonomies, 'register_afa_events_taxonomies', 8 );
		$this->loader->add_filter( 'manage_edit-event_category_columns', $plugin_taxonomies, 'remove_taxonomy_description_column' );

		// advanced custom fields
		$plugin_acf = new AFA_Events_ACF();
		$this->loader->add_action( 'acf/init', $plugin_acf, 'register_acf_block_types', 9 );
		// $this->loader->add_action( 'acf/init', $plugin_acf, 'add_acf_options_pages', 9 );

		$this->loader->add_filter( 'acf/load_field/key=field_64c9253ed54f9', $plugin_acf, 'update_agenda_items_on_edit_event_page', 9 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new AFA_Events_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    AFA_Events_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
