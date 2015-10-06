<?php
/**
 * Customize the interface for news
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 *
 * @package phila.gov-customization
 */


function get_home_news(){
    $category = get_the_category();
    $url = rwmb_meta('phila_news_url', $args = array('type'=>'url'));
    $contributor = rwmb_meta('phila_news_contributor', $args = array('type'=>'text'));
    $desc = rwmb_meta('phila_news_desc', $args = array('type'=>'textarea'));

    if (!$url == ''){

        echo '<a href="' . $url .'" target="_blank">';
        the_post_thumbnail(  );
        echo '<span class="accessible"> Opens in new window</span></a>';

        echo '<a href="' . $url .'" target="_blank">';
        the_title('<h3>', '</h3>');
        echo '<span class="accessible"> Opens in new window</span></a>';


    }else{
        echo '<a href="' . get_permalink() .'">';
        the_post_thumbnail(  );
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
*
* Filter the post_type_link to add the category into url
*
* @package phila.gov-customization
*/

add_filter( 'post_type_link', 'phila_news_link' , 10, 2 );
function phila_news_link( $post_link, $id = 0 ) {

    $post = get_post( $id );

    if ( is_wp_error( $post ) || 'news_post' != $post->post_type || empty( $post->post_name ) )
        return $post_link;

    // Get the genre:
    $terms = get_the_terms( $post->ID, 'category' );

    if( is_wp_error( $terms ) || !$terms ) {
        $cat = 'uncategorised';
    } else {
        $cat_obj = array_pop($terms);
        $cat = $cat_obj->slug;
    }

    return home_url( user_trailingslashit( "news/$cat/$post->post_name" ) );
}


/**
* @since 0.7.0
*
* Shortcode for displaying news on department homepage
* @param @atts - posts can be set to 1 or 3 in a card-like view
*                list can be set for ul display
*
* @package phila.gov-customization
*/


function recent_news_shortcode($atts) {
  global $post;
  $category = get_the_category();
  $a = shortcode_atts( array(
   'posts' => 1,
    0 => 'list'
 ), $atts );

  function is_flag( $flag, $atts ) {
     foreach ( $atts as $key => $value )
         if ( $value === $flag && is_int( $key ) ) return true;
     return false;
  }

   $current_category = $category[0]->cat_ID;
   if ($a['posts'] > 3){
     $a['posts'] = 3;
   }

   if ( is_flag( 'list', $atts ) ){
     $a['posts'] = 6;
   }

  $args = array( 'posts_per_page' => $a['posts'], 'order'=> 'DESC', 'orderby' => 'date', 'post_type'  => 'news_post', 'cat' => $current_category);

  $news_loop = new WP_Query( $args );

  $output = '';
  $output = '<div class="news">';

  if( $news_loop->have_posts() ) {
    $post_counter = 0;

    if ( $a['posts'] == 2) {
      $output .= '<div class="row"><div class="equal-height"><div class="row title-push"><h2 class="alternate divide large-16 columns">' . __('News', 'phila.gov') . '</h2></div>';
    }
    if ( $a['posts'] == 3) {
      $output .= '<div class="row"><div class="equal-height"><div class="row title-push"><h2 class="alternate divide large-24 columns">' . __('News', 'phila.gov') . '</h2></div>';
    }
    if ( is_flag ('list', $atts) ) {
      $output .= '<div class="row"><h2 class="alternate divide large-24 columns">' . __('News', 'phila.gov') . '</h2></div><div class="row"><div class="medium-24 columns"><ul class="news-list">';
    }

    while( $news_loop->have_posts() ) : $news_loop->the_post();
    $post_counter++;

    $url = rwmb_meta('phila_news_url', $args = array('type'=>'url'));
    $contributor = rwmb_meta('phila_news_contributor', $args = array('type'=>'text'));
    $desc = rwmb_meta('phila_news_desc', $args = array('type'=>'textarea'));

    $link = get_permalink();

    if ( is_flag( 'list', $atts ) ){
      $output .= '<li>';
      if ( !$url == '' ){
        $output .= '<a href="' . $url .'">';
      }else{
        $output .= '<a href="' . get_permalink() .'">';
      }
      $output .=  get_the_post_thumbnail( $post->ID, 'news-thumb', 'class=alignleft small-thumb' );
      $output .= 	'<span class="entry-date small-text">'. get_the_date() . '</span>';
      $output .=  '<h3>' . get_the_title( $post->ID ) . '</h3>';
      $output .= '<span class="small-text">' . $desc . '</span>';
      $output .= '</a>';
      $output .= '</li>';

    }else{

      $output .=  '<div class="medium-8 columns">';

      //news title on first item
      if ( $post_counter == 1 && $a['posts'] == 1) {
        $output .= '<h2 class="alternate divide title-offset">' . __('News', 'phila.gov') . '</h2>';
      }

      $output .= '<div class="story s-box">';

      if (!$url == ''){
        $output .= '<a href="' . $url .'">'; //a tag ends after all the content
        $output .=  get_the_post_thumbnail( $post->ID, 'news-thumb' );
        $output .= '<h3>' . get_the_title( $post->ID ) . '</h3>';

      }else{
        $output .= '<a href="' . get_permalink() .'">';//a tag ends after all the content
        $output .=   get_the_post_thumbnail( $post->ID, 'news-thumb' );
        $output .=  '<h3>' . get_the_title( $post->ID ) . '</h3>';
      }

      if ( function_exists('rwmb_meta' ) ) {
        if ( $contributor != ''){
          $output .= '<span class="small-text">' . $contributor . '</span>';
        }
        $output .= '<p>' . $desc  . '</p>';
      }
      $output .= '</a></div></div>';
    }

    endwhile;

    if ( is_flag( 'list', $atts ) ) {
      $output .= '</ul></div></div>';
    }
    //this means we had equal-height applied and must close those divs
    $output .= '</div></div>';

    }else {
      $output .= __( 'Please enter at least one news story.', 'phila.gov' );
    }
    //.news
    $output .= '</div>';

  wp_reset_postdata();
  return $output;

}
add_action( 'init', 'register_news_shortcodes' );

function register_news_shortcodes(){
   add_shortcode( 'recent-news', 'recent_news_shortcode' );
}
