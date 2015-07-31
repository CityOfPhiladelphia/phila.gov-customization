<?php
 // Instantiate new class
 $phila_publication_load = new PhilaPublication();

 class PhilaPublication {

  public function __construct(){
    add_action( 'save_post_publication', array( $this, 'save_publication_meta'), 10, 3 );
    add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_media_js') );
    add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_css'), 11 );
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

  public function save_publication_meta( $post_id, $post, $update ) {

    // Check permissions
    if ( !current_user_can( 'edit_page', $post_id ) )
      return;

    //don't run on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return;

    //make sure the metabox plugin exists
    if (function_exists('rwmb_meta')) {
      $publications = rwmb_meta( 'phila_publications', $args = array('type' => 'file_advanced'));

    }
      var_dump($publications);
    //ensure we have documents attached
    if(!$publications == null) {

      foreach ($publications as $document){
        $current_pdf = $document[ID];

        $categories = get_the_category($post_id);

         //on save, set the current page category
        foreach ($categories as $category){
          $category_ids[] = $category->cat_ID;
          wp_set_object_terms( $current_pdf, $category_ids, 'category', false );
          wp_add_object_terms( $current_pdf, $category_ids, 'category' );
        }

        $types =  get_the_terms( $post_id, 'publication_type' );

        foreach ($types as $type){
          $type_ids[] = $type->term_id;
          wp_set_object_terms( $current_pdf, $type_ids, 'publication_type', false );
        }
      }
      $list = get_post_meta($post_id, 'phila_publications');
      var_dump($list);
    }
  }

  public function load_admin_media_js(){
    	wp_enqueue_script( 'admin-publication-script', plugins_url( '../js/admin-media.js' , __FILE__, array('jQuery') ) );
  }
  public function load_admin_css(){
    wp_register_style( 'phila_admin_css', plugins_url( '../css/admin.css', __FILE__));
    wp_enqueue_style( 'phila_admin_css' );
  }


}//PhilaPublication

 function phila_wp_ajax_attach_file(){
			$post_id  = isset( $_REQUEST['post_id'] ) ? intval( $_REQUEST['post_id'] ) : 0;
			$field_id       = isset( $_POST['field_id'] ) ? $_POST['field_id'] : 0;
			$attachment_ids = isset( $_POST['attachment_ids'] ) ? (array) $_POST['attachment_ids'] : array();
			//check_ajax_referer( "rwmb-attach-file_{$field_id}" );
      var_dump($attachment_ids);
			foreach ( $attachment_ids as $attachment_id )
			{
				delete_post_meta( $post_id, $field_id, $attachment_id, false );
        var_dump($attachment_id);
			}
			//wp_send_json_success();
		}
 // /phila_wp_ajax_attach_file();
