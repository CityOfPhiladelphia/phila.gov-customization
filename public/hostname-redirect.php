<?php
/*
*  Non-logged in users get kicked back to the public site.
*/

add_action('template_redirect', 'admin_phila_redirect');

function admin_phila_redirect(){
  if ($_SERVER && $_SERVER['HTTP_PHL_SCR'] && $_SERVER['HTTP_PHL_SCR'] == 'beta-static-generator/0.0.1'){
    return;
  }

  $domain = parse_url($_SERVER['HTTP_HOST']);
  $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

   if ( !isset( $domain['path'] ) ) $domain['path'] = 'localhost'; // Probably it is a localhost dev.

  if ( !is_user_logged_in() && $domain['path'] === 'admin.phila.gov' ) {
    wp_redirect( 'https://'.'www.phila.gov' . $path );
    die();
  }else if( !is_user_logged_in() && $domain['path'] === 'staging-admin.phila.gov' ){
    wp_redirect( 'https://' . 'staging-www.phila.gov' . $path );
    die();
  } else if( !is_user_logged_in() && $domain['path'] === 'test-admin.phila.gov' ){
    wp_redirect( 'https://' . 'test-www.phila.gov' . $path );
    die();
  }
}
