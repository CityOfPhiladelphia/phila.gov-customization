<?php

//TODO: Make sure these appear on ADMIN pages. 

if ( class_exists("PhilaGovNews" ) ){
  $phila_document_load = new PhilaGovNews();
}

 class PhilaGovNews {

  public function __construct(){

    add_action( 'admin_notices', array($this, 'load_news_meta') );

  }
  function load_news_meta() {
    if ( current_user_can( PHILA_ADMIN ) ) {
      add_filter( 'rwmb_meta_boxes', array($this, 'phila_register_news_meta_boxes' ) );
    }
  }
  function phila_register_news_meta_boxes( $meta_boxes ){
    $prefix = 'phila_';
    $meta_boxes[] = array(
     'id'       => 'external_news',
     'title'    => 'News Linking to External Source',
     'pages'    => array( 'news_post' ),
     'context'  => 'normal',
     'priority' => 'high',

     'fields' => array(
        array(
          'name'  => 'URL of external news article',
          'desc'  => 'http://www.phila.gov/experiencephila/mayor.html',
          'id'    => $prefix . 'news_url',
          'type'  => 'URL',
          'class' => 'news-url',
          'clone' => false,
         ),
        array(
         'name'  => 'News article contributor name',
         'desc'  => 'Eg. Experience Philadelphia',
         'id'    => $prefix . 'news_contributor',
         'type'  => 'text',
         'class' => 'news-contributor',
         'clone' => false,
         ),
      )
    );//link to external news source
  }
}
