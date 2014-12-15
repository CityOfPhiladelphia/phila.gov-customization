<?php 
/**
 * Registers all the metaboxes we ever will need
 *
 * @link https://github.com/CityOfPhiladelphia/phila.gov-customization
 * 
 * @package phila.gov-customization
 */

add_filter( 'rwmb_meta_boxes', 'phila_register_meta_boxes' );

function phila_register_meta_boxes( $meta_boxes )
{
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
                'desc'  => 'A short detail of where the URL is taking you',
                'id'    => $prefix . 'service_detail',
                'type'  => 'textarea',
                'class' => 'service-detail',
                'clone' => false,
            ),
        )
    );
    
    $meta_boxes[] = array(
        'id'       => 'departments',
        'title'    => 'Department Information',
        'pages'    => array( 'department_page' ),
        'context'  => 'normal',
        'priority' => 'high',

        'fields' => array(
            array(
                'name'  => 'Description',
                'desc'  => 'A short description of the department',
                'id'    => $prefix . 'dept_desc',
                'type'  => 'textarea',
                'class' => 'dept-description',
                'clone' => false,
            ),
             array(
                'name'  => 'External URL of Department',
                'desc'  => 'http://phila.gov/revenue/',
                'id'    => $prefix . 'dept_url',
                'type'  => 'URL',
                'class' => 'dept-url',
                'clone' => false,
            ),
        )
    );
    
   $meta_boxes[] = array(
    'id'       => 'news',
    'title'    => 'News Information',
    'pages'    => array( 'news_post' ),
    'context'  => 'normal',
    'priority' => 'high',

    'fields' => array(
        array(
            'name'  => 'Description',
            'desc'  => 'A one or two sentence description',
            'id'    => $prefix . 'news_desc',
            'type'  => 'textarea',
            'class' => 'news-description',
            'clone' => false,
        ),
         array(
            'name'  => 'URL of news article',
            'desc'  => 'http://phila.gov/revenue/',
            'id'    => $prefix . 'news_url',
            'type'  => 'URL',
            'class' => 'news-url',
            'clone' => false,
        ),
        array(
            'name'  => 'Contributor Name',
            'desc'  => 'External source',
            'id'    => $prefix . 'news_contributor',
            'type'  => 'text',
            'class' => 'news-contributor',
            'clone' => false,
        ),
       
    )
);

    return $meta_boxes;
}
 $user_ID = get_current_user_id();
  if($user_ID && is_super_admin( $user_id )) {
    //Do Something
  }