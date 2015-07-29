<?php
/**
 * Registers all the metaboxes we ever will need
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 *
 * @package phila.gov-customization
 */

add_filter( 'rwmb_meta_boxes', 'phila_register_meta_boxes' );

function phila_register_meta_boxes( $meta_boxes ){
    $prefix = 'phila_';

    $meta_boxes[] = array(
      'id'       => 'service_additions',
      'title'    => 'Service Description',
      'pages'    => array( 'service_post' ),
      'context'  => 'normal',
      'priority' => 'high',

      'fields' => array(
        array(
          'name'  => 'Description',
          'desc'  => 'A short description of the Service',
          'id'    => $prefix . 'service_desc',
          'type'  => 'textarea',
          'class' => 'service-description',
          'clone' => false,
        ),
         array(
          'name'  => 'Full URL of Service',
          'desc'  => 'https://ework.phila.gov/revenue/',
          'id'    => $prefix . 'service_url',
          'type'  => 'URL',
          'class' => 'service-url',
          'clone' => false,
        ),
        array(
          'name'  => 'Detail',
          'desc'  => 'The name of the website',
          'id'    => $prefix . 'service_detail',
          'type'  => 'text',
          'class' => 'service-detail',
          'clone' => false,
        ),
      )
    );//Service page links and description

    $meta_boxes[] = array(
      'id'       => 'departments',
      'title'    => 'Department Information',
      'pages'    => array( 'department_page' ),
      'context'  => 'normal',
      'priority' => 'high',

      'fields' => array(
        array(
          'name'  => 'Description',
          'desc'  => 'A short description of the department. Required.',
          'id'    => $prefix . 'dept_desc',
          'type'  => 'textarea',
          'class' => 'dept-description',
          'clone' => false,
        ),
        array(
          'name'  => 'External URL of Department',
          'desc'  => 'If the department does not live on this website, enter the location here. Eg. http://phila.gov/revenue/',
          'id'    => $prefix . 'dept_url',
          'type'  => 'URL',
          'class' => 'dept-url',
          'clone' => false,
        ),
      )
    );//External department link

    $meta_boxes[] = array(
      'id'       => 'news',
      'title'    => 'News Information',
      'pages'    => array( 'news_post' ),
      'context'  => 'normal',
      'priority' => 'high',

      'fields' => array(
        array(
          'name'  => 'Description',
          'desc'  => 'A one or two sentence description describing this article. Required.',
          'id'    => $prefix . 'news_desc',
          'type'  => 'textarea',
          'class' => 'news-description',
          'clone' => false,
        )
      )
    );//news description

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

    $meta_boxes[] = array(
      'id'       => 'news-admin-only',
      'title'    => 'Homepage Display',
      'pages'    => array( 'news_post' ),
      'context'  => 'side',
      'priority' => 'high',

      'fields' => array(
        array(
          'name'  => '',
          'desc'  => 'Should this story appear on the homepage?',
          'id'    => $prefix . 'show_on_home',
          'type'  => 'radio',
          'std'=> '0',
          'options' =>  array(
              '0' => 'No',
              '1' => 'Yes'
          )
        ),
      )
    );//news homepage display

    $meta_boxes[] = array(
      'id'       => 'site-wide-alert',
      'title'    => 'Alert Settings',
      'pages'    => array( 'site_wide_alert' ),
      'context'  => 'side',
      'priority' => 'high',

      'fields' => array(
        array(
          'name'  => 'Active Alert',
          'desc'  => 'Is this alert active?',
          'id'    => $prefix . 'active',
          'type'  => 'radio',
          'std'=> '0',
          'options' =>  array(
            '0' => 'No',
            '1' => 'Yes'
          )
        ),
        array(
          'name'  => 'Alert Type',
          'id'    => $prefix . 'type',
          'type'  => 'select',
          'std'=> '0',
          'options' =>  array(
            'Code Blue Effective' => 'Code Blue Effective',
            'Code Red Effective' => 'Code Red Effective',
            'Code Orange Effective' => 'Code Orange Effective',
            'Code Grey Effective'  =>  'Code Grey Effective',
            'Other' => 'Other'
          )
        ),
        array(
          'name'  => 'Custom Icon <a href="http://ionicons.com/" target="_new">get icons</a>. Enter icon name only i.e. <i>ion-alert-circled</i>',
          'id'    => $prefix . 'icon',
          'type'  => 'text',
          'class' => 'other-icon',
          'size'  => 25
        ),
        array(
          'name'  => 'Start Time',
          'id'    => $prefix . 'start',
          'class' =>  'start-time',
          'type'  => 'datetime',
          'size'  =>  25,
          'js_options' =>  array(
            'timeFormat' =>  'hh:mm tt',
            'dateFormat'=>'m-dd-yy',
            'showTimepicker' => true,
            'stepMinute' => 15,
            'showHour' => 'true'
          )
        ),
        array(
          'name'  => 'End Time',
          'id'    => $prefix . 'end',
          'type'  => 'datetime',
          'class' =>  'end-time',
          'size'  =>  25,
          'js_options' =>  array(
            'timeFormat' =>  'hh:mm tt',
            'dateFormat'=>'m-dd-yy',
            'showTimepicker' => true,
            'stepMinute' => 15,
            'showHour' => 'true'
          )
        ),
      )
    );//site wide alert boxes

    $meta_boxes[] = array(
      'id'       => 'document-meta',
      'title'    => 'Publication Information',
      'pages'    => array( 'publication' ),
      'context'  => 'normal',
      'priority' => 'high',

      'fields' => array(
        array(
          'name'  => 'Published Date',
          'id'    => $prefix . 'publication-released',
          'type'  => 'datetime',
          'class' =>  'publication-released',
          'size'  =>  25,
          'js_options' =>  array(
            'timeFormat' =>  'hh:mm tt',
            'dateFormat'=>'m-dd-yy',
            'showTimepicker' => false
          )
        ),
        array(
          'name'  => 'Add Files',
          'id'    => $prefix . 'publications',
          'type'  => 'file_advanced',
          'class' =>  'publication-list',
          'mime_type'  =>  'application/pdf',
      )
    )
  );//document metadata

    return $meta_boxes;
}
