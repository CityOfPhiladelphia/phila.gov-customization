<?php

if ( class_exists("PhilaGovDepartmentSites" ) ){
  $phila_document_load = new PhilaGovDepartmentSites();
}

 class PhilaGovDepartmentSites {

  public function __construct(){

    add_action( 'admin_init', array( $this, 'determine_page_type' ) );

    if ( $this->determine_page_type() ){
      add_filter( 'rwmb_meta_boxes', array($this, 'phila_register_department_meta_boxes' ) );
    }


  }

  function determine_page_type() {
    global $pagenow;
     if ( is_admin() && 'post.php' == $pagenow ) {
        $post = get_post( $_GET['post'] );
      	$post_id = isset( $_GET['post'] ) ? $_GET['post'] : ( isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : false );

        $children = get_pages( array( 'child_of' => $post_id ) );

        if( ( count( $children ) == 0 ) && ( $post->post_parent == 0 ) ){
          return true;
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
    /*
    $meta_boxes[] = array(
      'id'       => 'department-content-highlights',
      'title'    => 'Homepage Highlights',
      'pages'    => array( 'department_page' ),
      'context'  => 'normal',
      'priority' => 'high',
      'fields' => array(
        array(
         'id' => 'homepage-highlights',
         'type' => 'group',
         'clone'  => true,
         // List of sub-fields
         'fields' => array(
            array(
              'name'  => 'Name of highlight',
              'id'    => $prefix . 'highlight_title',
              'type'  => 'text',
              'class' => 'highlight-description',
              'required' => true
            ),
            array(
              'name'  => 'Image',
              'id'    => $prefix . 'highlight_image',
              'type'  => 'file_input',
              'class' => 'highlight-image',
              'required' => true
            ),
            array(
              'name'  => 'Title',
              'id'    => $prefix . 'highlight_content_title',
              'type'  => 'text',
              'class' => 'highlight-content-text',
              'required' => true
            ),
            array(
              'name'  => 'Description',
              'id'    => $prefix . 'highlight_description',
              'type'  => 'textarea',
              'class' => 'highlight-description',
              'required' => true
            ),
            array(
              'name'  => 'Link',
              'id'    => $prefix . 'highlight_link',
              'type'  => 'url',
              'class' => 'highlight-url'
            ),
          )
        )
      )
    );
    */
    return $meta_boxes;
  }

}
