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

function display_upcoming_department_events( $atts ) {
	global $ai1ec_registry;

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


	$content         = '<div class="event-box">';
	$time            = $ai1ec_registry->get( 'date.system' );
	// Get localized time
	$timestamp       = $time->current_time();
	// Set $limit to the specified categories/tags
	$limit           = array(
		//
		// this is demo data - please use your own filters
		//
      'cat_ids' => array($term)
  );
	$events_per_page = 3;
	$paged           = 0;
	$event_results   = $ai1ec_registry->get( 'model.search' )
		->get_events_relative_to(
			$timestamp,
			$events_per_page,
			$paged,
			$limit
		);
	$dates = $ai1ec_registry->get(
			'view.calendar.view.agenda',
			$ai1ec_registry->get( 'http.request.parser' )
		)->get_agenda_like_date_array( $event_results['events'] );
	foreach ( $dates as $date ) {
		foreach ( $date['events']['allday'] as $instance ) {
			$post_title   = $instance->get( 'post' )->post_title;
			$post_name    = $instance->get( 'post' )->post_name;
			$post_content = $instance->get( 'post' )->post_content;
      $post_venue = $instance->get( 'address' );
      $post_start_date = $instance->get( 'start' );
      $post_end_date = $instance->get( 'end' );
      $start_format_date = new DateTime($post_start_date);
      $end_format_date = new DateTime($post_end_date);
      $instance_id  = $instance->get( 'instance_id' );

      $content .=
      '<div class="event-row">
        <div class="date-time">'
        . $start_format_date->format('l, F j, Y') . '<br>'
        .  __('All Day Event', 'phila.gov') . '</div>'
        . '<div class="event-title"><a href="/event/'.$post_name . '">'
        . $post_title.'</a></div>'
        . '<div class="vcard"><div class="street-address">' . $post_venue
        . '</div></div></div>';
		}
		foreach ( $date['events']['notallday'] as $instance ) {

				$post_title   = $instance->get( 'post' )->post_title;
				$post_name    = $instance->get( 'post' )->post_name;
				$post_content = $instance->get( 'post' )->post_content;
        $post_venue = $instance->get( 'address' );
        $post_start_date = $instance->get( 'start' );
        $post_end_date = $instance->get( 'end' );
        $start_format_date = new DateTime($post_start_date);
        $end_format_date = new DateTime($post_end_date);
        $instance_id  = $instance->get( 'instance_id' );


				$content .=
        '<div class="event-row">
					<div class="date-time">'
          . $start_format_date->format('l, F j, Y') . '<br>'
          . $start_format_date->format('g:i A') . ' - '
          . $end_format_date->format('g:i A') .'</div>'
          . '<div class="event-title"><a href="/event/'.$post_name . '">'
          . $post_title.'</a></div>'
          . '<div class="vcard"><div class="street-address">' . $post_venue
          . '</div></div></div>';
		}
	}
	$content .= '</div>';
	return $content;
}
add_shortcode( 'upcoming-events', 'display_upcoming_department_events' );
