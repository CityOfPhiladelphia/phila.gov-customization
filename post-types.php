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

if (!class_exists("PhilaGovCustomPostTypes")){
    class PhilaGovCustomPostTypes {
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
            $get_post = $wp_post_types['post'];
            $lables = $get_post -> labels;
            $lables -> name = 'Information';
            $lables -> singular_name = 'Information';
            $lables -> add_new = 'Add Information';
            $lables -> add_new_item = 'Add Information Page';
            $lables -> edit_item = 'Edit Information Page';
            $lables -> new_item = 'Information';
            $lables -> view_item = 'View Information Page';
            $lables -> search_items = 'Search Information Pages';
            $lables -> not_found = 'No Information Page Found';
            $lables -> not_found_in_trash = 'No Information Page found in Trash';
            $lables -> all_items = 'All Information Pages';
            $lables -> menu_name = 'Information Page';
            $lables -> name_admin_bar = 'Information';

        }
    }//end PhilaGovCustomTax
}

//create instance of PhilaGovCustomTax
if (class_exists("PhilaGovCustomPostTypes")){
    $admin_menu_lables = new PhilaGovCustomPostTypes();
}

if (isset($phila_gov_tax)){
    //WP actions
    add_action( 'admin_menu', array($admin_menu_lables, 'change_admin_post_label'));
    add_action( 'init', array($admin_menu_lables, 'change_admin_post_object'));
}