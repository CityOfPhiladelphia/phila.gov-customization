<?php
if ( class_exists( "PhilaGovSiteWideAlertRendering" ) ){
  $phila_site_wide_alert = new PhilaGovSiteWideAlertRendering();
}

class PhilaGovSiteWideAlertRendering {

  /**
  *
  * Display alert if display is true, also show preview
  *
  */
  static function create_site_wide_alerts(){

    $args = array( 'post_type' => 'site_wide_alert' );

    $pages = get_posts( $args );

    $args = array(
      'post_type' => array ('site_wide_alert'),
      'posts_per_page'    => 1,
      'post_status' => 'any'
    );
    $alert_query = new WP_Query($args);
    if ( $alert_query->have_posts() ) {

      while ( $alert_query->have_posts() ) {

        $alert_query->the_post();

        $alert_active = rwmb_meta( 'phila_active', $args = array('type' => 'radio'));
        $alert_type = rwmb_meta( 'phila_type', $args = array('type' => 'select'));
        $alert_start = rwmb_meta( 'phila_start', $args = array('type' => 'datetime'));
        $alert_end = rwmb_meta( 'phila_end', $args = array('type' => 'datetime'));


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

      if ( $alert_active == 1 || ( is_preview() && is_singular( 'site_wide_alert' ) ) ) :

        ?><div id="site-wide-alert">
            <div class="row"><?php
        echo '<div class="large-8 columns">';
        echo '<h2><i class="ionicons ' . $alert_icon . '"></i>' . get_the_title() .'</h2>';

        echo '<div class="alert-start">';
      //  phila_dateTimeFormat($alert_start);
        echo $date_seperator;
        //phila_dateTimeFormat($alert_end);
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
      endif;
      }//end while
    }

    wp_reset_postdata();

  }
}
