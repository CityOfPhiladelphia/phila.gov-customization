<?php
/**
 * Functions for Information/Department Finder
 * lives at /browse
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 * 
 * @package phila.gov-customization
 */

function currentURL(){
    $page_URL = 'http';
        $page_URL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $page_URL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
         } else {
            $page_URL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
         }
    $the_URL = explode('/', rtrim($page_URL, '/'));
    return $the_URL;
}

function display_topic_list() {
    $get_URL = currentURL();
    $url_last_item =  end($get_URL);
    $topic = count($get_URL);
    $parent_topic = '';
    if ($url_last_item === 'browse' ) {
        get_parent_topics();
    //use array of URL length to determine if we are parent or child
    }elseif ($topic === 5) {
        $parent_topic === end($get_URL);
        get_children_topics();
    }elseif ($topic === 6){
        active_children_topics();
    }
}

function display_browse_breadcrumbs(){
    $get_URL = currentURL();
    $url_last_item =  end($get_URL);
    $url = currentURL();
    end($url);
    $second_to_last = prev($url);
    $topic = count($get_URL);
    if ($topic === 5) {
        echo '<li><a href="/browse/' . $url_last_item. '">' .  replace_dashes($url_last_item) . '</a></li>';
    }elseif ($topic === 6){
        echo '<li><a href="/browse/' . $second_to_last . '">' .  replace_dashes($second_to_last) . '</a></li>';
        echo '<li><a href="/browse/' . $second_to_last . '/' . $url_last_item . '">'.  replace_dashes($url_last_item) . '</a></li>';
    }
}



function display_filtered_pages() {
    $get_URL = currentURL();
    $url_last_item =  end($get_URL);
    $topic = count($get_URL);
    $parent_topic = '';
    if ($topic === 6){
        get_template_part( 'content', 'finder' );    
        echo '<ul class="list">';
          while ( have_posts() ) : the_post();
              get_template_part( 'content', 'list' );
          endwhile; 
        echo '</ul>';
                        
    }
}

function get_parent_topics(){
    $get_URL = currentURL();
    $url_last_item =  end($get_URL);
    $topic = count($get_URL);
        $parent_terms = get_terms('topics', array('orderby' => 'slug', 'parent' => 0));
    echo '<nav><ul>';
        foreach($parent_terms as $key => $parent_term) {
            
            echo '<li class="parent ' . $parent_term->slug . '"><a href="/browse/' . $parent_term->slug . '"><h3>' . $parent_term->name . '</h3>'; 
                      echo '<span>' . $parent_term->description . '</span></a></li>';
        }
    echo '</ul></nav>';
}   

//Utility function, not currently in use
function list_parent_topics(){
    $parent_terms = get_terms('topics', array('orderby' => 'slug', 'parent' => 0));
    foreach($parent_terms as $key => $parent_term) {

        echo '<li class="parent ' . $parent_term->slug . '"><a href="/browse/' . $parent_term->slug . '"><h3>' . $parent_term->name . '</h3>'; 
                  echo '<span>' . $parent_term->description . '</span></a></li>';
    }
}
function get_children_topics(){
    $url = currentURL();
    $last_term = end($url);
    $parent_term = $last_term;
    
    echo '<h2 class="current-topic">' . replace_dashes($parent_term) . '</h2>';
    
    $child_terms = get_terms('topics', array('orderby' => 'slug', 'search' => $parent_term));
    
    $current_term = get_term_by( 'slug', $parent_term, 'topics' );

        //then set the args for wp_list_categories
         $args = array(
            'child_of' => $current_term->term_id,
            'taxonomy' => $current_term->taxonomy,
            'hide_empty' => 0,
            'hierarchical' => true,
            'depth'  => 1,
            'title_li' => ''
            );
         wp_list_categories( $args );
}

function active_children_topics(){
    $url = currentURL();
    end($url);
    $parent_term = prev($url);
    
    echo '<h2 class="current-topic">' . replace_dashes($parent_term) . '</h2>';
    
    $child_terms = get_terms('topics', array('orderby' => 'slug', 'search' => $parent_term));
    
    $current_term = get_term_by( 'slug', $parent_term, 'topics' );
    
    $args = array(
        'child_of' => $current_term->term_id,
        'taxonomy' => $current_term->taxonomy,
        'hide_empty' => 0,
        'hierarchical' => true,
        'depth'  => 1,
        'title_li' => ''
        );
     wp_list_categories( $args );

}

function replace_dashes($string) {
    $string = str_replace("-", " ", $string);
    $string = str_replace("and", "&", $string);
    return $string;
}

//utility function, not currently in use
function get_topics(){
    $parent_terms = get_terms('topics', array('orderby' => 'slug', 'parent' => 0));
        foreach($parent_terms as $key => $parent_term) {
            
            echo '<li><h3><a href="/browse/' . $parent_term->slug . '">' . $parent_term->name . '</h3>'; 
            echo '<p>' . $parent_term->description . '</p></a></li>';
            
            $child_terms = get_terms('topics', array('orderby' => 'slug', 'parent' => $parent_term->term_id));
    
            if($child_terms) {
                echo '<ul>';
                    foreach($child_terms as $key => $child_term) {
                        echo '<li><a href="/browse/'. $parent_term->slug . '/' . $child_term->slug . '">' . $child_term->name . ' (child)</a></li>';
                        //echo $child_term->description; 
                    }
                echo '</ul>';
            }
    }
}