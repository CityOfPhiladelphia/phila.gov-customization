<?php

if ( class_exists("Phila_Item_Meta_Desc" ) ){
  $phila_meta_desc = new Phila_Item_Meta_Desc();
}

class Phila_Item_Meta_Desc {


  public function __construct(){

    add_filter( 'rwmb_meta_boxes', array($this, 'phila_register_item_desc_meta_boxes' ), 1000, 1 );

  }

  function phila_register_item_desc_meta_boxes( $meta_boxes ){

    $prefix = 'phila_';

    $meta_boxes[] = array(
      'id'  => 'item_description',
      'title' => 'Short Description',
      'context'  => 'after_title',
      //'priority' => 'high',

      //TODO: replace this with a function that pulls the post types we need. It had been set up this way, but after a WP update, get_post_types was not returning CPTs. A quick fix needed to be put in place, and this is it.
      'post_types' => array(
        'phila_post', 'news_post',  'department_page', 'service_page', 'document', 'press_release', 'page', 'post', 'programs', 'event_spotlight', 'guides'
      ),

      'fields' => array(
        array(
          'id'   => $prefix . 'meta_desc',
          'limit' => 140,
          'type' => 'textarea',
          'desc'  => 'Enter a short description of this content. This description will appear in lists that include this item, search results, and social media link previews. 140 character maximum.',
          'required'  => true
        ),
      ),
    );

    $meta_boxes[] = array(
      'title' => 'Translation Settings',
      'context'  => 'side',
      'post_types' => array(
        'department_page', 'service_page', 'document', 'page', 'programs', 'event_spotlight', 'guides'
      ),
      'fields' => array(
        array(
          'id' => $prefix . 'send_to_translation',
          'name' => 'Ready to send to translation?',
          'type' => 'switch',
          'style' => 'rounded',
          'std' => 1,
          'on_label' => 'Yes',
          'off_label' => 'No',
        ),
      ),
    );

    return $meta_boxes;

  }
}
