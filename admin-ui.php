<?php
/**
 * Contains :
 * Change admin lables
    * post > information Page
    * page > Department Page
 * redorder admin menu
 * custom post types
    * Services
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 * 
 * @package phila.gov-customization
 */


/**
 * Change admin lables
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 * 
 * @package phila.gov-customization
 */

if (!class_exists('PhilaGovCustomAdminLabels')){
    class PhilaGovCustomAdminLabels {
        function change_admin_post_label(){
            global $menu;
            global $submenu;
                
            $menu[5][0] = 'Information Page';
            $submenu['edit.php'][5][0] = 'Information Page';
            $submenu['edit.php'][10][0] = 'Add Information Page';
                
            echo '';
            //remove comments, this is here b/c we are using the add_action hook
            remove_menu_page('edit-comments.php');
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
            
            //also, register post_tag and cats
            register_taxonomy_for_object_type('post_tag', 'page');
            register_taxonomy_for_object_type('category', 'page'); 
        
        }
        
    }//end PhilaGovCustomAdminLables
}

//create instance of PhilaGovCustomAdminLabels
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

/**
 *  Create Custom Post Types
 *
 * Additional custom post types can be defined here
 * http://codex.wordpress.org/Post_Types
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 *
 *
 */

if (!class_exists('PhilaGovCustomPostTypes')){
    class PhilaGovCustomPostTypes{
        function create_services_post_type() {
          register_post_type( 'service_post',
            array(
                'labels' => array(
                    'name' => __( 'Service Page' ),
                    'singular_name' => __( 'Service Page' ),
                    'add_new'   => __('Add Service Page'),
                    'all_items'   => __('All Service Pages'),
                    'add_new_item' => __('Add Service Page'),
                    'edit_item'   => __('Edit Service Page'),
                    'view_item'   => __('View Service Page'),
                    'search_items'   => __('Search Service Pages'),
                    'not_found'   => __('Service Page Not Found'),
                    'not_found_in_trash'   => __('Service Page not found in trash'),
              ),
                'taxonomies' => array('category', 'post_tag'),
                'public' => true,
                'has_archive' => true,
                'menu_position' => 5,
                'menu_icon' => 'dashicons-groups',
                'hierarchical' => false,
                'rewrite' => array(
                    'slug' => 'service',
                ),
            )
          );
        }
     
        //TODO Check if this is actually refreshing permalinks
        function rewrite_flush() {
            create_services_post_type();
            flush_rewrite_rules();
        }
        
    }//end class

}


if (class_exists("PhilaGovCustomPostTypes")){
    $custom_post_types = new PhilaGovCustomPostTypes();
}

if (isset($custom_post_types)){
    //actions
    add_action( 'init', array($custom_post_types, 'create_services_post_type'));
    register_activation_hook( __FILE__, array($custom_post_types, 'rewrite_flush') );
}

/**
 * Reorder Menu Items
 *
 * 
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/custom_menu_order
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 *
 *
 */
if (!class_exists('PhilaGovCustomMenuOrdering')){
    class PhilaGovCustomMenuOrdering {
        function custom_menu_order($menu_ord) {
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
    }
}

if (class_exists("PhilaGovCustomMenuOrdering")){
    $change_menu_order = new PhilaGovCustomMenuOrdering();
}
if (isset($change_menu_order)){
    add_filter('custom_menu_order', array($change_menu_order, 'custom_menu_order')); // Activate custom_menu_order
    add_filter('menu_order', array($change_menu_order, 'custom_menu_order'));
}