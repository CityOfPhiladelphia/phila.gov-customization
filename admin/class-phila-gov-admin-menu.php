<?php
/**
 * Change admin labels
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 *
 * @package phila.gov-customization
 * @since 0.17.6
 */

if ( class_exists( "PhilaGovAdminMenu" ) ){
  $admin_menu_labels = new PhilaGovAdminMenu();
}

class PhilaGovAdminMenu {

  public function __construct(){

    add_action( 'admin_menu', array($this, 'change_admin_post_label' ) );

    add_action( 'admin_menu', array($this, 'change_admin_information_page_label' ) );

    add_filter( 'custom_menu_order', array($this, 'admin_menu_order' ) );
    add_filter( 'menu_order', array($this, 'admin_menu_order' ) );

 }

  function admin_menu_order($menu_ord) {

    if (!$menu_ord) return true;

    return array(
        'index.php', // Dashboard
        'separator1', // First separator
        'edit.php', // Posts
        'edit.php?post_type=service_post', // Links
        'edit.php?post_type=page', // Pages
        'upload.php', // Media
    );
  }

  function change_admin_post_label(){

    // Add Menus as a Department Site submenu
    add_submenu_page( 'edit.php?post_type=department_page', 'Sidebar', 'Sidebar', 'edit_posts', 'widgets.php');

    //remove comments, this is here b/c we are using the add_action hook
    remove_menu_page('edit-comments.php');
  }

  function change_admin_information_page_label(){

    global $menu;
    global $submenu;
    //Rename Pages
    $menu[20][0] = 'Information Page';
  }

  function change_admin_page_object(){

      global $wp_post_types;
      //can't extract $labels in one go, so break it into 2 vars
      $get_page = $wp_post_types['page'];
      $labels = $get_page -> labels;
      $labels -> name = 'Information Page';
      $labels -> singular_name = 'Information Page';
      $labels -> add_new = 'Add Information Page';
      $labels -> add_new_item = 'Add Information Page';
      $labels -> edit_item = 'Edit Information Page';
      $labels -> new_item = 'Add Information Page';
      $labels -> view_item = 'View Information Page';
      $labels -> search_items = 'Search Information Pages';
      $labels -> not_found = 'No Information Page Found';
      $labels -> not_found_in_trash = 'No Information Page found in Trash';
      $labels -> all_items = 'All Information Pages';
      $labels -> menu_name = 'Information Page';
      $labels -> name_admin_bar = 'Information Page';

      //also, register cats
      register_taxonomy_for_object_type('category', 'page');

  }
}
