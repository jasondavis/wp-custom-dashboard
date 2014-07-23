<?php
/**
 * @package   wp_custom_dashboard
 * @version   1.0
 * @author    Charlie MERLAND <charlie@caercam.org>
 * @license   Public Domain
 * @link      http://www.caercam.org/
 * @copyright 2014 Charlie MERLAND
 * 
 * Plugin Name: WordPress Custom Dashboard
 * Plugin URI: https://github.com/Askelon/wp-custom-dashboard
 * Description: Build a custom dashboard for your plugins
 * Author: Charlie MERLAND
 * Author URI: http://www.caercam.org/
 * License: Public Domain
 * License URI: http://unlicense.org/
 * Version: 1.0
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) )
	die;

define( 'WPCD_VERSION', '1.0' );
define( 'WPCD_NAME',    'WordPress Custom Dashboard' );
define( 'WPCD_SLUG',    'wpcd' );
define( 'WPCD_URL',     plugins_url( basename( __DIR__ ) ) );
define( 'WPCD_PATH',    plugin_dir_path( __FILE__ ) );

require( WPCD_PATH . 'classes/class-wpcd.php' );
require( WPCD_PATH . 'classes/class-dashboard.php' );
require( WPCD_PATH . 'classes/class-stats-widget.php' );
require( WPCD_PATH . 'classes/class-latest-posts-widget.php' );

if ( class_exists( 'wp_custom_dashboard' ) )
	$GLOBALS['wpcd'] = new wp_custom_dashboard();
