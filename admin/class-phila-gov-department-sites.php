<?php

if ( class_exists("PhilaGovDepartmentSites" ) ){
  $phila_departent_sites = new PhilaGovDepartmentSites();
}

 class PhilaGovDepartmentSites {

  public function __construct(){

    add_action( 'admin_init', array( $this, 'determine_page_type' ) );

    add_action( 'init', array( $this, 'register_content_blocks_shortcode' ) );

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
      'title'    => 'Content Blocks',
      'pages'    => array( 'department_page' ),
      'context'  => 'normal',
      'priority' => 'high',
      'fields' => array(
        array(
         'id' => 'content_blocks',
         'type' => 'group',
         'clone'  => true,
         // List of sub-fields
         'fields' => array(
            array(
              'name'  => 'Block Heading',
              'id'    => $prefix . 'block_heading',
              'type'  => 'text',
              'class' => 'block-title',
              'required' => true,
              'desc'  => '20 character maxium'
            ),
            array(
              'name'  => 'Image',
              'id'    => $prefix . 'block_image',
              'type'  => 'file_input',
              'class' => 'block-image',
              'required' => true,
              'desc'  => 'Image should be no smaller than 274px by 180px.'
            ),
            array(
              'name'  => 'Title',
              'id'    => $prefix . 'block_content_title',
              'type'  => 'text',
              'class' => 'block-content-title',
              'required' => true,
              'desc'  => '70 character maxium.',
              'size'  => '60'
            ),
            array(
              'name'  => 'Summary',
              'id'    => $prefix . 'block_summary',
              'type'  => 'textarea',
              'class' => 'block-summary',
              'required' => true,
              'desc'  => '225 character maxium.'
            ),
            array(
              'name'  => 'Link to Content',
              'id'    => $prefix . 'block_link',
              'type'  => 'url',
              'class' => 'block-url',
              'desc'  => 'Enter a URL. E.g. http://alpha.phila.gov/oem',
              'size'  => '60',
            ),
          )
        )
      )
    );
    return $meta_boxes;
  }

  function content_blocks_shortcode( $atts ) {
    $a = shortcode_atts( array(
      'heading' => ''
    ), $atts );

    $content_blocks = rwmb_meta( 'content_blocks' );

    foreach( $content_blocks as $key => $array_value ) {

      $block_heading = isset( $array_value['phila_block_heading'] ) ? $array_value['phila_block_heading'] : '';

      //match on the heading param
      if ( strtolower( $a['heading'] ) == strtolower( $block_heading ) ){

        $output = '';
        $output .= '<h2 class="alternate divide title-offset">' . $block_heading . '</h2>';

        $block_link = isset( $array_value['phila_block_link'] ) ? $array_value['phila_block_link'] : '';
        if ($block_link == '') {
          $output .= '<div class="content-block no-link">';
          $block_image = isset( $array_value['phila_block_image'] ) ? $array_value['phila_block_image'] : '';
          $output .= '<img src="' . $block_image . '" alt="">';

          $block_title = isset( $array_value['phila_block_content_title'] ) ? $array_value['phila_block_content_title'] : '';
          $output .= '<h3>' . $block_title . '</h3>';

          $block_summary = isset( $array_value['phila_block_summary'] ) ? $array_value['phila_block_summary'] : '';
          $output .= '<p>' . $block_summary . '</p>';

          $output .= '</div>';
        }else{
          $output .= '<div class="content-block">';
          $output .= '<a href="' . $block_link . '">';

          $block_image = isset( $array_value['phila_block_image'] ) ? $array_value['phila_block_image'] : '';

          $output .= '<img src="' . $block_image . '" alt="">';

          $block_title = isset( $array_value['phila_block_content_title'] ) ? $array_value['phila_block_content_title'] : '';

          $output .= '<h3>' . $block_title . '</h3>';

          $block_summary = isset( $array_value['phila_block_summary'] ) ? $array_value['phila_block_summary'] : '';

          $output .= '<p>' . $block_summary . '</p>';
          $output .= '</a>';
          $output .= '</div>';
        }
        return $output;

        break;
      }
    }

  }
  function register_content_blocks_shortcode(){
     add_shortcode( 'content-block', array($this, 'content_blocks_shortcode') );
  }

}
