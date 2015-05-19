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

/**
* @since 0.5.11
* Rewrite rules for news
* /news/category should render filtered archive page
*
* @package phila.gov-customization
*/
function phila_news_rewrite() {
  add_rewrite_rule("^news/([^/]+)/([^/]+)/?",'index.php?post_type=news_post&category_name=$matches[1]&news_post=$matches[2]','top');
  add_rewrite_rule("^news/([^/]+)/?",'index.php?post_type=news_post&category_name=$matches[1]','top');

//  add_permastruct('news-rewrite', $struct, $args);

}
add_action('init','phila_news_rewrite');

/**
* @since 0.5.11
*
* Filter the post_type_link to add the category into url
*
* @package phila.gov-customization
*/

function phila_news_link( $post_link, $id = 0 ) {

    $post = get_post($id);

    if ( is_wp_error($post) || 'news_post' != $post->post_type || empty($post->post_name) )
        return $post_link;

    // Get the genre:
    $terms = get_the_terms($post->ID, 'category');

    if( is_wp_error($terms) || !$terms ) {
        $cat = 'uncategorised';
    } else {
        $cat_obj = array_pop($terms);
        $cat = $cat_obj->slug;
    }

    return home_url(user_trailingslashit( "news/$cat/$post->post_name" ));
}
add_filter( 'post_type_link', 'phila_news_link' , 10, 2 );
