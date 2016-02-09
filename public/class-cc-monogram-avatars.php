<?php
/**
 * @package   CC Monogram Avatars
 * @author    CARES staff
 * @license   GPL-2.0+
 * @copyright 2014 CommmunityCommons.org
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 *
 * @package CC Monogram Avatars
 * @author  David Cavins
 */
class CC_Monogram_Avatars {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'cc-monogram-avatars';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		// Tell BP to not use gravatars
		add_filter( 'bp_core_fetch_avatar_no_grav', array( $this, 'filter_bp_core_fetch_avatar_no_grav' ) );

		// Replace the mystery man with our on-the-fly avatars.
		add_filter( 'bp_core_fetch_avatar', array( $this, 'replace_default_avatar' ), 10, 9 );

		// Load public-facing style sheet and JavaScript.
		// On the front end.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );
		// In the admin area.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles_scripts() {
		// Styles
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Tell BP to not use gravatars.
	 *
	 * @since    1.0.0
	 */
	public function filter_bp_core_fetch_avatar_no_grav( $fetch ) {
		return true;
	}

	/**
	 * Replace the mystery man with our on-the-fly avatars.
	 *
	 * @since    1.0.0
	 */
	public function replace_default_avatar( $avatar, $params, $item_id, $avatar_dir, $html_css_id, $html_width, $html_height, $avatar_folder_url, $avatar_folder_dir ){

	    // Do nothing if this is not the default.
	    if ( strrpos( $avatar, 'bp-core/images/mystery-man') === false ) {
			return $avatar;
	    }

	    // Replace the default mystery man group avatar.
	    if ( $params['object'] == 'group' ) {
	      $html_class = ' class="' . sanitize_html_class( $params['class'] ) . ' ' . sanitize_html_class( $params['object'] . '-' . $params['item_id'] . '-avatar' ) . ' ' . sanitize_html_class( 'avatar-' . $params['width'] ) . ' photo"';
	      $avatar = '<img src="' . cc_monogram_plugin_base_uri() . 'public/img/cc-default-group-avatar.png"' . $html_css_id . $html_class . $html_width . $html_height . ' alt="default group photo"/>';
	    }

	    // User avatar
	    if ( $params['object'] == 'user' ) {
	    	// Select the letters to use for the monogram.
	    	$display_name = bp_core_get_user_displayname( $params['item_id'] );
			$words = preg_split("/\s+/", ucwords( $display_name ) );
			$monogram = substr( current( $words ), 0, 1 );
			if ( count( $words ) > 1 ) {
				$monogram .= substr( end( $words ), 0, 1 );
			}

			// Fun. Randomize the background based on the user's display name length
			// Not currently using a background image.
			$offset = '';
			/*
			$x_position = strlen( $display_name ) % 7;
			$y_position = strlen( $display_name ) % 4;
			if ( $params['width'] > 50 ) {
				$range = 200 - $params['width'];
			} else {
				$range = 100 - $params['width'];
			}
			$offset = 'background-position:' . floor( $range / 14 * $x_position ) . 'px ' . floor( $range / 2 * $y_position ) . 'px;';
			$offset = 'style="' . $offset . '"';
			*/

			// Choose a background color and scale for the background image.
			$letter = substr( $monogram, 0, 1 );
			// We're not currently using a background image, so scale isn't needed.
			$scale = '';
			if ( strcasecmp( $letter, 'F') <= 0 ) {
				$color = 'ccyellow';
				// $scale = 'scale-50';
			} else if ( strcasecmp( $letter, 'L') <= 0  ) {
				$color = 'ccblue';
				// $scale = 'scale-80';
			} else if ( strcasecmp( $letter, 'R') <= 0  ) {
				$color = 'ccred';
				// $scale = 'scale-65';
			} else {
				$color = 'ccgreen';
				// $scale = 'scale-35';
			}



			$avatar = '<span class="avatar monogram monogram-' . $params['width'] . ' ' . $color . ' ' . $scale . '"' . $offset . '>' . $monogram . '</span>';
	    }

	    return $avatar;
	}

}
