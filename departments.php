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

function eg_add_rewrite_rules() {
    global $wp_rewrite;
 
    $new_rules = array(
        'departments/topics/(.+)/?$' => 'index.php?post_type=department_page&topics=' . $wp_rewrite->preg_index(1)
    );
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action( 'generate_rewrite_rules', 'eg_add_rewrite_rules' );

function the_dept_description(){
        $dept_desc = rwmb_meta( 'phila_dept_desc', $args = array('type' => 'textarea'));

        if (!$dept_desc == ''){
            echo '<p>' . $dept_desc . '</p>';
    }
}
function get_department_topics(){
    $parent_terms = get_terms('topics', array('orderby' => 'slug', 'parent' => 0));
        foreach($parent_terms as $key => $parent_term) {
            
            echo '<li class="parent ' . $parent_term->slug . '"><h3><a href="/departments/topics/' . $parent_term->slug . '" class="item-link">' . $parent_term->name . '</a></h3>'; 
    }
}
