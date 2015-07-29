<?php
 // Instantiate new class
 $phila_document_collection_load = new PhilaDocumentCollection();

 class PhilaDocumentCollection {

  public function __construct(){
       add_action( 'save_post_documents', array( $this, 'save_document_meta'), 10, 3 );
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
          //false will override any existing categories, set to true to add new
          wp_set_object_terms( $current_pdf, array($category->cat_ID), 'category', false );
        }
      }
    }
  }
}//PhilaDocumentCollection
