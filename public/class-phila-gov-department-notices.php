<?php
/**
 * Departmental Notices
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 *
 * @package phila.gov-customization
 * @since 0.19.0
 */

if ( class_exists("PhilaGovDepartmentHomePageNotices" ) ){
  $phila_document_load = new PhilaGovDepartmentHomePageNotices();
}

class PhilaGovDepartmentHomePageNotices {

  public function __construct(){

    add_action( 'init', array( $this, 'register_notices_shortcode' ) );

  }

  function notices_shortcode( $atts ) {
    global $post;
    $category = get_the_category();

    $current_category = $category[0]->cat_ID;

    $args = array( 'posts_per_page' => 5,
    'order'=> 'DESC',
    'orderby' => 'date',
    'post_type'  => 'news_post',
    'cat' => $current_category,
    'tax_query'=> array(
      array(
        'taxonomy' => 'news_type',
        'field'    => 'slug',
  			'terms'    => 'notice',
        'operator' => 'IN'
        ),
      ),
    );

    $notices_loop = new WP_Query( $args );

    if( $notices_loop->have_posts() ) {

      $output = '';
      $output .= '<h2 class="alternate divide">Notices</h2>';
      $output .= '<div class="notices content-block">';

      $output .= '<ul class="no-bullet">';

      while( $notices_loop->have_posts() ) : $notices_loop->the_post();

        $link = get_permalink();

        $output .= '<li>';
        $output .= '<a href="' . $link .'">';
        $output .= get_the_title();
        $output .= '</a>';
        $output .= '</li>';

      endwhile;
      $output .= '</ul>';
      $output .= '</div>';

    }else {
      $output = '';
    }

      wp_reset_postdata();
      return $output;
  }

  function register_notices_shortcode(){
    add_shortcode( 'notices', array($this, 'notices_shortcode') );
  }

}
