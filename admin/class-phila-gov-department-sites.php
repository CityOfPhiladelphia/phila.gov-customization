<?php

if ( class_exists("PhilaGovDepartmentSites" ) ){
  $phila_departent_sites = new PhilaGovDepartmentSites();
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
          'desc'  => 'If the department does not live on this website, enter the location here. Eg. http://phila.gov/health/',
          'id'    => $prefix . 'dept_url',
          'type'  => 'URL',
          'class' => 'dept-url',
          'clone' => false,
        ),
      )
    );//External department link
    $meta_boxes[] = array(
      'id'       => 'department-content-block',
      'title'    => 'Content Blocks',
      'pages'    => array( 'department_page' ),
      'context'  => 'normal',
      'priority' => 'high',
      'fields' => array(
        array(
         'id' => 'content-blocks',
         'type' => 'group',
         'clone'  => true,
         // List of sub-fields
         'fields' => array(
            array(
              'name'  => 'Block Heading',
              'id'    => $prefix . 'block_heading',
              'type'  => 'text',
              'class' => 'block-title',
              'required' => true
            ),
            array(
              'name'  => 'Image',
              'id'    => $prefix . 'block_image',
              'type'  => 'file_input',
              'class' => 'block-image',
              'required' => true
            ),
            array(
              'name'  => 'Title',
              'id'    => $prefix . 'block_content_title',
              'type'  => 'text',
              'class' => 'block-content-title',
              'required' => true
            ),
            array(
              'name'  => 'Summary',
              'id'    => $prefix . 'block_summary',
              'type'  => 'textarea',
              'class' => 'block-summary',
              'required' => true
            ),
            array(
              'name'  => 'Link to Content',
              'id'    => $prefix . 'block_link',
              'type'  => 'url',
              'class' => 'block-url'
            ),
          )
        )
      )
    );
    return $meta_boxes;
  }

}
