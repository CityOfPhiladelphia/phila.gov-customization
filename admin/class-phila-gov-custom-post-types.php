<?php
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
if ( class_exists("PhilaGovCustomPostTypes" ) ){
  $custom_post_types = new PhilaGovCustomPostTypes();
}

class PhilaGovCustomPostTypes{

  public function __construct(){
    add_action( 'init', array( $this, 'create_services_post_type' ) );

    add_action( 'init', array( $this, 'create_news_post_type' ) );

    add_action( 'init', array( $this, 'create_departments_page_type' ) );

    add_action( 'init', array( $this, 'create_site_wide_alert' ) );

    add_action( 'init', array( $this, 'create_document_post_type' ) );

    add_action( 'init', array( $this, 'create_notification_post_type' ) );

    register_activation_hook( __FILE__, array( $this, 'rewrite_flush' ) );

  }

  function create_services_post_type() {
    register_post_type( 'service_post',
      array(
        'labels' => array(
          'name' => __( 'Service Page' ),
          'singular_name' => __( 'Service Page' ),
          'add_new'   => __( 'Add Service Page' ),
          'all_items'   => __( 'All Service Pages' ),
          'add_new_item' => __( 'Add Service Page' ),
          'edit_item'   => __( 'Edit Service Page' ),
          'view_item'   => __( 'View Service Page' ),
          'search_items'   => __( 'Search Service Pages' ),
          'not_found'   => __( 'Service Page Not Found' ),
          'not_found_in_trash'   => __( 'Service Page not found in trash' ),
        ),
        'taxonomies' => array('category', 'post_tag'),
        'supports' => array( 'title', 'editor', 'revisions'),
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
          'add_new'   => __( 'Add a Page' ),
          'all_items'   => __( 'All Pages' ),
          'add_new_item' => __( 'Add a Department Page' ),
          'edit_item'   => __( 'Edit Department Page' ),
          'view_item'   => __( 'View Department Page' ),
          'search_items'   => __( 'Search Department Pages' ),
          'not_found'   => __( 'No Pages Found' ),
          'not_found_in_trash'   => __( 'Department Page not found in trash' ),
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
        'add_new'   => __( 'Add News' ),
        'all_items'   => __( 'All News' ),
        'add_new_item' => __( 'Add News' ),
        'edit_item'   => __( 'Edit News' ),
        'view_item'   => __( 'View News Item' ),
        'search_items'   => __( 'Search News' ),
        'not_found'   => __( 'News Not Found' ),
        'not_found_in_trash'   => __( 'News not found in trash' ),
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
          'add_new'   => __( 'Add Alert' ),
          'all_items'   => __( 'All Alerts' ),
          'add_new_item' => __( 'Add Alerts' ),
          'edit_item'   => __( 'Edit Alerts' ),
          'view_item'   => __( 'View Alerts' ),
          'search_items'   => __( 'Search Alerts'),
          'not_found'   => __( 'Alert Not Found' ),
          'not_found_in_trash'   => __( 'Alert not found in trash' ),
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

  function create_document_post_type() {
    register_post_type( 'document',
      array(
        'labels' => array(
            'name' => __( 'Document Page' ),
            'singular_name' => __( 'Document' ),
            'add_new'   => __( 'Add Document' ),
            'all_items'   => __( 'All Documents' ),
            'add_new_item' => __( 'Add New Document' ),
            'edit_item'   => __( 'Edit Document' ),
            'view_item'   => __( 'View Document' ),
            'search_items'   => __( 'Search Documents' ),
            'not_found'   => __( 'Document Not Found' ),
            'not_found_in_trash'   => __( 'Document not found in trash' ),
        ),
        'taxonomies' => array('category', 'document_type'),
        'supports' => array( 'title', 'revisions'),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-media-text',
        'hierarchical' => false,
        'rewrite' => array(
            'slug' => 'documents',
        ),
      )
    );
  }
  function create_notification_post_type() {
    register_post_type( 'notification',
      array(
        'labels' => array(
            'name' => __( 'Notifications' ),
            'singular_name' => __( 'Notification' ),
            'add_new'   => __( 'Add Notifications' ),
            'all_items'   => __( 'All Notifications' ),
            'add_new_item' => __( 'Add New Notification' ),
            'edit_item'   => __( 'Edit Notification' ),
            'view_item'   => __( 'View Notification' ),
            'search_items'   => __( 'Search Notifications' ),
            'not_found'   => __( 'Notification Not Found' ),
            'not_found_in_trash'   => __( 'Notification not found in trash' ),
        ),
        'taxonomies' => array('category'),
        'supports' => array( 'title', 'revisions'),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-warning',
        'hierarchical' => false,
        'rewrite' => array(
            'slug' => 'notifications',
        ),
      )
    );
  }
}
