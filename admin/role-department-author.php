<?php

/**
 * Add custom js to force category selection for Department Author roles
 *
 * @since   0.10.0
 */

add_action( 'plugins_loaded', 'department_author_only' );

function department_author_only(){
  if ( current_user_can( 'department_author' ) ){
    add_action( 'admin_enqueue_scripts', 'administration_admin_scripts' );
  }
}
function administration_admin_scripts() {
	wp_enqueue_script( 'admin-script', plugins_url( '../js/admin-department-author.js' , __FILE__ ) );
}
// define the custom capability name for protected content
define ('PHILA_ADMIN', 'see_all_content');

add_action('wp_loaded', 'phila_roles_and_capabilities');

function phila_roles_and_capabilities(){

  // add the custom capability to other qualified roles
  get_role('administrator')->add_cap(PHILA_ADMIN);
  get_role('editor')->add_cap(PHILA_ADMIN);
}
/*
add_action( 'admin_init', 'action_after_menu_locations_table', 10, 0 );
// define the after_menu_locations_table callback
function action_after_menu_locations_table()
{
  if ( current_user_can( PHILA_ADMIN ) ) {
    //they get full access
  }else {
    //otherwise load the JS that will hide all the things
    add_action( 'admin_enqueue_scripts', 'admin_scripts' );
  }
};
*/

// Instantiate new class
$phila_role_administration_load = new PhilaRoleAdministration();

class PhilaRoleAdministration {

    public function __construct(){
        //we can only run this role-check after plugins have been loaded
        add_action( 'plugins_loaded', array( $this, 'check_if_user_logged_in' ) );

        //remove our unwanted widgets
        add_action( 'widgets_init', array($this, 'remove_others_widgets'), 11 );
    }
    /**
  	 * Outputs all categories into an array w/ just slugs.
  	 *
  	 * @since 1.0
  	 * @return $cat_slugs array Returns an array of all categories.
  	 */
    public function get_categories(){
      $categories_args = array(
          'type'                     => 'post',
          'child_of'                 => 0,
          'parent'                   => '',
          'orderby'                  => 'name',
          'order'                    => 'ASC',
          'hide_empty'               => 1,
          'hierarchical'             => 0,
          'taxonomy'                 => 'category',
          'pad_counts'               => false
      );

      $categories = get_categories( $categories_args );

      $cat_slugs =[];

      //loop through and push slugs to $cat_slugs
      foreach($categories as $category){
        array_push($cat_slugs, $category->slug);
      }
      //add category slugs to their own array
      return $cat_slugs;
    }

  /**
   * Outputs all categories into an array w/ just slugs.
   *
   * @since 1.0
   * @return $cat_slugs array Returns an array of all categories.
   */
    public function check_if_user_logged_in(){

      if ( is_user_logged_in() ){

        $cat_slugs = $this->get_categories();

        //define current_user, we should only do this when we are logged in.
        $user = wp_get_current_user();
        $all_user_roles = $user->roles;

        //if there are matches, then you have a secondary role that should not be allowed to see other's menus, etc.
        $current_user_cat_assignment = array_intersect($all_user_roles, $cat_slugs);

        if(count($current_user_cat_assignment) > 0) {
          //TODO make this applicable to more than one sub category
          $current_category = get_category_by_slug( $current_user_cat_assignment[1] );
          $current_cat_slug = strval($current_category->slug);

          $sidebar_name = 'sidebar' . $current_cat_slug;

          //var_dump($current_category);

          add_action( 'admin_enqueue_scripts', 'administration_admin_scripts' );

          return $sidebar_name;
        }

      }
    }

  /**
	 * Outputs all categories into an array w/ just slugs.
	 *
	 * @since 1.0
   * @uses check_if_user_logged_in() Outputs all categories into an array w/ just slugs.
	  */
    public function remove_others_widgets(){
      //assign $sidebar_name to new var
    	$sidebar_name	= $this->check_if_user_logged_in();
           unregister_sidebar( $sidebar_name );
    }

}//end PhilaRoleAdministration
