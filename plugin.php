<?php
/*

Programme: name, content
Venue    : name, address, url, tel





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

		define( 'CP_PLUGIN_VERSION', '2.0' );

		// Load plugin text domain
		add_action( 'init', array( $this, 'plugin_textdomain' ) );

		// Register admin styles and scripts
		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Register site styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );

		add_action( 'init', array( $this, 'create_custom_post_types' ) );
		add_action( 'save_post', array( $this, 'save_event_meta' ), 10, 2 );

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

		$domain = 'concertpress';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
        load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
        load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	} // end plugin_textdomain

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	function register_admin_styles() {

		if ( 'event' == get_current_screen()->id ) {

			wp_enqueue_style( 'jquery-ui', 'http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css' );
			wp_enqueue_style( 'concertpress-admin-styles', plugins_url( 'concertpress/css/admin.css' ) );
		}

	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	function register_admin_scripts() {

		if ( 'event' == get_current_screen()->id ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'concertpress-admin-script', plugins_url( 'concertpress/js/admin.js' ), array( 'jquery' ), CP_PLUGIN_VERSION, true );
		}

	} // end register_admin_scripts

	/**
	 * Registers and enqueues plugin-specific styles.
	 */
	function register_plugin_styles() {

		// TODO:	Change 'concertpress' to the name of your plugin
		wp_enqueue_style( 'concertpress-plugin-styles', plugins_url( 'concertpress/css/display.css' ) );

	} // end register_plugin_styles

	/**
	 * Registers and enqueues plugin-specific scripts.
	 */
	function register_plugin_scripts() {

		// TODO:	Change 'concertpress' to the name of your plugin
		wp_enqueue_script( 'concertpress-plugin-script', plugins_url( 'concertpress/js/display.js' ) );

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

  // 		$post = array(
		// 	'post_content' => '',
		// 	'post_name'    => 'palladium',
		// 	'post_status'  => 'publish',
		// 	'post_title'   => 'Palladium',
		// 	'post_type'    => 'venue',
		// );
		// $id = wp_insert_post( $post );
		// add_post_meta( $id, '_url', 'http://google.com' );
		// add_post_meta( $id, '_address', 'Torupsgatan 1D, 217 73 Malmö' );
		// add_post_meta( $id, '_email', 'theorboman@gmail.com' );

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

		if ( !post_type_exists( $args['name'] ) )
			register_post_type( $args['name'], $args );

	}


	/** Register meta boxes */
	function register_meta_boxes() {
		add_meta_box(
			'convertpress-event-date-meta',
			__( 'Date', 'ibmetall' ),
			array( $this, 'print_event_date_meta' ),
			'event',
			'normal',
			'high'
		);
		add_meta_box(
			'convertpress-event-programme-meta',
			__( 'Programme', 'ibmetall' ),
			array( $this, 'print_event_programme_meta' ),
			'event',
			'normal',
			'high'
		);
		add_meta_box(
			'convertpress-event-venue-meta',
			__( 'Venue', 'ibmetall' ),
			array( $this, 'print_event_venue_meta' ),
			'event',
			'normal',
			'high'
		);
	}

	private function print_select_lists( $type = 'programme' ) {
		global $wpdb, $post;
		$value = get_post_meta( $post->ID, "_$type", true );
		$sql = "SELECT ID, post_title AS title FROM $wpdb->posts WHERE post_type = '%s' AND post_status = 'publish'";
		$items = $wpdb->get_results( $wpdb->prepare( $sql, $type ) );
		if ( !$items )
			return false;

		$name = "concertpress[$type][select_id]";
		?>
		<label class="concertpress-label" for="<?php echo $name ?>"><?php printf( __( 'Select a %s', 'concertpress' ), $type ) ?></label>
			<select class="concertpress-select" name="<?php echo $name ?>" id="<?php echo $name ?>">
				<option value="0"><?php _e( '-- select --', 'concertpress' ); ?></option>
				<?php foreach ( $items as $item ) : ?>
					<option value="<?php esc_attr_e( $item->ID ) ?>" <?php selected( $value, $item->ID ) ?>><?php echo $item->title ?></option>
				<?php endforeach ?>
			</select>
	<?php
	}


	function print_event_date_meta() {
		global $post;
		// Use nonce for verification
		wp_nonce_field( 'concertpress_nonce', 'concertpress_add_event' );

		$saved_hour = $saved_min = $date = $end_date = false;
		$start_date = get_post_meta( $post->ID, '_start_date', true );
		$end_date   = get_post_meta( $post->ID, '_end_date', true );
		$checked    = '';

		if ( $start_date ) {
			$date = date( 'Y-m-d', $start_date );
			$saved_hour = date( 'H', $start_date );
			$saved_min  = date( 'i', $start_date );
		}

		if ( $end_date ) {
			$end_date = date( 'Y-m-d', $end_date );
			$checked = 'checked';
			$saved_hour = $saved_min = false;
		}

		?>
		<div class="date">

			<label class="concertpress-label" for="concertpress[date][start]"><?php _e( 'Start date', 'concertpress' ) ?></label>
				<input type="text" class="concertpress-datepicker" id="concertpress[date][start]" name="concertpress[date][start]" value="<?php echo $date ?>" />

			<select class="concertpress-time" id="concertpress[date][time][hour]" name="concertpress[date][time][hour]">
				<?php $hour = 1; ?>
				<option value="none"> -- </option>
				<?php while ( $hour < 25 ) : ?>
					<?php $pad_hour = str_pad( $hour, 2, '0', STR_PAD_LEFT ); ?>
					<option <?php selected( $saved_hour, $pad_hour ) ?> value="<?php echo $pad_hour ?>"><?php echo $pad_hour ?></option>
					<?php $hour++; ?>
				<?php endwhile; ?>
			</select>

			:

			<select class="concertpress-time" id="concertpress[date][time][min]" name="concertpress[date][time][min]">
				<?php $min = 0; ?>
				<option value="none"> -- </option>
				<?php while ( $min < 60 ) : ?>
					<?php $pad_min = str_pad( $min, 2, '0', STR_PAD_LEFT ); ?>
					<option <?php selected( $saved_min, $pad_min ) ?> value="<?php echo $pad_min ?>"><?php echo $pad_min ?></option>
					<?php $min += 5; ?>
				<?php endwhile; ?>
			</select>

			<label for="concertpress[date][multi]">
				<input <?php echo $checked ?> class="concertpress-multi-date-trigger" type="checkbox" id="concertpress[date][multi]" name="concertpress[date][multi]">
				<?php _e( 'This is a multi-date event', 'concertpress' ) ?>
			</label>

			<div class="end-date">

				<label class="concertpress-label" for="concertpress[date][end]"><?php _e( 'End date', 'concertpress' ) ?></label>
					<input type="text" class="concertpress-datepicker" id="concertpress[date][end]" name="concertpress[date][end]" value="<?php echo $end_date ?>"/>

			</div>

		</div>
	<?php
	}


	/** Add meta boxes to events */
	function print_event_programme_meta() {
		global $post;

		$programme_id = get_post_meta( $post->ID, '_programme', true );
		$this->print_select_lists();

		$editor_settings = array(
			'teeny'         => true,
			'media_buttons' => false,
			'textarea_name' => 'concertpress[programme][content]',
		);
		?>
		<p class="new-trigger">
			<a href="#" class="button button-secondary"><?php _e( 'Add a new programme', 'concertpress' ) ?></a>
		</p>
		<div class="new new-programme">

			<?php if ( ! $programme_id && isset( $programme_errors['title'] ) )
				echo "<p class='cp-error'>{$programme_errors['title']}</p>"; ?>

			<label for="concertpress[programme][title]"><?php _e( 'Programme title', 'concertpress' ) ?></label>
				<input type="text" name="concertpress[programme][title]" id="concertpress[programme][title]" />

		<?php if ( ! $programme_id && isset( $programme_errors['content'] ) )
			echo "<p class='cp-error'>{$programme_errors['content']}</p>"; ?>

			<label for="concertpress[programme][content]"><?php _e( 'Programme details', 'concertpress' ) ?></label>

		<?php
			wp_editor( '', 'programme-content', $editor_settings );
			do_action( 'concertpress_add_programme' );
		?>
		</div>
		<?php
	}

	function print_event_venue_meta() {
		global $post;

		$venue_id = get_post_meta( $post->ID, '_venue', true );
		$this->print_select_lists( 'venue' );
		?>
		<p class="new-trigger">
			<a href="#" class="button button-secondary"><?php _e( 'Add a new venue', 'concertpress' ) ?></a>
		</p>
		<div class="new new-venue">


			<?php if ( ! $venue_id && isset( $venue_errors['name'] ) )
				echo "<p class='cp-error'>{$venue_errors['name']}</p>"; ?>

			<label for="concertpress[venue][name]"><?php _e( 'Name', 'concertpress' ) ?></label>
				<input type="text" id="concertpress[venue][name]" name="concertpress[venue][name]" />

			<label for="concertpress[venue][url]"><?php _e( 'URL', 'concertpress' ) ?></label>
				<input type="url" id="concertpress[venue][url]" name="concertpress[venue][url]" />

			<label for="concertpress[venue][tel]"><?php _e( 'Tel', 'concertpress' ) ?></label>
				<input type="tel" id="concertpress[venue][tel]" name="concertpress[venue][tel]" />

			<label for="concertpress[venue][address]"><?php _e( 'Address', 'concertpress' ) ?></label>
				<input type="text" id="concertpress[venue][address]" name="concertpress[venue][address]" />

			<?php do_action( 'concertpress_add_venue' ); ?>

		</div>

	<?php
	}


	function save_event_meta( $post_id, $post ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		if ( is_int( wp_is_post_autosave( $post_id ) ) )
			return;

		// if ( ! isset( $_REQUEST['concertpress-nonce'] ) || ! wp_verify_nonce( $_REQUEST['concertpress_add_event'], 'concertpress_nonce' ) )
		// 	return $post_id;

		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;

		if ( 'event' != $post->post_type )
			return $post_id;

		if ( ! isset( $_REQUEST['concertpress'] ) )
			return $post_id;

		// unhook this function so it doesn't loop infinitely
		remove_action( 'save_post', array( $this, 'save_event_meta' ) );

		$cp        = $_REQUEST['concertpress'];
		$date      = $cp['date'];
		$programme = $cp['programme'];
		$venue     = $cp['venue'];
		$errors    = array();

		error_log( print_r( $cp, 1 ) );

		if ( '' == $date['start'] ) {

			$errors[] = __( 'Please add a date for the event', 'concertpress' );

		} else {

			$hour = ( isset( $date['time']['hour'] ) ) ? $date['time']['hour'] : 0;
			$min  = ( isset( $date['time']['min'] ) )  ? $date['time']['min']  : 0;

			list( $year, $month, $day ) = explode( '-', $date['start'] );
			$start_timestamp = mktime( $hour, $min, 0, $month, $day, $year );

			update_post_meta( $post->ID, '_start_date', $start_timestamp );

			if ( isset( $date['multi'] ) ) {

				if ( '' == $date['end'] ) {

					if ( get_post_meta( $post->ID, '_end_date', true ) )
						delete_post_meta( $post_id, '_end_date' );

				} else {

					list( $year, $month, $day ) = explode( '-', $date['end'] );
					$end_timestamp = mktime( 0, 0, 0, $month, $day, $year );

					if ( $end_timestamp < $start_timestamp ) {
						$errors[] = __( 'The end date should be later than the start date', 'concertpress' );
						delete_post_meta( $post_id, '_end_date' );
					} else {
						update_post_meta( $post->ID, '_end_date', $end_timestamp );
					}

				}

			} else {

				if ( get_post_meta( $post->ID, '_end_date', true ) )
					delete_post_meta( $post_id, '_end_date' );

			}

		}


		if ( $programme['select_id'] != 0 ) {

			update_post_meta( $post_id, '_programme', $programme['select_id'] );

		} else {

			if ( '' == $programme['content'] || '' == $programme['title'] ) {

				if ( '' == $programme['content'] )
					$errors[] = __( 'Please add a programme', 'concertpress' );
				if ( '' == $programme['title'] )
					$errors[] = __( 'Please add a programme title', 'concertpress' );

			} else {

				$new_programme = array(
					'post_content' => $programme['content'],
					'post_title'   => $programme['title'],
					'post_status'  => 'publish',
					'post_type'    => 'programme',
				);

				$new_programme_id = wp_insert_post( $new_programme );
				add_post_meta( $post_id, '_programme', $new_programme_id );

			}
		}


		if ( $venue['select_id'] != 0 ) {

			update_post_meta( $post_id, '_venue', $venue['select_id'] );

		} else {

			if ( empty( $venue['name'] ) ) {

				$errors[] = __( 'Please add a venue', 'concertpress' );

			} else {

				$new_venue = array(
					'post_content' => '',
					'post_title'   => $venue['name'],
					'post_status'  => 'publish',
					'post_type'    => 'venue',
				);

				$new_venue_id = wp_insert_post( $new_venue );
				add_post_meta( $post_id, '_venue', $new_venue_id );
				add_post_meta( $new_venue_id, '_url', esc_url_raw( $venue['url'] ) );
				add_post_meta( $new_venue_id, '_address', sanitize_text_field( $venue['address'] ) );

			}

		}


		if ( empty( $errors ) ) {
			if ( get_post_meta( $post->ID, '_errors', true ) )
				delete_post_meta( $post->ID, '_errors' );
		} else {
			update_post_meta( $post->ID, '_errors', $errors );
		}

		error_log( print_r( $errors, 1 ) );

		add_action( 'save_post', array( $this, 'save_event_meta' ) );

	}


}


global $concertpress;
$concertpress = new ConcertPress();







