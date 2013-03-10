<?php
/*
Plugin Name: ConcertPress
Plugin URI: TODO
Description: An events management plugin specifically for classical musicians
Version: 2.0
Author: theorboman@gmail.com
Author URI: http://richardsweeney.com
License:

  Copyright 2013 TODO (email@domain.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

// TODO: rename this class to a proper name for your plugin
class ConcertPress {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'plugin_textdomain' ) );

		// Register admin styles and scripts
		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Register site styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );

		add_action( 'init', array( $this, 'create_custom_post_types' ) );

		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		register_uninstall_hook( __FILE__, 'ConcertPress::uninstall' );

	    /*
	     * TODO:
	     * Define the custom functionality for your plugin. The first parameter of the
	     * add_action/add_filter calls are the hooks into which your code should fire.
	     *
	     * The second parameter is the function name located within this class. See the stubs
	     * later in the file.
	     *
	     * For more information:
	     * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
	     */
	    add_action( 'TODO', array( $this, 'action_method_name' ) );
	    add_filter( 'TODO', array( $this, 'filter_method_name' ) );

	} // end constructor

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	function activate( $network_wide ) {
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	static function deactivate( $network_wide ) {
		// TODO:	Define deactivation functionality here
	} // end deactivate

	/**
	 * Fired when the plugin is uninstalled.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	function uninstall( $network_wide ) {
		// TODO:	Define uninstall functionality here
	} // end uninstall

	/**
	 * Loads the plugin text domain for translation
	 */
	function plugin_textdomain() {

		// TODO: replace "plugin-name-locale" with a unique value for your plugin
		$domain = 'concertpress';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
        load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
        load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	} // end plugin_textdomain

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	function register_admin_styles() {

		// TODO:	Change 'plugin-name' to the name of your plugin
		wp_enqueue_style( 'plugin-name-admin-styles', plugins_url( 'plugin-name/css/admin.css' ) );

	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	function register_admin_scripts() {

		// TODO:	Change 'plugin-name' to the name of your plugin
		wp_enqueue_script( 'plugin-name-admin-script', plugins_url( 'plugin-name/js/admin.js' ) );

	} // end register_admin_scripts

	/**
	 * Registers and enqueues plugin-specific styles.
	 */
	function register_plugin_styles() {

		// TODO:	Change 'plugin-name' to the name of your plugin
		wp_enqueue_style( 'plugin-name-plugin-styles', plugins_url( 'plugin-name/css/display.css' ) );

	} // end register_plugin_styles

	/**
	 * Registers and enqueues plugin-specific scripts.
	 */
	function register_plugin_scripts() {

		// TODO:	Change 'plugin-name' to the name of your plugin
		wp_enqueue_script( 'plugin-name-plugin-script', plugins_url( 'plugin-name/js/display.js' ) );

	} // end register_plugin_scripts

	/*--------------------------------------------*
	 * Core Functions
	 *---------------------------------------------*/

	/**
 	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *		  WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 *		  Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 */
	function action_method_name() {
    	// TODO:	Define your action method here
	} // end action_method_name

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *		  WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *		  Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 */
	function filter_method_name() {
	    // TODO:	Define your filter method here
	} // end filter_method_name


	function create_custom_post_types() {

		$cpts = array(
			array(
				'name'                 => 'event',
				'singular_name'        => __( 'event', 'concertpress' ),
				'plural_name'          => __( 'events', 'concertpress' ),
				'slug'                 => 'event',
				'menu_position'        => 30,
				'register_meta_box_cb' => array( $this, 'register_meta_boxes' ),
			),
			array(
				'name'          => 'programme',
				'singular_name' => __( 'programme', 'concertpress' ),
				'plural_name'   => __( 'programmes', 'concertpress' ),
				'show_ui'       => false,
			),
			array(
				'name'          => 'venue',
				'singular_name' => __( 'venue', 'concertpress' ),
				'plural_name'   => __( 'venues', 'concertpress' ),
				'show_ui'       => false,
			),
		);
		foreach( $cpts as $cpt )
    		$this->create_post_type( $cpt );

  		// $post = array(
		// 	'post_content' => 'A lovely venue!',
		// 	'post_name'    => 'messiah',
		// 	'post_status'  => 'publish',
		// 	'post_title'   => 'Messiah',
		// 	'post_type'    => 'programme',
		// );
		// wp_insert_post( $post );

    }


	private function create_post_type( $args ) {

		$defaults = array(
			'hierarchical'         => false,
			'public'               => true,
			'show_ui'              => true,
			'query_var'            => true,
			'menu_position'        => 5,
			'supports'             => array( 'title', 'editor', 'thumbnail' ),
		);
		$args = wp_parse_args( $args, $defaults );

		$args['labels'] = array(
			'name'               => __( ucfirst( $args['plural_name'] ), 'concertpress' ),
			'singular_name'      => __( $args['singular_name'], 'concertpress' ),
			'add_new'            => sprintf( __( 'Add new %s', 'concertpress' ), $args['singular_name'] ),
			'add_new_item'       => sprintf( __( 'Add new %s', 'concertpress' ), ucfirst( $args['singular_name'] ) ),
			'edit_item'          => sprintf( __( 'Edit %s', 'concertpress' ), $args['singular_name'] ),
			'new_item'           => sprintf( __( 'New %s', 'concertpress' ), $args['singular_name'] ),
			'view_item'          => sprintf( __( 'View %s', 'concertpress' ), $args['singular_name'] ),
			'search_items'       => sprintf( __( 'Search %s', 'concertpress' ), ucfirst( $args['plural_name'] ) ),
			'not_found'          => sprintf( __( 'No %s found', 'concertpress' ), $args['plural_name'] ),
			'not_found_in_trash' => sprintf( __( 'No %s found in the trash!', 'concertpress' ), $args['plural_name'] ),
			'menu_name'          => ucfirst( __( $args['plural_name'], 'concertpress' ) ),
		);
		if ( isset( $args['slug'] ) )
			$args['rewrite'] = array( 'slug' => $args['slug'] );

		register_post_type( $args['name'], $args );

	}


	/** Register meta boxes */
	function register_meta_boxes() {
		add_meta_box(
			'convertpress-event-meta',
			__( 'Programme', 'ibmetall' ),
			array( $this, 'print_event_meta' ),
			'event',
			'normal',
			'high'
		);
		add_meta_box(
			'convertpress-venue-meta',
			__( 'Venue', 'ibmetall' ),
			array( $this, 'print_venue_meta' ),
			'venue',
			'normal',
			'high'
		);
	}

	private function print_select_lists( $text, $type = 'programme' ) {
		global $wpdb;
		$sql = "SELECT ID, post_title AS title FROM $wpdb->posts WHERE post_type = '%s' AND post_status = 'publish'";
		$items = $wpdb->get_results( $wpdb->prepare( $sql, $type ) );

		if ( !$items )
			return false;

		$name = "concertpress_$type";
		?>
		<label for="<?php echo $name ?>"><strong><?php echo $text ?>: </strong></label>
		<select name="<?php echo $name ?>">
			<?php foreach ( $items as $item ) : ?>
				<option value="<?php esc_attr_e( $item->ID ) ?>"><?php echo $item->title ?></option>
			<?php endforeach ?>
		</select>
	<?php
	}


	/** Add meta boxes to events */
	function print_event_meta() {
		$text = __( 'Select an event' , 'concertpress' );
		$this->print_select_lists( $text );
	}


	/** Add meta boxes to venues */
	function print_venue_meta() {
		$text = __( 'Select a venue' , 'concertpress' );
		$this->print_select_lists( $text, 'venue' );

	}


}


global $concertpress;
$concertpress = new ConcertPress();
