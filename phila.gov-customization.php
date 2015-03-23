<?php

/**
 * The plugin  file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://phila.gov
 * @since             0.3.3
 * @package           phila.gov-customization
 *
 * @wordpress-plugin
 * Plugin Name:       phila.gov-customization
 * Plugin URI:        https://github.com/CityOfPhiladelphia/phila.gov-customization
 * Description:       Custom Wordpress functionality, custom post types, custom taxonomies, etc.
 * Version:           0.3.3
 * Author:            City of Philadelphia
 * Author URI:        http://phila.gov
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       phila.gov-customization
 * Domain Path:       /languages
 */

// Direct access?  Get out.
if ( ! defined( 'ABSPATH' ) ) exit;

$dir = plugin_dir_path( __FILE__ );
require $dir. '/admin/admin-ui.php';
require $dir. '/admin/alerts.php';
require $dir. '/admin/taxonomies.php';

require $dir. '/meta-boxes.php';
require $dir. '/browse.php';
require $dir. '/departments.php';
require $dir. '/news.php';
