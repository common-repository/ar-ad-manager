<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/CoolS2/ar-ad-manager
 * @since      1.0.0
 *
 * @package    Ar_Ad_Manager
 * @subpackage Ar_Ad_Manager/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ar_Ad_Manager
 * @subpackage Ar_Ad_Manager/includes
 * @author     Aleksandrs Reidzans <aleksandrs.reidzans@gmail.com>
 */
class Ar_Ad_Manager_i18n
{
    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain(
            'ar-ad-manager',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}
