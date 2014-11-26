<?php
/**
 * Information/Department Finder
 * lives at /browse
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 * 
 * @package phila.gov-customization
 */

function browse_add_rewrite_rules() {
    add_rewrite_rule(
        '([^/]*)/([^/]*)/(browse)/?' // get browse
        'index.php?pagename=$matches[1]',
        'top'
    );
}
add_action( 'init', 'browse_add_rewrite_rules' );