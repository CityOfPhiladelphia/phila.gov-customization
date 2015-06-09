<?php
/**
  * @since 0.8.0
  * Functionality for forcing  our categories on the events_categories taxonomy
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

  $all_cats = array();
  foreach ($categories as $category) {
    $init =  intval($category->term_id);
    array_push ($all_cats, $init);
  }

  $all_calendar_cats = array();
  foreach ($calendar_categories as $calendar_category) {
    $cal_init =  intval($calendar_category->term_id);
    $sub_one = $cal_init-1;
    array_push ($all_calendar_cats, $sub_one);
  }

  //this gives us the value of all intersecting categories outputting the native wp category name
  $results = array_intersect($all_cats, $all_calendar_cats);

    foreach($results as $result) {
      //add one to give us the calendar_category to change
      $term_to_update = $result+1;

      $category_to_update_from =  get_term_by( 'id', $result, 'category' );
      unset($result); //required to trim last element

      //update the term
      wp_update_term($term_to_update, 'events_categories', array(
        'name' => $category_to_update_from->name,
        'description'=> $category_to_update_from->description,
        'slug' => $category_to_update_from->slug
      ));
    }
  //the normal thing: copy the category to the calendar category list
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
}
add_action( 'init', 'duplicate_the_categories' );


/**
* @since 0.8.0
*
* Shortcode for displaying calendar events on department homepage
* Most of this came from: https://gist.github.com/lukaspawlik/045dbd5b517a9eb1cf95
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
