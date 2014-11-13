<?php
/**
 * Add custom post type && change admin lables
 *
 * Additional custom post types can be defined here
 * http://codex.wordpress.org/Post_Types
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 * 
 * @package phila.gov-customization
 */

if (!class_exists("PhilaGovCustomAdminLabels")){
    class PhilaGovCustomAdminLabels {
        function change_admin_post_label(){
            global $menu;
            global $submenu;
                
            $menu[5][0] = 'Information Page';
            $submenu['edit.php'][5][0] = 'Information Page';
            $submenu['edit.php'][10][0] = 'Add Information Page';
                
            echo '';
        }
        
        function change_admin_post_object(){
            global $wp_post_types;
            //can't extract $lables in one go, so break it into 2 vars
            $get_post = $wp_post_types['post'];
            $lables = $get_post -> labels;
            $lables -> name = 'Information Page';
            $lables -> singular_name = 'Information';
            $lables -> add_new = 'Add Information Page';
            $lables -> add_new_item = 'Add Information Page';
            $lables -> edit_item = 'Edit Information Page';
            $lables -> new_item = 'Information';
            $lables -> view_item = 'View Information Page';
            $lables -> search_items = 'Search Information Pages';
            $lables -> not_found = 'No Information Page Found';
            $lables -> not_found_in_trash = 'No Information Page found in Trash';
            $lables -> all_items = 'All Information Pages';
            $lables -> menu_name = 'Information Page';
            $lables -> name_admin_bar = 'Information Page';
        }
        
        function change_admin_page_label(){
            global $menu;
            global $submenu;
                
            $menu[20][0] = 'Department Page';
            $submenu['edit.php?post_type=page'][5][0] = 'Department Page';
            $submenu['edit.php?post_type=page'][10][0] = 'Add Department Page';
                
            echo '';
        }
        
        function change_admin_page_object(){
            global $wp_post_types;
            //can't extract $lables in one go, so break it into 2 vars
            $get_page = $wp_post_types['page'];
            $lables = $get_page -> labels;
            $lables -> name = 'Department Page';
            $lables -> singular_name = 'Department';
            $lables -> add_new = 'Add Department Page';
            $lables -> add_new_item = 'Add Department Page';
            $lables -> edit_item = 'Edit Department Page';
            $lables -> new_item = 'Department';
            $lables -> view_item = 'View Department Page';
            $lables -> search_items = 'Search Department Pages';
            $lables -> not_found = 'No Department Page Found';
            $lables -> not_found_in_trash = 'No Department Page found in Trash';
            $lables -> all_items = 'All Department Pages';
            $lables -> menu_name = 'Department Page';
            $lables -> name_admin_bar = 'Department Page';
        }
    }//end PhilaGovCustomAdminLables
}

//create instance of PhilaGovCustomTax
if (class_exists("PhilaGovCustomAdminLabels")){
    $admin_menu_lables = new PhilaGovCustomAdminLabels();
}

if (isset($admin_menu_lables)){
    //WP actions
    add_action( 'init', array($admin_menu_lables, 'change_admin_post_object'));
    add_action( 'admin_menu', array($admin_menu_lables, 'change_admin_post_label'));
    
    add_action( 'init', array($admin_menu_lables, 'change_admin_page_object'));
    add_action( 'admin_menu', array($admin_menu_lables, 'change_admin_page_label'));
    
}