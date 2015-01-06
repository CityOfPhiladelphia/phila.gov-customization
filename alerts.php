<?php

/**
* Add alerts to all pages
*
* @link https://github.com/CityOfPhiladelphia/phila.gov-customization
*
* @package phila.gov-customization
*/

function create_site_wide_alerts(){
  $args = array(	'post_type' => 'site_wide_alert');

  $pages = get_posts($args);


  if (function_exists('rwmb_meta')) {
    $alert_active = rwmb_meta( 'phila_active', $args = array('type' => 'url'));
  }

  $args = array(
    'post_type' => array ('site_wide_alert'),
    'posts_per_page'    => 1,
    'meta_key'          => 'phila_active',
    //only show if "yes" is selected
    'meta_value'     => '1'
  );
  $alert_query = new WP_Query($args);
  if ( $alert_query->have_posts() ) : while ( $alert_query->have_posts() ) : $alert_query->the_post();
    {
      $alert_type = rwmb_meta( 'phila_type', $args = array('type' => 'select'));
      $alert_start = rwmb_meta( 'phila_start', $args = array('type' => 'datetime'));
      $alert_end = rwmb_meta( 'phila_end', $args = array('type' => 'datetime'));
      echo '<div id="site-wide-alert" class="pure-g"><div class="container"><div class="pure-u-1">';

      echo '<div class="pure-u-3-24 icon">icon</div>';
      echo '<div class="pure-u-8-24">';
      echo the_title('<h2>','</h2>');

      echo '<div class="alert-start">From: ' . $alert_start .'</div>' . '<div class="alert-end">Until: ' . $alert_end .'</div>';
      echo '</div>';
      echo '<div class="pure-u-13-24">';
      echo $alert_type . ': ';
      $content = get_the_content();
      echo $content;
      echo '</div></div></div></div>';
    }
  endwhile;
    else: //no alerts!
  endif;


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
