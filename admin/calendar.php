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
    //don't do this if $calendar_categories is not found.
    //ie. the calendar plugin is not active.
    if ( !is_wp_error( $calendar_categories ) ) {
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
    }//end if
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
