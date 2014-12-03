<?php
/**
 * Functions for Department Finder
 * lives at /browse
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 * 
 * @package phila.gov-customization
 */

/*

function topic_query($public_query_vars) {
    $topics = get_terms('topics', array('orderby' => 'slug', 'parent' => 0));
    $public_query_vars[] = property;
    return $public_query_vars;
}

add_filter('query_vars', 'topic_query');

function do_rewrite() {
    add_rewrite_rule('departments/([^/]+)/?$', 'index.php?pagename=departments&property=$matches[1]','top');
}

add_action('init', 'do_rewrite');
*/

    
function the_dept_description(){
        $dept_desc = rwmb_meta( 'phila_dept_desc', $args = array('type' => 'textarea'));

        if (!$dept_desc == ''){
            echo '<p>' . $dept_desc . '</p>';
    }
}
function get_department_topics(){
    $parent_terms = get_terms('topics', array('orderby' => 'slug', 'parent' => 0));
        foreach($parent_terms as $key => $parent_term) {
            
            echo '<li><h3><a href="?topics=' . $parent_term->slug . '" class="item-link">' . $parent_term->name . '</a></h3>'; 
    }
}
