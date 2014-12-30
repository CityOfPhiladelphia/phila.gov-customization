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

function get_home_news(){
    $category = get_the_category(); 
    $url = rwmb_meta('phila_news_url', $args = array('type'=>'url'));
    $contributor = rwmb_meta('phila_news_contributor', $args = array('type'=>'text'));
    $desc = rwmb_meta('phila_news_desc', $args = array('type'=>'textarea'));
    
    if (!$url == ''){
        
        echo '<a href="' . $url .'" target="_blank">';
        the_post_thumbnail( 'full' );
        echo '<span class="accessible"> Opens in new window</span></a>';

        echo '<a href="' . $url .'" target="_blank">';
        the_title('<h3>', '</h3>'); 
        echo '<span class="accessible"> Opens in new window</span></a>';
        
        
    }else{
        echo '<a href="' . get_permalink() .'">';
        the_post_thumbnail( 'full' );
        echo '</a>';
        
        echo '<a href="' . get_permalink().'">';
        the_title('<h3>', '</h3>'); 
        echo '</a>';

    }

    if (function_exists('rwmb_meta')) {   
        if ($contributor === ''){
            echo '<span>' . $category[0]->cat_name . '</span>';
        }else {
            echo '<span>' . $contributor . '</span>';
        }

        echo '<p>' . $desc  . '</p>';

    } 
}