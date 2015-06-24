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

if (!class_exists("PhilaGovCustomTax")){
    class PhilaGovCustomTax {
            function add_custom_taxonomies() {
                register_taxonomy('topics',
                    array(
                        'post',
                        'page',
                        'service_post'
                    ), array(
                        'hierarchical' => true,
                        // This array of options controls the labels displayed in the WordPress Admin UI
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
                    // Control the slugs used for this taxonomy
                    'rewrite' => array(
                      'slug' => 'browse', // This controls the base slug that will display before each term
                      'hierarchical' => true // This will allow URL's like "/topics/water/billing"
                    ),
              ));
            }
    }//end PhilaGovCustomTax
}

//create instance of PhilaGovCustomTax
if (class_exists("PhilaGovCustomTax")){
    $phila_gov_tax = new PhilaGovCustomTax();
}

if (isset($phila_gov_tax)){
    //WP actions
    add_action( 'init', array($phila_gov_tax, 'add_custom_taxonomies'), 0 );
}
