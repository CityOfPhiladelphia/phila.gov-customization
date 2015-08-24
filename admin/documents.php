<?php
 // Instantiate new class
 $phila_document_load = new PhilaDocument();

 class PhilaDocument {

  public function __construct(){
    add_action( 'save_post_document', array( $this, 'save_document_meta'), 10, 3 );

    add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_media_js') );

    add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_css'), 11 );

    add_filter( 'wp_default_editor', array( $this, 'set_default_editor' ) );

  }
 /**
  * Save attachment metadata when a document page is saved.
  *
  * @param int $post_id The post ID.
  * @param post $post The post object.
  * @param bool $update Whether this is an existing post being updated or not.
  * @uses get_the_category() https://developer.wordpress.org/reference/functions/get_the_category/
  * @uses wp_set_object_terms() https://codex.wordpress.org/Function_Reference/wp_set_object_terms
  */

  public function save_document_meta( $post_id, $post, $update ) {

    // Check permissions
    if ( !current_user_can( 'edit_page', $post_id ) )
      return;

    //don't run on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return;

    //make sure the metabox plugin exists
    if (function_exists('rwmb_meta')) {
      $documents = rwmb_meta( 'phila_documents', $args = array('type' => 'file_advanced'));

    }
    //ensure we have documents attached
    if(!$documents == null) {

      foreach ($documents as $document){
        $current_pdf = $document[ID];

        $categories = get_the_category($post_id);

         //on save, set the current page category
        foreach ($categories as $category){
          $category_ids[] = $category->cat_ID;
          wp_set_object_terms( $current_pdf, $category_ids, 'category', false );
          wp_add_object_terms( $current_pdf, $category_ids, 'category' );
        }

        $types =  get_the_terms( $post_id, 'document_type' );

        foreach ($types as $type){
          $type_ids[] = $type->term_id;
          wp_set_object_terms( $current_pdf, $type_ids, 'document_type', false );
        }
      }
      $list = get_post_meta($post_id, 'phila_documents');
    }
  }

  public function load_admin_media_js(){
  	wp_enqueue_script( 'admin-document-script', plugins_url( '../js/admin-media.js' , __FILE__, array('jQuery') ) );

    wp_enqueue_script( 'jquery-validation', plugins_url('../js/jquery.validate.min.js', __FILE__, array( 'admin-document-script') ) );


  }
  public function load_admin_css(){
    wp_register_style( 'phila_admin_css', plugins_url( '../css/admin.css', __FILE__));
    wp_enqueue_style( 'phila_admin_css' );
  }


  public function set_default_editor() {
      $r = 'tinymce';
      return $r;
  }


}//PhilaDocument
