<?php

/**
* Notification short code for department homepages
*
* @link https://github.com/CityOfPhiladelphia/phila.gov-customization
*
* @package phila.gov-customization
* @since 0.18.0
*/

class PhilaGovNotification {

  public static function notification_short_code( $atts ) {

    global $post;
    $category = get_the_category();
    $current_category = $category[0]->cat_ID;

    $args = array( 'posts_per_page' => 1, 'order'=> 'DESC', 'orderby' => 'date', 'post_type'  => 'notification', 'cat' => $current_category);

    $notification_loop = new WP_Query( $args );

    function timeFormat($date){
      if (!$date == '') {
        $the_date = DateTime::createFromFormat('m-d-Y', $date);
        return $the_date->format('m-d-Y');
      }
    }

    if( $notification_loop->have_posts() ) :
      while( $notification_loop->have_posts() ) : $notification_loop->the_post();

        if ( function_exists('rwmb_meta' ) ) {
          $notification_start = rwmb_meta( 'phila_notification-start', $args = array('type' => 'date'));
          $notification_end = rwmb_meta( 'phila_notification-end', $args = array('type' => 'date'));
          $desc = rwmb_meta('phila_notification-description', $args = array('type'=>'textarea'));
        }
        $current_date = date('m-d-Y');
        if ($notification_end < $current_date){
          $output = '';
        }else {
          $output = '';
          $output .= '<div data-alert class="alert-box info">';
          $output .=  '<h3>' . timeFormat($notification_start) . ' - ' . get_the_title( $post->ID ) . '</h3>';

          $desc = rwmb_meta('phila_notification-description', $args = array('type'=>'textarea'));
          $output .= '<span>' . $desc . '</span>';

          $output .= '</div>';
        }

      endwhile;
    endif;

    return $output;
  }
}

add_shortcode( 'notifications', array( 'PhilaGovNotification', 'notification_short_code' ) );
