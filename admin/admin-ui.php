<?php
/**
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 *
 * @package phila.gov-customization
 */

/**
 * Hook into Restrict Categories plugin and allow custom post types to be filtered through posts()
 *
 * @since 0.5.9
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization, https://wordpress.org/plugins/restrict-categories/
 *
 * @package phila.gov-customization
 */

add_action( 'admin_init', 'restrict_categories_custom_loader', 1 );

function restrict_categories_custom_loader() {

  class RestrictCategoriesCustom extends RestrictCategories {
    public function  __construct() {

      if ( is_admin() ) {
         $post_type = get_post_types();

         foreach ($post_type as $post) {
           add_action( 'admin_init', array( &$this, 'posts' ) );
          }
       }
    }
  }
    new RestrictCategoriesCustom();
}

/**
 * Allow draft pages to be in the "Parent" attribute dropdown
 *
 * @since   0.8.5
 */

add_filter('page_attributes_dropdown_pages_args', 'phila_allow_draft_dropdown_pages_args', 1, 1);

function phila_allow_draft_dropdown_pages_args($dropdown_args) {

    $dropdown_args['post_status'] = array('publish','draft');

    return $dropdown_args;
}
