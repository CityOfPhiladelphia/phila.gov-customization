<?php
/**
 * Save post metadata when a post is saved.
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */
function save_document_meta( $post_id, $post, $update ) {
  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

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
           wp_set_object_terms( $current_pdf, array($category->cat_ID), 'category', false );
         }
      }
    }

}
add_action( 'save_post_documents', 'save_document_meta', 10, 3 );
