<?php
/**
 * Add custom js to force category selection for Department Author roles
 *
 * @since   0.11.0
 */

add_action( 'plugins_loaded', 'department_author_only' );

function department_author_only(){
  if ( ! current_user_can( PHILA_ADMIN ) ){
    add_action( 'admin_enqueue_scripts', 'administration_admin_scripts' );
  }
}

function administration_admin_scripts() {
  wp_enqueue_script( 'admin-script', plugins_url( '../js/admin-department-author.js' , __FILE__ ) );
  wp_register_style( 'admin-department-author', plugins_url( '../css/admin-department-author.css' , __FILE__  ) );
  wp_enqueue_style( 'admin-department-author' );
}

/**
 * Define our custom role. If a user is not PHILA_ADMIN, then they get things hidden.
 *
 * @since   0.11.0
 */
// define the custom capability name for protected content
define ( 'PHILA_ADMIN', 'phila_see_all_content' );

add_action( 'wp_loaded', 'phila_roles_and_capabilities' );

function phila_roles_and_capabilities(){

  // add the custom capability to other qualified roles
  get_role( 'administrator' )->add_cap( PHILA_ADMIN );
  get_role( 'editor' )->add_cap( PHILA_ADMIN );

}
