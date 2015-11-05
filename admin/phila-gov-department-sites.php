<?php


add_action ('add_meta_boxes', 'determine_page_type', 1);

function determine_page_type() {
  global $post;
   if ( is_admin() ) {
    $post_id = isset($_GET['post']);

      $children = get_pages( array( 'child_of' => $post_id ) );

      if( (count( $children ) == 0 ) && ( $post->post_parent == 0 ) ){
        echo 'yeah';
        add_filter( 'rwmb_meta_boxes', 'phila_register_department_meta_boxes' );

    }
  }
}
function phila_register_department_meta_boxes( $meta_boxes ){
  $prefix = 'phila_';
  $meta_boxes[] = array(
    'id'       => 'departments',
    'title'    => 'Department Information',
    'pages'    => array( 'department_page' ),
    'context'  => 'normal',
    'priority' => 'high',

    'fields' => array(
      array(
        'name'  => 'Description',
        'desc'  => 'A short description of the department. Required.',
        'id'    => $prefix . 'dept_desc',
        'type'  => 'textarea',
        'class' => 'dept-description',
        'clone' => false,
      ),
      array(
        'name'  => 'External URL of Department',
        'desc'  => 'If the department does not live on this website, enter the location here. Eg. http://phila.gov/revenue/',
        'id'    => $prefix . 'dept_url',
        'type'  => 'URL',
        'class' => 'dept-url',
        'clone' => false,
      ),
    )
  );//External department link
  $meta_boxes[] = array(
    'id'       => 'department-homepage-cards',
    'title'    => 'Homepage Cards',
    'pages'    => array( 'department_page' ),
    'context'  => 'normal',
    'priority' => 'high',

    'fields' => array(
      array(
        'name'  => 'Name of card',
        'id'    => $prefix . 'card_title',
        'type'  => 'text',
        'class' => 'card-description',
        'required' => true
      ),
      array(
        'name'  => 'Image',
        'id'    => $prefix . 'card_image',
        'type'  => 'file_input',
        'class' => 'card-image',
        'required' => true
      ),
      array(
        'name'  => 'Content title',
        'id'    => $prefix . 'card_content_title',
        'type'  => 'text',
        'class' => 'card-content-text',
        'required' => true
      ),
      array(
        'name'  => 'Description',
        'id'    => $prefix . 'card_description',
        'type'  => 'textarea',
        'class' => 'card-description',
        'required' => true
      ),
      array(
        'name'  => 'Link',
        'id'    => $prefix . 'card_link',
        'type'  => 'url',
        'class' => 'card-url'
      ),
    )
  );

  return $meta_boxes;
}
