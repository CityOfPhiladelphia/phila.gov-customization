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
  add_rewrite_rule("^news/topics/([^/]+)/?",'index.php?post_type=news_post&topics=$matches[1]','top');
  add_rewrite_rule("^news/topics",'index.php?post_type=news_post','top');
  add_rewrite_rule("^news/([^/]+)/([^/]+)/?",'index.php?post_type=news_post&category_name=$matches[1]&news_post=$matches[2]','top');
  add_rewrite_rule("^news/([^/]+)/?",'index.php?post_type=news_post&category_name=$matches[1]','top');
  add_rewrite_rule("^browse/([^/]+)/([^/]+)/?",'index.php?&topics=$matches[1]&topics=$matches[2]','top');
}
add_action('init','phila_news_rewrite');

function wptuts_register_rewrite_tag() {
    add_rewrite_tag( '%topics%', '([^/]+)');
}
add_action( 'init', 'wptuts_register_rewrite_tag', 0, 10);


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


/**
* @since 
* Shortcode for displaying news on homepage
*
* @package phila.gov-customization
*/

function recent_news_shortcode($atts) {
  global $post;
  $category = get_the_category();
  $a = shortcode_atts( array(
   'posts' => 1,
 ), $atts );

 $current_category = $category[0]->cat_ID;

  $args = array( 'posts_per_page' => $a['posts'], 'order'=> 'DESC', 'orderby' => 'date', 'post_type'  => 'news_post', 'cat' => $current_category);

  $news_loop = new WP_Query( $args );

  $output = '';

  if( $news_loop->have_posts() ) {
    $post_counter = 0;

    $output .= '<h2 class="columns">' . __('News', 'phila.gov') . '</h2>';

    while( $news_loop->have_posts() ) : $news_loop->the_post();
    $post_counter++;

    $url = rwmb_meta('phila_news_url', $args = array('type'=>'url'));
    $contributor = rwmb_meta('phila_news_contributor', $args = array('type'=>'text'));
    $desc = rwmb_meta('phila_news_desc', $args = array('type'=>'textarea'));

    $link = get_permalink();

    //add the "end" class if we are dealing with more than one column
    if ( $post_counter == $a['posts']){
      $output .= '<div class="medium-8 columns end">';
    }else {
      $output .= '<div class="medium-8 columns">';
    }

      $output .= '<div class="story s-box">';

      if (!$url == ''){

        $output .= '<a href="' . $url .'" target="_blank">';
        $output .=  get_the_post_thumbnail( $post->ID );;
        $output .= '<span class="accessible"> Opens in new window</span></a>';

        $output .= '<a href="' . $url .'" target="_blank">';
        $output .= '<h3>' . get_the_title( $post->ID ) . '</h3>';
        $output .= '<span class="accessible"> Opens in new window</span></a>';

      }else{
        $output .= '<a href="' . get_permalink() .'">';
        $output .=   get_the_post_thumbnail( $post->ID );;
        $output .= '</a>';

        $output .= '<a href="' . get_permalink().'">';
        $output .=  '<h3>' . get_the_title( $post->id ) . '</h3>';
        $output .= '</a>';
      }

      if (function_exists('rwmb_meta')) {
        if ($contributor === ''){
          $output .= '<span class="small-text">' . $category[0]->cat_name . '</span>';
        }else {
          $output .= '<span class="small-text">' . $contributor . '</span>';
        }
        $output .= '<p>' . $desc  . '</p>';
      }
      $output .= '</div></div>';

      endwhile;
  }else {
    $output .= __('Please enter at least one news story.');
  }

  wp_reset_postdata();
  return $output;

}

function register_shortcodes(){
   add_shortcode('recent-news', 'recent_news_shortcode');
}

add_action( 'init', 'register_shortcodes');
