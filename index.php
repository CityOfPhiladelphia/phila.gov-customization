<?php 	
/**
 * Plugin Name: Phila.gov Customization
 * Plugin URI: https://github.com/CityOfPhiladelphia/phila.gov-customization
 * Description: Custom Wordpress functionality, custom post types, custom taxonomies, etc.
 * Version: 0.0.1
 * Author: Karissa Demi
 * Author URI: http://karissademi.com 
 * 
 * @package phila.gov-customization
 */

$dir = plugin_dir_path( __FILE__ );

require $dir.'/taxonomies.php';
require $dir.'/admin-ui.php';
require $dir. '/browse.php';
require $dir. '/departments.php';