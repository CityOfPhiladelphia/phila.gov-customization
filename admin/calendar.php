<?php
/**
  * @since 0.8.0
  * Functionality for forcing  our categories on the events_categories taxonomy
  * problem: if cat or name slug changes, a new cat is created.
*/
//TODO: find a way to recognize that a category has been deleted from Phila.gov Categories and update this this accordingly

function duplicate_the_categories(){

  $args = array(
  	'child_of'                 => 0,
  	'parent'                   => '',
  	'orderby'                  => 'name',
  	'order'                    => 'ASC',
  	'hide_empty'               => 0,
  	'hierarchical'             => 0,
  	'taxonomy'                 => 'category'
  );
  $categories = get_categories( $args );

  $taxes = array( 'events_categories' );
  $cal_args = array(
    'orderby'                  => 'name',
    'order'                    => 'ASC',
    'hide_empty'               => false
  );
  $calendar_categories = get_terms('events_categories', $cal_args);

  //1. try to update the term - if it doesn't work then we insert a new one.
  foreach ($categories as $category){
    wp_insert_term(
          $category->name, // the term
          'events_categories', // the taxonomy
          array(
            'alias_of' => $category->slug,
            'description'=> $category->category_description,
            'slug' => $category->slug,
            'parent'=> $category->category_parent
            )
          );
  }
  foreach ($calendar_categories as $calendar_category) {
    foreach ($categories as $category){

      if ($category->name == $calendar_category->name) {
        wp_update_term($calendar_category->term_id, 'events_categories', array(
          'description'=> $category->category_description,
          'slug' => $category->slug,
          'parent'=> $category->category_parent
        ));
      }
    }
  }
}
add_action( 'init', 'duplicate_the_categories' );


/**
* @since 0.8.0
*
* Shortcode for displaying calendar events on department homeage
*
* @package phila.gov-customization
*/

function upcoming_events_shortcode($atts) {
  global $post;

  $taxes = array( 'events_categories' );
  $cal_args = array(
    'orderby'                  => 'name',
    'order'                    => 'ASC',
    'hide_empty'               => false
  );
  $calendar_categories = get_terms('events_categories', $cal_args);

  $page_category = get_the_category();

  $current_cat_name = $page_category[0]->name;

    foreach ($calendar_categories as $calendar_category){
      $cal_cat_name = $calendar_category->name;
      if ($cal_cat_name == $current_cat_name){
         $term = $calendar_category->term_id;
      }
    }

    $cal_args = array( 'posts_per_page' => 3,
    'order'=> 'DESC',
    'orderby' => 'date',
    'post_type'  =>
    'ai1ec_event',
    'tax_query' => array(
  		array(
  			'taxonomy' => 'events_categories',
  			'field'    => 'term_id',
  			'terms'    => $term,
  		),
  	),

  );

  $calendar_loop = new WP_Query( $cal_args );

  $output = '';

  if( $calendar_loop->have_posts() ) {
    $post_counter = 0;


    while( $calendar_loop->have_posts() ) : $calendar_loop->the_post();
    $post_counter++;

    $link = get_permalink();

      $output .=  '<div class="large-8 columns">';

      $output .= '<div class="story s-box">';

        $output .= '<a href="' . get_permalink() .'">';
        $output .=   get_the_post_thumbnail( $post->ID );
        $output .= '</a>';

        $output .= '<a href="' . get_permalink().'">';
        $output .=  '<h3>' . get_the_title( $post->id ) . '</h3>';
        $output .= '</a>';
    
      $output .= '</div></div>';

      endwhile;
  }else {
    $output .= __('Please enter at least one event.', 'phila.gov');
  }

  wp_reset_postdata();
  return $output;

}

function register_calendar_shortcodes(){
   add_shortcode('upcoming-events', 'upcoming_events_shortcode');
}

add_action( 'init', 'register_calendar_shortcodes');
