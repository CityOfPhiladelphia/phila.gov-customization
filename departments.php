<?php
/**
 * Functions for Department Finder
 * lives at /browse
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 * 
 * @package phila.gov-customization
 */


function get_topics(){
    $parent_terms = get_terms('topics', array('orderby' => 'slug', 'parent' => 0));
        foreach($parent_terms as $key => $parent_term) {
            
            echo '<li><h3><a href="' . $parent_term->slug . '" class="item-link">' . $parent_term->name . '</a></h3>'; 
    }
}