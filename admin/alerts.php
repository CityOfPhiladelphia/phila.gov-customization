<?php

/**
* Add alerts to all pages
*
* @link https://github.com/CityOfPhiladelphia/phila.gov-customization
*
* @package phila.gov-customization
*/

/**
* Display alerts if post is set
*
*
*/
function create_site_wide_alerts(){


  $args = array(	'post_type' => 'site_wide_alert');

  $pages = get_posts($args);

  $args = array(
    'post_type' => array ('site_wide_alert'),
    'posts_per_page'    => 1,
    'meta_key'          => 'phila_active',
    //only show if "yes" is selected
    'meta_value'     => '1'
  );
  $alert_query = new WP_Query($args);
  if ( $alert_query->have_posts() ) {

    while ( $alert_query->have_posts() ) {

      $alert_query->the_post();

      $alert_type = rwmb_meta( 'phila_type', $args = array('type' => 'select'));
      $alert_start = rwmb_meta( 'phila_start', $args = array('type' => 'datetime'));
      $alert_end = rwmb_meta( 'phila_end', $args = array('type' => 'datetime'));

      function timeFormat($date){
        if (!$date == '') {
          $the_date = DateTime::createFromFormat('m-d-Y H:i a', $date);
          echo $the_date->format('m-d-Y h:ia');
        }
      }

      //timeFormat();
      $alert_icon = 'ion-alert-circled';

      if($alert_type == 'Code Red Effective') {
        $alert_icon = 'ion-ios-sunny';
      }elseif ($alert_type == 'Code Orange Effective') {
        $alert_icon = 'ion-cloud';
      }elseif ($alert_type == 'Code Grey Effective') {
        $alert_icon = 'ion-waterdrop';
      }elseif($alert_type == 'Other'){
        $alert_icon = rwmb_meta( 'phila_icon', $args = array('type' => 'text'));
      }
      $date_seperator = ' <strong>to</strong> ';
      if(($alert_start == '') || ($alert_end == '')){
        $date_seperator = ' ';
      }

      ?><div id="site-wide-alert">
          <div class="row"><?php
      echo '<div class="large-8 columns">';
      echo '<h2><i class="ionicons ' . $alert_icon . '"></i>' . get_the_title() .'</h2>';

      echo '<div class="alert-start">';
      timeFormat($alert_start);
      echo $date_seperator;
      timeFormat($alert_end);
      echo '</div>';
      echo '</div>';
      echo '<div class="large-16 columns">';
      if ($alert_type == 'Other'){
        //blank
      }else {
        echo '<strong>'.$alert_type . ': </strong>';
      }

      $content = get_the_content();
      echo $content;
      echo '</div></div></div>';
    }//end while
  } else {
    //nothing
    }

  wp_reset_postdata();

}

//Show the active column
function site_wide_alert_columns( $columns ) {
  $columns["active_alert"] = "Active";
  return $columns;
}
add_filter('manage_edit-site_wide_alert_columns', 'site_wide_alert_columns');
add_filter('manage_edit-site_wide_alert_sortable_columns', 'site_wide_alert_columns');


function site_wide_alert_column_output( $colname, $cptid ) {
  echo get_post_meta( $cptid, 'phila_active', true );
}
add_action('manage_site_wide_alert_posts_custom_column', 'site_wide_alert_column_output', 10, 2);
//the actual column data is output

function my_sort_metabox( $vars ) {
  if( array_key_exists('orderby', $vars )) {
    if('Active' == $vars['orderby']) {
      $vars['orderby'] = 'meta_value';
      $vars['meta_key'] = 'phila_active';
    }
  }
  return $vars;
}
add_filter('request', 'my_sort_metabox');
//the ASC and DESC part of ORDER BY is handled automatically

/**
* Add scripts only to site_wide_alert posts
*
*/
add_action( 'admin_enqueue_scripts', 'enqueue_alert_scripts' );

function enqueue_alert_scripts($hook) {
  global $post;
  if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
    if ( 'site_wide_alert' === $post->post_type ) {
        wp_enqueue_script( 'alerts-ui', plugin_dir_url( __FILE__ ) . '../js/alerts.js', array('jquery'));
    }
  }
}
