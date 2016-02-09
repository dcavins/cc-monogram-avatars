<?php
/**
 *
 * @package   CC Monogram Avatars
 * @author    David Cavins
 * @license   GPL-2.0+
 * @copyright 2014 CommmunityCommons.org
 *
 * @wordpress-plugin
 * Plugin Name:       CC Monogram Avatars
 * Description:       Replaces mystery man default avatars with monograms.
 * Version:           1.0.0
 * Author:            David Cavins
 * Text Domain:       cc-monogram-avatars
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * WordPress-Plugin-Boilerplate: v2.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/


/* Do our setup after BP is loaded, but before we create the group extension */
function cc_monogram_avatars_class_init() {

	// The main class
	require_once( plugin_dir_path( __FILE__ ) . 'public/class-cc-monogram-avatars.php' );

	add_action( 'bp_init', array( 'CC_Monogram_Avatars', 'get_instance' ), 11 );

}
add_action( 'bp_init', 'cc_monogram_avatars_class_init' );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */

/*
 * Helper function.
 * @return Fully-qualified URI to the root of the plugin.
 */
function cc_monogram_plugin_base_uri(){
    return plugin_dir_url( __FILE__ );
}

