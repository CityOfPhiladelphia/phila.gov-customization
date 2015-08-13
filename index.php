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
 * @since             0.5.6
 * @package           phila.gov-customization
 *
 * @wordpress-plugin
 * Plugin Name:       Phila.gov Customization
 * Plugin URI:        https://github.com/CityOfPhiladelphia/phila.gov-customization
 * Description:       Custom Wordpress functionality, custom post types, custom taxonomies, etc.
 * Version:           0.12.0
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
require $dir. '/admin/calendar.php';
require $dir. '/admin/class-department-author.php';
require $dir. '/admin/class-department-author-media.php';
require $dir. '/admin/define-roles.php';
require $dir. '/admin/documents.php';
require $dir. '/admin/meta-boxes.php';
require $dir. '/admin/taxonomies.php';

require $dir. '/public/browse.php';
require $dir. '/public/calendar-display.php';
require $dir. '/public/content-collection.php';
require $dir. '/public/departments.php';
require $dir. '/public/news.php';
require $dir. '/public/removals.php';
