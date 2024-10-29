<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/CoolS2
 * @since             1.0.0
 * @package           Ar_Ad_Manager
 *
 * @wordpress-plugin
 * Plugin Name:       Advertising management - A.R
 * Plugin URI:        https://github.com/CoolS2/ar-ad-manager
 * Description:       Plugin to manage advertisements on your website. Beautifully, Easily and Professional.
 * Version:           1.0.5
 * Author:            A.R
 * Author URI:        https://github.com/CoolS2
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ar-ad-manager
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) exit;
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AR_AD_MANAGER_VERSION', '1.0.4' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ar-ad-manager-activator.php
 */
function ar_ad_manager_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ar-ad-manager-activator.php';
	Ar_Ad_Manager_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ar-ad-manager-deactivator.php
 */
function ar_ad_manager_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ar-ad-manager-deactivator.php';
	Ar_Ad_Manager_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'ar_ad_manager_activate' );
register_deactivation_hook( __FILE__, 'ar_ad_manager_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ar-ad-manager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
if ( ! function_exists('ar_ad_manager_run') ) {
    function ar_ad_manager_run() {
        $plugin = new Ar_Ad_Manager();
        $plugin->run();
    }

    ar_ad_manager_run();
}