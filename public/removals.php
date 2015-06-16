<?php
/**
 * Things we want to remove from WordPress
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 *
 * @package phila.gov-customization
 */

// Remove shortlink from head and HTTP headers
// https://wordpress.org/support/topic/unable-to-remove-wordpress-relshortlink
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('template_redirect', 'wp_shortlink_header', 11);

// Turn off xmlrpc
// https://wordpress.org/plugins/disable-xml-rpc/
add_action( 'xmlrpc_enabled', '__return_false' );

// Remove pingback from HTTP headers
// https://wordpress.org/support/topic/how-to-remove-x-pingback-httpwwwexamplecomxmlrpcphp
function remove_x_pingback($headers) {
    unset($headers['X-Pingback']);
    return $headers;
}
add_filter('wp_headers', 'remove_x_pingback');
