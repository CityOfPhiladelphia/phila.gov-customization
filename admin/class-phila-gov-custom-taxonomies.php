<?php
/**
 * Add custom taxonomies
 *
 * Additional custom taxonomies can be defined here
 * http://codex.wordpress.org/Function_Reference/register_taxonomy
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 *
 * @package phila.gov-customization
 */

if (class_exists("PhilaGovCustomTaxonomies") ){
  $phila_gov_tax = new PhilaGovCustomTaxonomies();
}
class PhilaGovCustomTaxonomies {

  public function __construct(){
    add_action( 'init', array($this, 'add_custom_taxonomies') );
  }

  function add_custom_taxonomies() {
    register_taxonomy('topics',
      array(
        'post',
        'page',
        'service_post'
      ), array(
          'hierarchical' => true,
          'labels' => array(
            'name' => _x( 'Topics', 'taxonomy general name'),
            'singular_name' => _x( 'Topic', 'taxonomy singular name'),
            'menu_name' =>     __('Topics'),
            'search_items' =>  __( 'Search Topics' ),
            'all_items' =>     __( 'All Topics' ),
            'edit_item' =>     __( 'Edit Topic' ),
            'update_item' =>   __( 'Update Topic' ),
            'add_new_item' =>  __( 'Add New Topic' ),
            'new_item_name' => __( 'New Topic Name' ),
            'menu_name' =>     __( 'Topics' ),
          ),
      'public' => true,
      'show_admin_column' => true,
      'rewrite' => array(
        'slug' => 'browse',
        'hierarchical' => true
      ),
    ));
    register_taxonomy('document_type',
      array(
        'document'
      ), array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x( 'Document Type', 'taxonomy general name'),
            'singular_name' => _x( 'Document Type', 'taxonomy singular name'),
            'menu_name' =>     __('Document Type'),
            'search_items' =>  __( 'Search Document Types' ),
            'all_items' =>     __( 'All Document Types' ),
            'edit_item' =>     __( 'Edit Document Type' ),
            'update_item' =>   __( 'Update Document Type' ),
            'add_new_item' =>  __( 'Add New Document Type' ),
            'new_item_name' => __( 'New Document Type' ),
            'menu_name' =>     __( 'Document Types' ),
          ),
      'public' => true,
      'show_admin_column' => true,
      'rewrite' => array(
        'slug' => 'document-type',
        'hierarchical' => false
      ),
    ));
    register_taxonomy('document_topics',
      array(
        'document'
      ), array(
        'hierarchical' => true,
        'labels' => array(
          'name' => _x( 'Document Topic', 'taxonomy general name'),
          'singular_name' => _x( 'Document Topic', 'taxonomy singular name'),
          'menu_name' =>     __('Document Topic'),
          'search_items' =>  __( 'Search Document Topics' ),
          'all_items' =>     __( 'All Document Topics' ),
          'edit_item' =>     __( 'Edit Document Topic' ),
          'update_item' =>   __( 'Update Document Topic' ),
          'add_new_item' =>  __( 'Add New Document Topic' ),
          'new_item_name' => __( 'New Document Topic' ),
          'menu_name' =>     __( 'Document Topics' ),
        ),
      'public' => true,
      'show_admin_column' => true,
      'rewrite' => array(
        'slug' => 'document-topics',
        'hierarchical' => false
      ),
    ));
  }
}
