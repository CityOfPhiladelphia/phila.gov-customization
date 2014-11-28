<?php
/**
 * Information/Department Finder
 * lives at /browse
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 * 
 * @package phila.gov-customization
 */

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
function currentURL(){
    $pageURL = 'http';
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
         } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
         }
    
    echo $pageURL;
    //$parts = explode('/', rtrim($pageURL, '/'));
    //var_dump($parts);
}