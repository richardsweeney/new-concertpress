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

	private $current_post;

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

		add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		add_filter( 'post_updated_messages', array( $this, 'filter_post_messages') );

		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		register_uninstall_hook( __FILE__, 'ConcertPress::uninstall' );

		// Diverse text changes
		add_filter( 'enter_title_here', array( $this, 'custom_enter_title_here' ) );
		add_filter( 'admin_body_class', array( $this, 'body_class_names' ) );

		// Custom columns mojo for events
		add_action( 'manage_event_posts_custom_column', array( $this, 'manage_event_custom_column' ), 10, 2 );
		add_filter( 'manage_edit-event_columns', array( $this, 'set_custom_edit_event_columns' ) );
		add_filter( 'manage_edit-event_sortable_columns', array( $this, 'event_column_register_sortable' ) );
		add_filter( 'request', array( $this, 'event_column_orderby' ) );
		if ( is_admin() )
			add_filter( 'pre_get_posts', array( $this, 'pre_get_posts_filter' ) );

		add_action( 'manage_programme_posts_custom_column', array( $this, 'manage_programme_custom_column' ), 10, 2 );
		add_filter( 'manage_edit-programme_columns', array( $this, 'set_custom_edit_programme_venue_columns' ) );

		add_action( 'manage_venue_posts_custom_column', array( $this, 'manage_venue_custom_column' ), 10, 2 );
		add_filter( 'manage_edit-venue_columns', array( $this, 'set_custom_edit_programme_venue_columns' ) );

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
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	} // end plugin_textdomain

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	function register_admin_styles() {

		if ( in_array( get_current_screen()->id, array( 'event', 'venue', 'programme' ) ) ) {

			wp_enqueue_style( 'jquery-ui', 'http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css' );
			wp_enqueue_style( 'concertpress-admin-styles', plugins_url( 'concertpress/css/admin.css' ) );
		}

	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	function register_admin_scripts() {

		global $wp_locale;
		$monthNames      = json_encode( array_values( $wp_locale->month ) );
		$monthNamesShort = json_encode( array_values( $wp_locale->month_abbrev ) );
		$dayNames      	 = json_encode( array_values( $wp_locale->weekday ) );
		$dayNamesShort   = json_encode( array_values( $wp_locale->weekday_abbrev ) );

		if ( in_array( get_current_screen()->id, array( 'event', 'venue' ) ) ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'concertpress-js', plugins_url( 'concertpress/js/admin.js' ), array( 'jquery' ), CP_PLUGIN_VERSION, true );
		}
		$i18n = array(
			'date_format'     => get_option( 'date_format' ),
			'language'        => get_bloginfo( 'language' ),
			'monthNames'      => $monthNames,
			'monthNamesShort' => $monthNamesShort,
			'dayNames'        => $dayNames,
			'dayNamesShort'   => $dayNamesShort,
		);
		wp_localize_script( 'jquery', 'cp', $i18n );

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
				'singular_name'        => __( 'Event', 'concertpress' ),
				'plural_name'          => __( 'Events', 'concertpress' ),
				'slug'                 => 'event',
				'menu_position'        => 30,
				'supports'             => array( 'title', 'thumbnail' ),
				'register_meta_box_cb' => array( $this, 'register_event_meta_boxes' ),
			),
			array(
				'name'                => 'programme',
				'singular_name'       => __( 'Programme', 'concertpress' ),
				'plural_name'         => __( 'Programmes', 'concertpress' ),
				'show_in_menu'        => 'edit.php?post_type=event',
				'supports'            => array( 'title', 'editor' ),
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
			),
			array(
				'name'                 => 'venue',
				'singular_name'        => __( 'Venue', 'concertpress' ),
				'plural_name'          => __( 'Venues', 'concertpress' ),
				'show_in_menu'         => 'edit.php?post_type=event',
				'supports'             => false,
				'register_meta_box_cb' => array( $this, 'register_venue_meta_boxes' ),
				'exclude_from_search'  => true,
				'publicly_queryable'   => false,
			),
		);

		foreach( $cpts as $cpt ) {
			$this->create_post_type( $cpt );
		}

	}


	/** If there errors in the metaboxes. */
	function admin_notice() {
		global $post;
		if ( isset( $_GET['message'] ) && 'event' == get_current_screen()->id && get_post_meta( $post->ID, '_errors' ) ) :
			?>
			<div class="error">
				<?php foreach ( get_post_meta ( $post->ID, '_errors', true ) as $errors ) : ?>
					<p><?php echo $errors; ?></p>
				<?php endforeach; ?>
			</div>
		<?php endif;
	}

	 function filter_post_messages( $messages ) {
	 	global $post;

	 	if ( 'event' == get_current_screen()->id && isset( $_GET['message'] ) )
	 		// var_dump( $messages );

	 	return $messages;
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


	/** Change 'enter text here' to something more appropriate */
	function custom_enter_title_here( $text ) {
		switch ( get_current_screen()->id ) {

			case 'venue' :
				$text = __( 'Venue name', 'concertpress' );
				break;

			case 'programme' :
				$text = __( 'Programme title', 'concertpress' );
				break;

			case 'event' :
				$text = __( 'Event title', 'concertpress' );
				break;

		}
		return $text;
	}


	/** Show the programme, venue and date in the event columns */
	function manage_event_custom_column( $column, $post_id ) {
		switch ( $column ) {

			case 'programme' :
				$pid = get_post_meta( $post_id, '_programme', true );
				if ( $pid )
					echo '<a href="' . admin_url( "post.php?post=$pid&action=edit" ) . '">' . get_the_title( $pid ) . '</a>';
				else
					echo '--';
				break;

			case 'venue' :
				$vid = get_post_meta( $post_id, '_venue', true );
				if ( $vid )
					echo '<a href="' . admin_url( "post.php?post=$vid&action=edit" ) . '">' . get_the_title( $vid ) . '</a>';
				else
					echo '--';
				break;

			case 'cp-date' :
				$date = get_post_meta( $post_id, '_start_date', true );
				if ( $date )
					echo date_i18n( get_option( 'date_format' ), $date );
				else
					echo '--';
				break;

		}

	}

	/** Show the event in the programme columns */
	function manage_programme_custom_column( $column, $post_id ) {
		switch( $column ) {
			case 'event_id' :
				$this->_get_venue_programme_meta_columns( '_programme', $post_id );
				break;

		}

	}

	/** Show the event in the venue columns */
	function manage_venue_custom_column( $column, $post_id ) {
		switch( $column ) {
			case 'event_id' :
				$this->_get_venue_programme_meta_columns( '_venue', $post_id );
				break;

		}

	}


	/** Helper function to retrieve the associated event for programmes & venues */
	private function _get_venue_programme_meta_columns( $meta_key, $post_id ) {
		$args = array(
			'post_type'      => 'event',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key'     => $meta_key,
					'value'   => (int) $post_id,
					'compare' => '=',
				),
			),
		);
		$events = new WP_Query( $args );
		if ( $events->have_posts() ) {
			$i = 1;
			while ( $events->have_posts() ) {
				$events->the_post();
				echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
				if ( $i != $events->found_posts )
					echo ', ';

				$i++;
			}
		} else {
			echo '--';
		}
	}



	/** Register the additional columns for events, programmes & venues */
	function set_custom_edit_event_columns( $columns ) {
		unset( $columns['date'] );
		$columns['programme'] = __( 'Programme', 'concertpress' );
		$columns['venue']     = __( 'Venue', 'concertpress' );
		$columns['cp-date']   = __( 'Event Date', 'concertpress' );
		$columns['date']	  = __( 'Date Created', 'concertpress' );
		return $columns;
	}

	function set_custom_edit_programme_venue_columns( $columns ) {
		unset( $columns['date'] );
		$columns['event_id'] = __( 'Associated Event(s)', 'concertpress' );
		$columns['date']	 = __( 'Date Created', 'concertpress' );
		return $columns;
	}


	/** Make the event date coloumn sortable */
	function event_column_register_sortable( $columns ) {
		$columns['cp-date'] = 'cp-date';
		unset( $columns['date'] );

		return $columns;
	}


	/** Add a filter to order by event date */
	function event_column_orderby( $vars ) {

		if ( ! isset( $vars['orderby'] ) )
			return $vars;

		if ( 'cp-date' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => '_start_date',
				'orderby'  => 'meta_value_num',
			) );
		}

		return $vars;
	}


	/** Add a filter to initially display events by their date */
	function pre_get_posts_filter( $query ) {

		if ( ! isset( $query->query_vars['post_type'] ) )
			return $query;

		switch ( $query->query_vars['post_type'] ) {

			case 'event' :

				if ( !isset( $query->query_vars['orderby'] ) ) {
					$query->set( 'meta_key', '_start_date' );
					$query->set( 'orderby', 'meta_value_num' );
					$query->set( 'order', 'asc' );
				}

				break;

		}
		return $query;
	}


	/** Add a class name to the edit screens for JS + CSS stuff */
	function body_class_names( $classes ) {
		global $post;
		if ( isset( $post->post_type ) && in_array( $post->post_type, array( 'event', 'programme', 'venue' ) ) )
			$classes .= 'edit-' . $post->post_type;

		return $classes;
	}



	/** Register meta boxes for events */
	function register_event_meta_boxes() {
		add_meta_box(
			'convertpress-event-date-meta',
			__( 'Date', 'concertpress' ),
			array( $this, 'print_event_date_meta' ),
			'event',
			'side',
			'high'
		);
		add_meta_box(
			'convertpress-event-programme-meta',
			__( 'Programme', 'concertpress' ),
			array( $this, 'print_event_programme_meta' ),
			'event',
			'normal',
			'high'
		);
		add_meta_box(
			'convertpress-event-venue-meta',
			__( 'Venue', 'concertpress' ),
			array( $this, 'print_event_venue_meta' ),
			'event',
			'normal',
			'high'
		);
	}


	/** Register meta boxes for venues */
	function register_venue_meta_boxes() {
		add_meta_box(
			'convertpress-venue-meta',
			__( 'Venue Details', 'concertpress' ),
			array( $this, 'print_venue_meta' ),
			'venue',
			'normal',
			'high'
		);
	}



	/** Helper function to print select lists of venues & programmes */
	private function _print_select_lists( $type = 'programme' ) {
		$args = array(
			'post_type'      => $type,
			'posts_per_page' => -1,
		);
		$things = new WP_Query( $args );
		$value  = get_post_meta( $this->current_post, "_$type", true );
		$name   = "concertpress[$type][select_id]";

		if ( $things->have_posts() ) : ?>
			<label class="concertpress-label" for="<?php $name ?>"><?php printf( __( 'Select a %s', 'concertpress' ), $type ) ?></label>
				<select class="concertpress-select" name="<?php echo $name ?>" id="<?php echo $name ?>">
					<option value="0"><?php _e( '-- select --', 'concertpress' ); ?></option>
						<?php while( $things->have_posts() ) : ?>
							<?php $things->the_post(); ?>
							<option value="<?php esc_attr( the_ID() ) ?>" <?php selected( $value, get_the_ID() ) ?>><?php the_title() ?></option>
						<?php endwhile; ?>
					</select>
		<?php endif; wp_reset_query();
	}


	/** Add event date meta box */
	function print_event_date_meta() {
		global $post;
		$this->current_post = $post->ID;

		wp_nonce_field( 'concertpress_nonce', 'concertpress_add_event' );

		$saved_hour = $saved_min = $date = $end_date = false;
		$start_date = get_post_meta( $post->ID, '_start_date', true );
		$end_date   = get_post_meta( $post->ID, '_end_date', true );
		$checked    = '';

		if ( $start_date ) {
			$date       = date( 'Y-m-d', $start_date );
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
				<input type="text" class="start-date concertpress-datepicker" id="concertpress[date][start]" name="concertpress[date][start]" value="<?php echo $date ?>" />

			<?php _ex( '@', 'Date and time separator', 'concertpress' ) ?>

			<select class="concertpress-time" id="concertpress[date][time][hour]" name="concertpress[date][time][hour]">
				<?php $hour = 0; ?>
				<option value="none"> -- </option>
				<?php while ( $hour < 24 ) : ?>
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

			<br>

			<label for="concertpress[date][multi]">
				<input <?php echo $checked ?> class="concertpress-multi-date-trigger" type="checkbox" id="concertpress[date][multi]" name="concertpress[date][multi]">
				&nbsp;<?php _e( 'This is a multi-date event', 'concertpress' ) ?>
			</label>

			<div class="end-date">

				<label class="concertpress-label" for="concertpress[date][end]"><?php _e( 'End date', 'concertpress' ) ?></label>
					<input type="text" class="concertpress-datepicker" id="concertpress[date][end]" name="concertpress[date][end]" value="<?php echo $end_date ?>"/>

			</div>

		</div>
	<?php
	}


	/** Add event programme meta box */
	function print_event_programme_meta() {
		$this->_print_select_lists();

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

			<label for="concertpress[programme][title]"><?php _e( 'Programme title', 'concertpress' ) ?></label>
				<input type="text" name="concertpress[programme][title]" id="concertpress[programme][title]" />

			<label for="concertpress[programme][content]"><?php _e( 'Programme details', 'concertpress' ) ?></label>
			<?php
				wp_editor( '', 'programme-content', $editor_settings );
				do_action( 'concertpress_add_programme' );
			?>

		</div>
		<?php
	}


	/** Add event venue meta box */
	function print_event_venue_meta() {
		$this->_print_select_lists( 'venue' );
		?>

		<p class="new-trigger">
			<a href="#" class="button button-secondary"><?php _e( 'Add a new venue', 'concertpress' ) ?></a>
		</p>

		<div class="new new-venue">

			<label for="concertpress[venue][name]"><?php _e( 'Name', 'concertpress' ) ?></label>
				<input type="text" id="concertpress[venue][name]" name="concertpress[venue][name]" />

			<label for="concertpress[venue][url]"><?php _e( 'URL', 'concertpress' ) ?></label>
				<input type="url" id="concertpress[venue][url]" name="concertpress[venue][url]" />

			<label for="concertpress[venue][address]"><?php _e( 'Address', 'concertpress' ) ?></label>
				<input type="text" id="concertpress[venue][address]" name="concertpress[venue][address]" />

			<?php do_action( 'concertpress_add_venue' ); ?>

		</div>

		<?php
	}


	/** Add venue meta box */
	function print_venue_meta() {
		global $post;

		$url     = get_post_meta( $post->ID, '_url', true );
		$address = get_post_meta( $post->ID, '_address', true );
		?>
		<div class="new new-venue">

			<label for="concertpress[venue][name]"><?php _e( 'Name', 'concertpress' ) ?></label>
				<input type="text" id="concertpress[venue][name]" name="concertpress[venue][name]" value="<?php echo esc_attr( get_the_title() ) ?>" />

			<label for="concertpress[venue][url]"><?php _e( 'URL', 'concertpress' ) ?></label>
				<input type="url" id="concertpress[venue][url]" name="concertpress[venue][url]" value="<?php echo esc_attr( $url ) ?>" />

			<label for="concertpress[venue][address]"><?php _e( 'Address', 'concertpress' ) ?></label>
				<input type="text" id="concertpress[venue][address]" name="concertpress[venue][address]" value="<?php echo esc_attr( $address ) ?>"  />

		</div>

		<?php
	}


	/** Save the extra meta for events + venues */
	function save_event_meta( $post_id, $post ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		if ( is_int( wp_is_post_autosave( $post_id ) ) )
			return;

		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;

		if ( ! isset( $_REQUEST['concertpress'] ) )
			return $post_id;

		switch ( $post->post_type ) {

			case 'event' :


				// Remove the save post hook to avoid an infinite loop
				remove_action( 'save_post', array( $this, 'save_event_meta' ) );

				if ( ! check_admin_referer( 'concertpress_nonce', 'concertpress_add_event' ) )
					return;

				$cp        = $_REQUEST['concertpress'];
				$date      = $cp['date'];
				$programme = $cp['programme'];
				$venue     = $cp['venue'];
				$errors    = array();


				/** Date */
				if ( '' == $date['start'] ) {

					$errors[] = __( 'Please add a date for the event.', 'concertpress' );

				} else {

					$hour = ( isset( $date['time']['hour'] ) ) ? (int) $date['time']['hour'] : 00;
					$min  = ( isset( $date['time']['min'] ) )  ? (int) $date['time']['min']  : 00;

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
								$errors[] = __( 'The end date should be later than the start date.', 'concertpress' );
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



				/** Programme */
				if ( $programme['select_id'] != 0 ) {

					update_post_meta( $post_id, '_programme', $programme['select_id'] );

				} else {

					if ( '' == $programme['content'] && '' == $programme['title'] ) {

						$errors[] = __( 'Please select or add a new programme.', 'concertpress' );

					} else {

						if ( '' == $programme['title'] )
							$errors[] = __( 'Please add a programme title.', 'concertpress' );

						$new_programme = array(
							'post_content' => $programme['content'],
							'post_title'   => $programme['title'],
							'post_status'  => 'publish',
							'post_type'    => 'programme',
						);

						$new_programme_id = wp_insert_post( $new_programme );
						update_post_meta( $post_id, '_programme', $new_programme_id );

					}
				}



				/** Venue */
				if ( $venue['select_id'] != 0 ) {

					update_post_meta( $post_id, '_venue', $venue['select_id'] );

				} else {

					if ( '' == $venue['name'] ) {

						$errors[] = __( 'Please select or add a new venue.', 'concertpress' );

					} else {

						$new_venue = array(
							'post_content' => '',
							'post_title'   => $venue['name'],
							'post_status'  => 'publish',
							'post_type'    => 'venue',
						);

						$new_venue_id = wp_insert_post( $new_venue );
						update_post_meta( $post_id, '_venue', $new_venue_id );
						update_post_meta( $new_venue_id, '_url', esc_url_raw( $venue['url'] ) );
						update_post_meta( $new_venue_id, '_address', sanitize_text_field( $venue['address'] ) );

					}

				}


				if ( empty( $errors ) ) {
					if ( get_post_meta( $post->ID, '_errors', true ) )
						delete_post_meta( $post->ID, '_errors' );
				} else {
					update_post_meta( $post->ID, '_errors', $errors );
				}

				// Reattach the save post hook
				add_action( 'save_post', array( $this, 'save_event_meta' ) );

				break;

		}

	}


}


global $concertpress;
$concertpress = new ConcertPress;
