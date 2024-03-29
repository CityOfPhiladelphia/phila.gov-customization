<?php
/**
 * This class defines a custom "Unit" field type for Meta Box class.
 *
 * @package Meta Box
 * @see http://metabox.io/?post_type=docs&p=390
 */

if ( class_exists( 'RWMB_Field' ) ) {

  class RWMB_Unit_Field extends RWMB_Checkbox_List_Field {

    public function __construct(){

      add_action( 'init', array( $this, 'is_edit_page' ), 1 );

    }

      /**
       * Gets the post status.
       * TODO: Move into separate class
      */
      public static function is_edit_page( $new_edit = null ){
        global $pagenow;

        if (!is_admin()) return false;

        if( $new_edit === 'edit' )
          return in_array( $pagenow, array( 'post.php' ) );
        elseif($new_edit === 'new')
          return in_array( $pagenow, array( 'post-new.php' ) );
        else //check for either new or edit
          return in_array( $pagenow, array( 'post.php', 'post-new.php') );
      }

    public static function normalize( $field ) {

      global $post;
      global $pagenow;

      $post_id = false;
  
      if ( $pagenow =='edit.php' ) return $field; 

      $post_status = RWMB_Unit_Field::is_edit_page('new');
      

      if ( $post_status && !is_null($field) ){
        $field = parent::normalize( $field );
        return $field;
      }


      if ('post.php' === $pagenow &&  isset( $_GET['post'] ) ) {
        $post_id = intval( $_GET['post'] );
      } elseif ( isset( $_POST['post_ID'] ) ) {
        $post_id = intval( $_POST['post_ID'] );
      }else{
        return $field;
      }

      $categories = (array) get_the_category($post_id);

      if (empty($categories)) return $field;

      foreach ( (array) get_the_category($post->ID) as $cat ) {

        if ( empty( $cat->slug ) )
          continue;

        $options = array();

        foreach ( $categories as $category ) {
          if ( empty($category->slug ) ) {
            continue;
          }
          $term_id = get_queried_object_id();

          // get data out of term meta field
          $units = rwmb_meta( 'department_units', array( 'object_type' => 'term' ), $category->term_id, false) ;

          if (!empty($units)) {
            foreach($units as $unit) {
              $options[urlencode($unit['name'])] = $category->name . ' - ' . $unit['name'];
            }
          }
        }


        $field['options'] = $options;
      }
      
      if (!is_null($field)) {
        $field = parent::normalize( $field );
        return $field;
      }
    }
  }
}
?>