<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://contentlocalized.com
 * @since             1.1.0
 * @package           Translation_Pro
 *
 * @wordpress-plugin
 * Plugin Name:       Translation.Pro
 * Plugin URI:        https://www.contentlocalized.com/translation-pro
 * Description:       Our winning combo: local translators + professional writers!
 * Version:           1.0.0
 * Author:            ContentLocalized
 * Author URI:        https://contentlocalized.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       contentlocalized
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
define( 'CLTP_TRANSLATIONPRO_PLUGIN_VERSION', '1.0.0' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-translation-pro-activator.php
 */
function activate_cltr_translation_pro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-translation-pro-activator.php';
	\Contentlocalized\CLTP_TranslationPro_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-translation-pro-deactivator.php
 */
function deactivate_cltr_translation_pro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-translation-pro-deactivator.php';
	\Contentlocalized\CLTP_TranslationPro_Deactivator ::deactivate();
}

register_activation_hook( __FILE__, 'activate_cltr_translation_pro' );
register_deactivation_hook( __FILE__, 'deactivate_cltr_translation_pro' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-translation-pro.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cltp_translation_pro() {

	$plugin = new \Contentlocalized\CLTP_TranslationPro();
	$plugin->run();

}

run_cltp_translation_pro();
