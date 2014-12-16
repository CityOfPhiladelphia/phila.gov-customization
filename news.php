<?php 
/**
 * Customize the interface for news
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 * 
 * @package phila.gov-customization
 */


//logic for user role that can see "add to home" metabox
if (is_admin()) :
    function remove_news_meta_boxes() {
         if( !current_user_can('create_users') ) {
            remove_meta_box('news-admin-only', 'news_post', 'side');
         }
    }
    add_action( 'admin_head', 'remove_news_meta_boxes' );
endif;