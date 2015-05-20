<?php
/**
 * Functions for Information Finder
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


function display_browse_breadcrumbs(){
    $get_URL = currentURL();
    $url_last_item =  end($get_URL);
    $url = currentURL();
    end($url);
    $second_to_last = prev($url);
    $topic = count($get_URL);
    if ($topic === 5) {
        echo '<li>'. replace_dashes($url_last_item) .'</li>';
    }elseif ($topic === 6){
        echo '<li><a href="/browse/' . $second_to_last . '">' .  replace_dashes($second_to_last) . '</a></li>';
        echo '<li>'. replace_dashes($url_last_item). '</li>';
    }
}

function display_current_selected_topic(){
    $get_URL = currentURL();
    $url_last_item =  end($get_URL);
    $url = currentURL();
    end($url);
    $second_to_last = prev($url);
    $topic = count($get_URL);
    if ($topic === 6){
        echo replace_dashes($url_last_item);
    }
}


function display_filtered_pages() {
    $get_URL = currentURL();
    $url_last_item =  end($get_URL);
    $topic = count($get_URL);
    $parent_topic = '';
    if ($topic === 6){
        get_template_part( 'partials/content', 'finder' );
        $filtered_list = new WP_Query(array('orderby' => 'title', 'order'=> 'asc', 'post_type' => array('post','service_post'),
            'tax_query' => array(
               array(
                    'taxonomy' => 'topics',
                    'field'    => 'slug',
                    'terms'    => array( $url_last_item ),
                    )
                )
          ));
        echo '<ul class="list">';
          while ( $filtered_list->have_posts() ) : $filtered_list->the_post();
              get_template_part( 'partials/content', 'list' );
          endwhile;
        echo '</ul>';

    }
}

function replace_dashes($string) {
    $string = str_replace("-", " ", $string);
   // $string = str_replace(" and ", " & ", $string);
    return $string;
}

function get_topics(){
    $parent_terms = get_terms('topics', array('orderby' => 'slug', 'parent' => 0));
        foreach($parent_terms as $key => $parent_term) {

            echo '<li class="parent ' . $parent_term->slug .'"><a href="/browse/' . $parent_term->slug . '"><h3>' . $parent_term->name . '</h3>';
            echo '<p class="parent-description">' . $parent_term->description . '</p></a></li>';

            $child_terms = get_terms('topics', array('orderby' => 'slug', 'parent' => $parent_term->term_id));

            if($child_terms) {
                echo '<ul class="subtopics">';
                    foreach($child_terms as $key => $child_term) {
                        echo '<a href="/browse/'. $parent_term->slug . '/' . $child_term->slug . '"><li class="h4 child ' . $parent_term->slug . ' '. $child_term->slug . '">' . $child_term->name;
                        echo '<p class="child-description ' . $parent_term->slug . ' '. $child_term->slug . '">' . $child_term->description . '</p></li></a>';
                    }
                echo '</ul>';
            }
    }
}
function get_parent_topics(){
    $args = array(
        'orderby' => 'name',
        'fields'=> 'all',
        'parent' => 0
   );
    $terms = get_terms( 'topics', $args );
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
             echo '<ul>';
             foreach ( $terms as $term ) {
                 echo '<a href="/browse/' . $term->slug . '"><li class="h4 '. $term->slug  . '">' . $term->name . '</li></a>';
             }
             echo '</ul>';
             echo '</nav>';
        }
}

/*Utility function for Master Topics List */
function get_master_topics(){
  $parent_terms = get_terms('topics', array('orderby' => 'slug', 'parent' => 0, 'hide_empty' => 0));
  echo '<ul>';
  foreach($parent_terms as $key => $parent_term) {



    echo '<li><h3>' . $parent_term->name . '</h3>';
    echo  $parent_term->description;

    $child_terms = get_terms('topics', array('orderby' => 'slug', 'parent' => $parent_term->term_id, 'hide_empty' => 0));

    if($child_terms) {
      echo '<ul class="subtopics">';
      foreach($child_terms as $key => $child_term) {
        echo '<li><h4>' . $child_term->name . '</h4>';
        echo  $child_term->description . '</li></li>';
      }

    }
    echo '</ul>';
  }
}
