<?php
/**
 * Contains :
 * Change admin labels
    * post = information post
    * page = phila.gov page
 * redorder admin menu
 * custom post types
    * Departments - department_page
    * Services - service_post
    * News - news_post
    * Alerts - site_wide_alert
    * Content Collection - collection_page

 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 *
 * @package phila.gov-customization
 */


/**
 * Change admin labels
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


            // Add Menus as a Department Site submenu
            add_submenu_page( 'edit.php?post_type=department_page', 'Nav Menu', 'Nav Menu', 'edit_posts', 'nav-menus.php');

            // Add Menus as a Department Site submenu
            add_submenu_page( 'edit.php?post_type=department_page', 'Sidebar', 'Sidebar', 'edit_posts', 'widgets.php');

            //remove comments, this is here b/c we are using the add_action hook
            remove_menu_page('edit-comments.php');
        }

        function change_admin_post_object(){
            global $wp_post_types;
            //can't extract $labels in one go, so break it into 2 vars
            $get_post = $wp_post_types['post'];

            $labels = $get_post -> labels;
            $labels -> name = 'Information Page';
            $labels -> singular_name = 'Information';
            $labels -> add_new = 'Add Information Page';
            $labels -> add_new_item = 'Add Information Page';
            $labels -> edit_item = 'Edit Information Page';
            $labels -> new_item = 'Information';
            $labels -> view_item = 'View Information Page';
            $labels -> search_items = 'Search Information Pages';
            $labels -> not_found = 'No Information Page Found';
            $labels -> not_found_in_trash = 'No Information Page found in Trash';
            $labels -> all_items = 'All Information Pages';
            $labels -> menu_name = 'Information Page';
            $labels -> name_admin_bar = 'Information Page';
        }

        function change_admin_page_label(){
            global $menu;
            global $submenu;

            $menu[20][0] = 'Phila.gov Page';
            $submenu['edit.php?post_type=page'][5][0] = 'Phila.gov Page';
            $submenu['edit.php?post_type=page'][10][0] = 'Add Phila.gov Page';

            echo '';
        }

        function change_admin_page_object(){
            global $wp_post_types;
            //can't extract $labels in one go, so break it into 2 vars
            $get_page = $wp_post_types['page'];
            $labels = $get_page -> labels;
            $labels -> name = 'Phila.gov Page';
            $labels -> singular_name = 'Phila.gov';
            $labels -> add_new = 'Add Phila.gov Page';
            $labels -> add_new_item = 'Add Phila.gov Page';
            $labels -> edit_item = 'Edit Phila.gov Page';
            $labels -> new_item = 'Phila.gov';
            $labels -> view_item = 'View Phila.gov Page';
            $labels -> search_items = 'Search Phila.gov Pages';
            $labels -> not_found = 'No Phila.gov Page Found';
            $labels -> not_found_in_trash = 'No Phila.gov Page found in Trash';
            $labels -> all_items = 'All Phila.gov Pages';
            $labels -> menu_name = 'Phila.gov Page';
            $labels -> name_admin_bar = 'Phila.gov Page';

            //also, register post_tag and cats
            register_taxonomy_for_object_type('post_tag', 'page');
            register_taxonomy_for_object_type('category', 'page');

        }


    }//end PhilaGovCustomAdminlabels
}

//create instance of PhilaGovCustomAdminLabels
if (class_exists("PhilaGovCustomAdminLabels")){
    $admin_menu_labels = new PhilaGovCustomAdminLabels();
}

if (isset($admin_menu_labels)){
    //WP actions
    add_action( 'init', array($admin_menu_labels, 'change_admin_post_object'));
    add_action( 'admin_menu', array($admin_menu_labels, 'change_admin_post_label'));

    add_action( 'init', array($admin_menu_labels, 'change_admin_page_object'));
    add_action( 'admin_menu', array($admin_menu_labels, 'change_admin_page_label'));

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
                'supports' => array( 'title', 'editor', 'front-end-editor', 'revisions'),
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

        function create_departments_page_type() {
          register_post_type( 'department_page',
            array(
                'labels' => array(
                    'name' => __( 'Department Site' ),
                    'singular_name' => __( 'Department Site' ),
                    'add_new'   => __('Add a Page'),
                    'all_items'   => __('All Pages'),
                    'add_new_item' => __('Add a Department Page'),
                    'edit_item'   => __('Edit Department Page'),
                    'view_item'   => __('View Department Page'),
                    'search_items'   => __('Search Department Pages'),
                    'not_found'   => __('No Pages Found'),
                    'not_found_in_trash'   => __('Department Page not found in trash'),
                    'parent_item_colon' => '',
              ),
                'taxonomies' => array('category'),
                'supports' => array( 'title', 'editor', 'page-attributes', 'thumbnail', 'revisions'),
                'public' => true,
                'has_archive' => true,
                'show_in_nav_menus' => true,
                'menu_position' => 5,
                'menu_icon' => 'dashicons-groups',
                'hierarchical' => true,
                'query_var' => true,
                'rewrite' => array(
                    'slug' => 'departments',
                ),
            )
          );
        }

         function create_news_post_type() {
          register_post_type( 'news_post',
            array(
                'labels' => array(
                    'name' => __( 'News' ),
                    'singular_name' => __( 'News' ),
                    'add_new'   => __('Add News'),
                    'all_items'   => __('All News'),
                    'add_new_item' => __('Add News'),
                    'edit_item'   => __('Edit News'),
                    'view_item'   => __('View News Item'),
                    'search_items'   => __('Search News'),
                    'not_found'   => __('News Not Found'),
                    'not_found_in_trash'   => __('News not found in trash'),
              ),
                'taxonomies' => array('category', 'topics'),
                'public' => true,
                'has_archive' => true,
                'menu_position' => 6,
                'menu_icon' => 'dashicons-media-document',
                'hierarchical' => false,
                'supports'  => array('title','editor','thumbnail', 'revisions'),
                'rewrite' => array(
                    'slug' => 'news'
                ),
            )
          );
        }
        function create_site_wide_alert() {
          register_post_type( 'site_wide_alert',
          array(
            'labels' => array(
              'name' => __( 'Site Alerts' ),
              'singular_name' => __( 'Alert' ),
              'add_new'   => __('Add Alert'),
              'all_items'   => __('All Alerts'),
              'add_new_item' => __('Add Alerts'),
              'edit_item'   => __('Edit Alerts'),
              'view_item'   => __('View Alerts'),
              'search_items'   => __('Search Alerts'),
              'not_found'   => __('Alert Not Found'),
              'not_found_in_trash'   => __('Alert not found in trash'),
            ),
            'taxonomies' => array(),
            'exclude_from_search' => true,
            'public' => true,
            'has_archive' => false,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-megaphone',
            'hierarchical' => false,
            'rewrite' => array(
              'slug' => 'alerts',
            ),
            )
          );
        }
        function create_collectiom_post_type() {
          register_post_type( 'collection_page',
            array(
                'labels' => array(
                    'name' => __( 'Collection Page' ),
                    'singular_name' => __( 'Collection Page' ),
                    'add_new'   => __('Add Collection Page'),
                    'all_items'   => __('All Collection Pages'),
                    'add_new_item' => __('Add Collection Page'),
                    'edit_item'   => __('Edit Collection Page'),
                    'view_item'   => __('View Collection Page'),
                    'search_items'   => __('Search Collection Pages'),
                    'not_found'   => __('Collection Page Not Found'),
                    'not_found_in_trash'   => __('Collection Page not found in trash'),
              ),
                'taxonomies' => array('category'),
                'supports' => array( 'title', 'editor', 'front-end-editor', 'page-attributes', 'revisions'),
                'public' => true,
                'has_archive' => true,
                'menu_position' => 10,
                'menu_icon' => 'dashicons-exerpt-view',
                'hierarchical' => true,
                'rewrite' => array(
                    'slug' => '',
                ),
            )
          );
        }


    }//end class

}


if (class_exists("PhilaGovCustomPostTypes")){
    $custom_post_types = new PhilaGovCustomPostTypes();
}

if (isset($custom_post_types)){
    //actions
    add_action( 'init', array($custom_post_types, 'create_services_post_type'));
    add_action( 'init', array($custom_post_types, 'create_news_post_type'));
    add_action( 'init', array($custom_post_types, 'create_departments_page_type'));
    add_action( 'init', array($custom_post_types, 'create_site_wide_alert'));
    add_action( 'init', array($custom_post_types, 'create_collectiom_post_type'));
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


/**
* Add scripts only to site_wide_alert posts
*
*/
function enqueue_alert_scripts($hook) {
  global $post;
  if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
    if ( 'site_wide_alert' === $post->post_type ) {
        wp_enqueue_script( 'alerts-ui', plugin_dir_url( __FILE__ ) . 'js/scripts.js', array('jquery'));

    }
  }
}
add_action( 'admin_enqueue_scripts', 'enqueue_alert_scripts' );

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
