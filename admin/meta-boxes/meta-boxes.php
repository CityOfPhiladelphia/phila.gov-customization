<?php
/**
 * Registers metaboxes
 *
 */

add_action('admin_init', 'phila_return_month_array');

function phila_return_month_array(){

  $month_array = array();
  for ($m=1; $m<=12; $m++) {
    $month = date('F', mktime(0,0,0,$m, 1, date('Y')));

    $month_array[$month] = $month;
  }
  return $month_array;
}
function phila_return_week_array(){

  $week_array = array(
    'Monday'  => 'Monday',
    'Tuesday' => 'Tuesday',
    'Wednesday' => 'Wednesday',
    'Thursday'  => 'Thursday',
    'Friday'  => 'Friday',
    'Saturday'  => 'Saturday',
    'Sunday'  => 'Sunday'
  );

  return $week_array;
}

function phila_setup_tiny_mce_basic( array $options ){

  $output['block_formats'] = 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6;';

  $defaults = array(
    'format_select' => false,
    'heading_level' => 'h3',
  );

  $options = array_merge($defaults, $options);

  if ( $options['format_select'] == true) {

    $output['toolbar1'] = 'formatselect, bold, italic, bullist, numlist, link, unlink, outdent, indent, removeformat, pastetext, superscript, subscript, hr, table';

  }

  if ( $options['heading_level'] == 'h3' ) {
    $output['block_formats'] = 'Paragraph=p; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6;';

  }elseif( $options['heading_level'] == 'h2' ){
    $output['block_formats'] = 'Paragraph=p;  Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6;';
  }

  if ( $options['format_select'] == false ) {

    $output['toolbar1'] = 'bold, italic, bullist, numlist, link, unlink, outdent, indent, removeformat, pastetext, superscript, subscript, table';

  }
  return $output;

}

add_filter( 'rwmb_meta_boxes', 'phila_register_meta_boxes' );

function phila_register_meta_boxes( $meta_boxes ){

  $meta_boxes[] = array(
    'id'       => 'announcement_end',
    'title'    => 'When should this end?',
    'pages'    => array( 'announcement' ),
    'context'  => 'side',
    'priority' => 'high',

    'fields' => array(
      array(
        'name'  => 'End date',
        'id'    => 'phila_announce_end_date',
        'type'  => 'date',
        'class' =>  'effective-end-time',
        'desc'  => 'Choose a date for this announcement to expire. Announcements can exist for a maximum of four weeks.',
        'required'=> true,
        'size'  =>  25,
        'timestamp'  => true,
        'js_options' =>  array(
          'dateFormat' => 'yy/mm/dd',
          'controlType'=> 'select',
          'oneLine'=> true,
          'maxDate' => '+4w'
        ),
        'admin_columns' => array(
          'position' => 'after date',
          'title'    => __( 'End date' ),
          'sort'     => true,
        ),
      ),
    ),
  );
  $meta_boxes[] = array(
    'title'    => 'Show on homepage?',
    'pages'    => array( 'announcement' ),
    'context'  => 'side',
    'priority' => 'high',
    'include' => array(
      'user_role'  => array( 'administrator', 'phila_master_homepage_editor', 'editor' ),
      'relation' => 'or',
    ),
    'fields' => array(
      array(
        'name'  => '',
        'desc'  => 'Display this announcement on the phila.gov homepage.',
        'id'    => 'show_on_home',
        'type'  => 'switch',
        'std'=> '0',
        'on_label'  => 'Yes',
        'off_label' => 'No',
      ),
    )
  );

  $meta_boxes[] = array(
    'id'       => 'document-description',
    'title'    => 'Document Information',
    'pages'    => array( 'document' ),
    'context'  => 'normal',
    'priority' => 'high',
    'revision' => true,
    'fields' => array(
      array(
        'id'   => 'phila_document_description',
        'type' => 'wysiwyg',
        'options' => Phila_Gov_Standard_Metaboxes::phila_wysiwyg_options_basic(),
        'desc' => 'Information describing the collection of documents on this page. This content will appear above the document list.'
      ),
      array(
        'type'  => 'heading',
        'name' => ' Release Date',
      ),
      array(
        'id'   => 'phila_override_release_date',
        'name'  => 'Override all release dates on this page with the date below?',
        'type' => 'switch',
        'on_label'  => 'Yes',
        'off_label' => 'No'
      ),
      array(
        'id'    => 'phila_document_released',
        'type'  => 'date',
        'class' =>  'document-released',
        'size'  =>  25,
        'js_options' =>  array(
          'dateFormat'=>'MM dd, yy',
          'showTimepicker' => false
        )
      ),
    )
  );
  $meta_boxes[] = array(
    'id'       => 'document-meta',
    'title'    => 'Files',
    'pages'    => array( 'document' ),
    'context'  => 'normal',
    'priority' => 'high',
    'revision' => true,
    'fields' => array(
      array(
        'name'  => 'Add Files',
        'id'    => 'phila_files',
        'type'  => 'file_advanced',
        'class' =>  'add-files',
        'mime_type' => 'application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document,
        application/vnd.ms-powerpointtd, application/vnd.openxmlformats-officedocument.presentationml.presentation,
        application/vnd.ms-excel,
        application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
        text/plain'
      ),
    ),
  );

  $meta_boxes[] = array(
    'id'       => 'press-release-date',
    'title'    => 'Release Date',
    'pages'    => array( 'post' ),
    'context'  => 'after_title',
    'priority' => 'low',
    'visible' => array(
      'when' => array(
        array('phila_template_select', '=', 'press_release'),
      ),
    ),

    'fields' => array(
      array(
        'name'  => 'Release Date',
        'id'    => 'phila_press_release_date',
        'type'  => 'date',
        'class' =>  'press-release-date',
        'size'  =>  30,
        'required'  => true,
        'js_options' =>  array(
          'dateFormat'=>'MM dd, yy',
          'showTimepicker' => false
        )
      ),
    ),
  );

  $meta_boxes[] = array(
    'title'    => 'Contact Information',
    'pages'    => array( 'post' ),
    'context'  => 'after_title',
    'priority' => 'low',
    'visible' => array(
      'when' => array(
        array('phila_template_select', '=', 'press_release'),
      ),
    ),

    'fields' => array(
      array(
        'id'  => 'press_release_contact',
        'type' => 'group',
        'clone'  => true,
        'fields' => array(
          Phila_Gov_Standard_Metaboxes::phila_metabox_v2_phila_text('Contact name', 'phila_press_release_contact_name', true),
          array(
            'name' => 'Contact phone',
            'id'   => 'phila_press_release_contact_phone_number',
            'type' => 'phone',
            'placeholder' => '(215) 686-2181'
          ),
          array(
            'name' => 'Contact email',
            'id'   => 'phila_press_release_contact_email',
            'type' => 'text',
            'std' => 'press@phila.gov',
            'required'  => true,
          ),
        ),
      )
    ),
  );

  $meta_boxes[] = array(
    'id'       => 'phila_resource_list',
    'title'    => __( 'Resource List' ),
    'pages'    => array( 'department_page', 'programs' ),
    'context'  => 'normal',
    'priority' => 'high',
    'visible' => array(
      'when' => array(
        array(  'phila_template_select','=', 'resource_list_v2'),
      ),
    ),

    'fields' => array(
      array(
        'id'  => 'phila_resource_list',
        'type' => 'group',
        'clone'  => true,
        'sort_clone' => true,
        'add_button' => '+ Add a Resource List',

        'fields' => array(
          Phila_Gov_Standard_Metaboxes::phila_metabox_v2_phila_text(__('List Title', 'rwmb'), 'phila_resource_list_title', true),
          array(
            'id'   => 'phila_resource_list_items',
            'type' => 'group',
            'clone'  => true,
            'sort_clone' => true,
            'add_button' => '+ Add an item',

            'fields' => array(
                Phila_Gov_Standard_Metaboxes::phila_metabox_v2_phila_text(__('Item Title', 'rwmb'), 'phila_list_item_title', true),
                array(
                  'name' => __('Item URL', 'rwmb'),
                  'id'   => 'phila_list_item_url',
                  'type' => 'url',
                  'required' => true,
                ),
                array(
                  //'name' => __('Featured Resource Summary', 'rwmb'),
                  'id'   => 'phila_list_item_external',
                  'name' => 'Does this link take users away from phila.gov?',
                  'type' => 'switch',
                  'on_label' => 'Yes',
                  'off_label'  => 'No'
                ),
                array(
                  'name' => __('Item Icon', 'rwmb'),
                  'id'   => 'phila_list_item_type',
                  'type' => 'select',
                  'placeholder' => 'Choose icon...',
                  'options' => array(
                    'phila_resource_link' => 'Link',
                    'phila_resource_document' => 'Document',
                    'phila_resource_map' => 'Map',
                    'phila_resource_video' => 'Video',
                  ),
                ),
                array(
                  'name' => __('Featured Resource', 'rwmb'),
                  'id'   => 'phila_featured_resource',
                  'class'   => 'phila_featured-resource',
                  'type' => 'checkbox',
                ),
                array(
                  'name' => __('Alternate Featured Title', 'rwmb'),
                  'id'   => 'phila_list_item_alt_title',
                  'type' => 'text',
                  'hidden' => array( 'phila_featured_resource', '!=', true ),
                ),
                array(
                  'name' => __('Featured Resource Summary', 'rwmb'),
                  'id'   => 'phila_featured_summary',
                  'class'   => 'phila_featured-summary',
                  'type' => 'textarea',
                  'hidden' => array( 'phila_featured_resource', '!=', true ),
                ),
                array(
                  'name'  => 'Display Order',
                  'id'    => 'phila_display_order',
                  'type'  => 'select',
                  'class' => 'display-order',
                  'options' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                  ),
                  'hidden' => array( 'phila_featured_resource', '!=', true ),
                ),
              ),
            ),
          ),
        ),
      ),
    );

  // First row of modules - Options have been reduced to What we do + connect circa V2 Department homepages
  $meta_boxes[] = array(
    'id'       => 'phila_module_row_1',
    'title'    => 'Two-thirds row',
    'pages'    => array( 'department_page' ),
    'context'  => 'normal',
    'priority' => 'default',
    'revision' => true,

    'visible' => array(
      'when'  => array(
        array('phila_template_select', '=', 'homepage_v2' ),
      ),
    ),

    'fields' => array(
      array(
        'name' => 'Description',
        'id'   => 'phila_module_row_1_description',
        'type' => 'custom_html',
        'std'  => '<span>Use this area to create a row that will be divided into two columns. The first column will take up 2/3 of the screen and second will take up 1/3.</span>',
      ),
      array(
        'type' => 'divider',
      ),
      array(
        'id' => 'module_row_1_col_1',
        'type' => 'group',
        'fields' => array(
          array(
            'name' => 'Column 1 <br/><small>(2/3 width column)</small>',
            'id'   => 'phila_module_row_1_col_1_type',
            'type' => 'select',
            'placeholder' => 'Select...',
            'std' => 'phila_module_row_1_col_1_custom_text',
            'options' => array(
              'phila_module_row_1_col_1_custom_text' => 'Custom Text',
            ),
          ),
          array(
            'id' => 'module_row_1_col_1_options',
            'type' => 'group',
            'fields' => array(
            array(
              'name' => 'Custom Text Title',
              'id'   => 'phila_module_row_1_col_1_texttitle',
              'type' => 'text',
              'hidden' => array('phila_module_row_1_col_1_type', '!=', 'phila_module_row_1_col_1_custom_text'),
            ),
          array(
            'name' => 'Custom Text Content',
            'id'   => 'phila_module_row_1_col_1_textarea',
            'type' => 'wysiwyg',
            'hidden' => array('phila_module_row_1_col_1_type', '!=', 'phila_module_row_1_col_1_custom_text'),
            'options' =>                     Phila_Gov_Standard_Metaboxes::phila_wysiwyg_options_basic(),
          ),
          ),
        ),
      ),
    ),
    array(
      'type' => 'divider'
    ),
    array(
      'id' => 'module_row_1_col_2',
      'type' => 'group',
      'fields' => array(
        array(
          'name' => 'Column 2 <br/><small>(1/3 column)</small>',
          'id'   => 'phila_module_row_1_col_2_type',
          'desc'  => 'Choose to display recent blog posts, custom markup, call to action panel, or a connect panel.',
          'type' => 'select',
          'std'   => 'phila_module_row_1_col_2_connect_panel',
          'placeholder' => 'Select...',
          'options' => array(
            'phila_module_row_1_col_2_connect_panel' => 'Connect Panel',
          ),
        ),
      ),
    ),
    array(
      'id' => 'module_row_1_col_2_connect_panel',
      'type' => 'group',
      'hidden' => array('phila_module_row_1_col_2_type', '!=', 'phila_module_row_1_col_2_connect_panel'),

      'fields' => Phila_Gov_Standard_Metaboxes::phila_meta_var_connect()
      ),
    )
  );

  // Second row of modules - Calendar
  $meta_boxes[] = array(
    'id'       => 'phila_module_row_2',
    'title'    => 'Full row calendar',
    'pages'    => array( 'department_page' ),
    'context'  => 'normal',
    'priority' => 'default',
    'revision' => true,

    'include' => array(
      'user_role'  => array( 'administrator', 'editor', 'primary_department_homepage_editor' ),
    ),
    'visible' => array(
      'when'  => array(
        array('phila_template_select', '=', 'homepage_v2' ),
      ),
      'relation' => 'or',
    ),

    // List of sub-fields
    'fields' => Phila_Gov_Standard_Metaboxes::phila_metabox_v2_calendar_full(),
  );

  $meta_boxes[] = array(
    'id'       => 'phila_staff_directory_listing',
    'title'    => 'Staff Directory Listing',
    'pages'    => array( 'department_page' ),
    'context'  => 'normal',
    'priority' => 'default',

    'include' => array(
      'user_role'  => array(
        'administrator', 'primary_department_homepage_editor', 'editor' ),
    ),
    'visible' => array(
      'when'  => array(
        array('phila_template_select', '=', 'homepage_v2' ),
      ),
    ),

    'fields' => array(
      array(
        'id'   => 'phila_staff_directory_selected',
        'name'  => 'Display a staff directory list?',
        'type' => 'switch',
        'on_label'  => 'Yes',
        'off_label' => 'No',
        'after' => '<p class="description">Enter at least one staff member in the <a href="/wp-admin/edit.php?post_type=staff_directory">Staff Members</a> section.</p>',
      ),
      array(
        'id'  => 'phila_get_staff_cats',
        'type' => 'group',
        'visible' => array(
          'when' => array(
            array( 'phila_staff_directory_selected', '=', 1 ),
          ),
        ),
        'fields' => array(
          Phila_Gov_Standard_Metaboxes::phila_metabox_category_picker('Select new owner', 'phila_staff_category', 'Display staff members from these owners. This will override page ownership selection entirely.' ),
        ),
      ),
      array(
        'id'  => 'hide_units',
        'class' => 'hide-on-load',
        'name'  => 'Hide staff assigned to units?',
        'desc'  => 'By selecting this option, staff assigned to a unit will not appear on this department homepage.',
        'type' => 'switch',
        'on_label'  => 'Yes',
        'off_label' => 'No',
        'visible' => array(
          'phila_template_select', 'in', ['homepage_v2', 'homepage_v3']
        )
      ),
    ),
  );

  $meta_boxes[] = array(
    'id'  => 'board_commission_member_list',
    'title' => 'Commission or board member listing',
    'pages' => array('department_page'),
    'context' => 'normal',
    'priority'  => 'default',

    'include' => array(
      'user_role'  => array( 'administrator', 'primary_department_homepage_editor', 'editor' ),
    ),
    'visible' => array(
      'when' => array(
        array( 'phila_template_select', '=', 'homepage_v2'),
        array( 'phila_template_select', '=', 'staff_directory_v2'),
      ),
      'relation' => 'or',
    ),

    'fields'  => array(
      array(
        'desc'  => 'Use this section to create an accordion-style list of people who don\'t formally work for the City of Philadelphia. List will appear in the order below.',

        'id'  => 'section_title',
        'type'  => 'text',
        'name'  => 'Optional row title',
      ),
      array(
        'id'  => 'table_head_title',
        'name'  => 'Rename table "title" cell',
        'type'  => 'text',
        'desc'  => 'The staff table column label defaults to "title". Use this to change it.'
      ),
      Phila_Gov_Standard_Metaboxes::phila_meta_var_member_list()
    ),

  );

  $meta_boxes[] = array(
    'id'       => 'phila_full_row_press_releases',
    'title'    => 'Full row press releases posts (3 total)',
    'pages'    => array( 'department_page', 'guides' ),
    'context'  => 'normal',
    'priority' => 'default',

    'include' => array(
      'user_role'  => array( 'administrator', 'primary_department_homepage_editor', 'editor' ),
    ),
    'visible' => array(
      'when' => array(
        array( 'phila_template_select', '=', 'homepage_v2'),
        array( 'phila_template_select', '=', 'guide_landing_page' )
      ),
      'relation'  => 'or'
    ),
    'fields' => array(
      array(
        'name'  => 'Display a full row of press releases?',
        'id'   => 'phila_full_row_press_releases_selected',
        'type' => 'switch',
        'on_label'  => 'Yes',
        'off_label' => 'No',
        'after' => '<p class="description">Enter at least three press releases in the <a href="/wp-admin/edit.php?post_type=press_release">Press release</a> section.</p>'
      ),
      array(
        'id'  => 'phila_get_press_cats',
        'type' => 'group',
        'visible' => array(
          'when' => array(
            array( 'phila_full_row_press_releases_selected', '=', 1 ),
          ),
          'relation' => 'or',
        ),
        'fields' => array(
          Phila_Gov_Standard_Metaboxes::phila_metabox_category_picker('Select owners', 'phila_press_release_category', 'Display press releases from these owners.' ),
          array(
            'name'  => 'Filter by a tag',
            'id'  => 'tag',
            'type' => 'taxonomy_advanced',
            'taxonomy'  => 'post_tag',
            'field_type' => 'select_advanced',
            'desc'  => 'Display press releases using this tag. "See all" will pre-filter on these terms.'
          ),
        ),
      ),
    ),
  );

  $meta_boxes[] = array(
    'id'       => 'phila_full_row_blog',
    'title'    => 'Full row blog posts (3 total)',
    'pages'    => array( 'department_page', 'guides' ),
    'context'  => 'normal',
    'priority' => 'high',

    'include' => array(
      'user_role'  => array( 'administrator', 'primary_department_homepage_editor', 'editor' ),
    ),
    'visible' => array(
      'when' => array(
        array( 'phila_template_select', '=', 'homepage_v2' ),
        array( 'phila_template_select', '=', 'guide_landing_page' )
      ),
      'relation' => 'or',
    ),

    'fields' => array(
      array(
        'name' => 'Display a full row of blog posts?',
        'id'   => 'phila_full_row_blog_selected',
        'type' => 'switch',
        'on_label'  => 'Yes',
        'off_label' => 'No',
        'after' => '<p class="description">Enter at least three blog posts in the <a href="/wp-admin/edit.php?post_type=phila_post">Blog Post</a> section.</p>'
      ),
      array(
        'id'  => 'phila_get_post_cats',
        'type' => 'group',
        'visible' => array(
          'when' => array(
            array( 'phila_full_row_blog_selected', '=', 1 ),
          ),
          'relation' => 'or',
        ),
        'fields' => array(
          Phila_Gov_Standard_Metaboxes::phila_metabox_category_picker('Select owners', 'phila_post_category', 'Display posts from these owners. This will override page ownership selection.'),
          array(
            'name'  => 'Filter by a tag',
            'id'  => 'tag',
            'type' => 'taxonomy_advanced',
            'taxonomy'  => 'post_tag',
            'field_type' => 'select_advanced',
            'desc'  => 'Display posts using this tag. "See all" will pre-filter on these terms.'
          ),
          Phila_Gov_Standard_Metaboxes::phila_metabox_url('See all link override', 'override_url', '', 12 ),
        ),
      ),
    ),
  );

  $meta_boxes[] = array(
    'id'       => 'phila_full_row_announcements',
    'title'    => 'Full row announcements',
    'pages'    => array( 'department_page', 'guides' ),
    'context'  => 'normal',
    'priority' => 'high',

    'include' => array(
      'user_role'  => array( 'administrator', 'primary_department_homepage_editor', 'editor' ),
    ),

    'visible' => array(
      'when' => array(
        array( 'phila_template_select', '=', 'homepage_v2' ),
        array( 'phila_template_select', '=', 'guide_landing_page' )
      ),
      'relation' => 'or',
    ),

    'fields' => array(
      array(
        'name' => 'Display a full row of announcements?',
        'id'   => 'phila_full_row_announcements_selected',
        'type' => 'switch',
        'on_label'  => 'Yes',
        'off_label' => 'No',
      ),
      array(
        'id'  => 'phila_get_ann_cats',
        'type' => 'group',
        'visible' => array(
          'when' => array(
            array( 'phila_full_row_announcements_selected', '=', 1 ),
          ),
          'relation' => 'or',
        ),
        'fields' => array(
          Phila_Gov_Standard_Metaboxes::phila_metabox_category_picker('Select owners', 'phila_announcement_category', 'Display announcements from these owners. This will override page ownership selection.'),
          array(
            'name'  => 'Filter by a tag',
            'id'  => 'announcements_tag',
            'type' => 'taxonomy_advanced',
            'taxonomy'  => 'post_tag',
            'field_type' => 'select_advanced',
            'desc'  => 'Display announcements using this tag.'
          ),
        ),
      ),
    ),
  );

  $meta_boxes[] = array(
    'id'  => 'phila_call_to_action_multi',
    'title' => 'Call to action cards (resources)',
    'pages' => array( 'department_page' ),
    'context' => 'normal',
    'priority' => 'default',
    'revision' => true,

    'include' => array(
      'user_role'  => array( 'administrator', 'primary_department_homepage_editor', 'editor' ),
    ),
    'visible' => array(
      'when'  => array(
        array('phila_template_select', '=', 'homepage_v2' ),
      ),
    ),

    'fields' => array(
      array(
        'id'  => 'phila_call_to_action_section',
        'type' => 'group',

        'fields' => array(
          array(
            'name'  => 'Section Title',
            'id'    => 'phila_action_section_title_multi',
            'type'  => 'text',
          ),
          array(
            'name'  =>  'See all title (optional)',
            'id'    => 'phila_url_title',
            'type'  => 'text',
          ),
          array(
            'name'  =>  'See all URL (optional)',
            'id'    => 'phila_url',
            'type'  => 'url',
          ),

          array(
            'id'  => 'phila_call_to_action_multi_group',
            'type' => 'group',
            'clone'  => true,
            'max_clone' => 4,
            'sort_clone' => true,

            'fields' => Phila_Gov_Standard_Metaboxes::phila_call_to_action_group_content()
          ),
        ),
      ),
    ),
  );

/**
*
* Begin MetaBox Field Arrays
*
**/

//Clonable WYSIWYG with title
$meta_var_wysiwyg_multi = array(
  'id'  =>  'phila_cloneable_wysiwyg',
  'type'  => 'group',
  'clone' => true,
  'sort_clone'  => true,
  'add_button'  => '+ Add a section',

  'fields'  => array(
    array(
      'placeholder'  => 'Section Heading',
      'id'  => 'phila_wysiwyg_heading',
      'type'  => 'text',
      'class' => 'percent-100'
    ),
    array(
      'id'  => 'phila_wysiwyg_content',
      'type'  => 'wysiwyg',
      'options' => Phila_Gov_Standard_Metaboxes::phila_wysiwyg_options_basic()
    )
  )
);


$meta_boxes[] = array(
  'title' => 'Service Stub',
  'pages' => array('service_page'),
  'context' => 'after_title',
  'priority' => 'low',
  'visible' => array('phila_template_select', 'service_stub'),
  'revision' => true,

  'fields'  => array(
    array(
      'name' => 'Page source',
      'type'  => 'heading'
    ),
    array(
      'id' => 'phila_stub_source',
      'type' => 'post',
      'post_type' => 'service_page',
      'desc'  => 'Display content from the selected page on the front-end.',
      'query_args'  => array(
        'post_status'    => array('publish', 'draft', 'private'),
        'posts_per_page' => - 1,
        'meta_key' => 'phila_template_select',
        'meta_value' => 'service_stub',
        'meta_compare' => '!=',
      ),
    )
  )
);

$meta_boxes[] = array(
  'title' => 'Department Stub',
  'pages' => array('department_page'),
  'context' => 'after_title',
  'priority' => 'low',
  'visible' => array(
    'when'  => array(
      array('phila_template_select', '=', 'department_stub'),
    ),
  ),
  'revision' => true,

  'fields'  => array(
    array(
      'name' => 'Page source',
      'type'  => 'heading',
    ),
    array(
      'id' => 'phila_stub_source',
      'type' => 'post',
      'post_type' => 'department_page',
      'desc'  => 'Display content from the selected page on the front-end.',
      'query_args'  => array(
        'post_status'    => array('publish', 'draft', 'private'),
        'posts_per_page' => - 1,
        'meta_key' => 'phila_template_select',
        'meta_value' => 'department_stub',
        'meta_compare' => '!=',
        'post_parent__not_in' => array('0')
      ),
    )
  )
);

$meta_boxes[] = array(
  'title' => 'Program + initiatives association',
  'pages' => array('service_page'),
  'context'  => 'side',
  'fields'  => array(
    array(
      'id'  => 'display_prog_init',
      'name'  => 'Should this page appear as "Related content" on the programs and initiatives landing page?',
      'type'  => 'switch',
      'on_label'  => 'Yes',
      'off_label' => 'No'
    )
  ),
);

$meta_boxes[] = array(
  'title' => 'Vuejs app',
  'pages' => array ( 'service_page' ),
  'revision' => false,
  'priority' => 'high',

  'visible' => array(
    'when'  => array(
      array('phila_template_select', '=', 'vue_app'),
    ),
  ),
  'fields' => array(
    array (
      'id'  => 'vue_app_title',
      'name'  => 'Optional title',
      'type'=> 'text'
    ),
    array(
      'id' => 'phila_vue_app',
      'type'  => 'group',
      'fields' => Phila_Vue_App_Files::phila_vue_metaboxes()
    )
  ),
);


$meta_boxes[] = array(
  'title' => 'Topic Page Options',
  'pages' => array('service_page'),
  'context' => 'after_title',
  'priority' => 'low',
  'visible' => array('phila_template_select', 'topic_page'),

  'fields'  => array(
    array(
      'name' => 'Hide child pages',
      'type'  => 'heading'
    ),
    array(
      'id'  => 'phila_hide_children',
      'type' => 'switch',
      'on_label'  => 'Yes',
      'off_label' => 'No',
      'name'  => 'Should the children of this page be hidden in the service directory?',
      'columns' => 6,

    ),
    array(
      'name' => 'Icon selection',
      'type'  => 'heading'
    ),
    Phila_Gov_Standard_Metaboxes::phila_metabox_v2_phila_text('', 'phila_page_icon', false, 'Choose a <a href="https://fontawesome.com/icons?d=gallery" target="_blank">Font Awesome</a> icon to represent a top-level page. E.g.: fas fa-bell.'),
  )
);

$meta_boxes[] = array(
  'title' => 'Heading Groups',
  'pages' => array('department_page', 'page', 'service_page', 'programs'),
  'revision' => true,

  'visible' => array(
    'when' => array(
      array( 'phila_template_select', '=', ''),
      array( 'phila_template_select', '=', 'one_quarter_headings_v2' ),
      array( 'phila_template_select', '=', 'phila_one_quarter' ),
      array( 'phila_template_select', '=', 'default'),
      array( 'phila_template_select', '=', 'start_process'),
      array( 'phila_template_select', '=', 'default_v2' ),

    ),
    'relation' => 'or',
  ),

  'fields' => array(
    array(
      'id' => 'phila_heading_groups',
      'type'  => 'group',
      'clone' => false,

      'fields' => array(
        Phila_Gov_Standard_Metaboxes::phila_metabox_v2_address_fields_unique(),
      ),
    )
  )
);


$meta_boxes[] = array(
  'title' => 'FAQ groups',
  'pages' => array('department_page', 'programs'),
  'revision' => true,

  'visible' => array(
    'when' => array(
      array( 'phila_template_select', '=', 'one_quarter_headings_v2' ),
      array( 'phila_template_select', '=', 'phila_one_quarter' ),
    ),
    'relation' => 'or',
  ),

  'fields' => array(
    array(
      'id'  => 'accordion_search',
      'type'  => 'checkbox',
      'name'  => 'Include in-page search on FAQ page?'
    ),
    array(
      'id' => 'accordion_row',
      'type' => 'group',
      'clone'  => true,
      'sort_clone' => true,
      'add_button' => '+ Add FAQ group',

      'fields'  => array (
        array(
          'name' => 'Section title',
          'id'   => 'accordion_row_title',
          'type' => 'text',
          'class' => 'percent-90',
        ),
        array(
          'id'   => 'accordion_group',
          'type' => 'group',
          'clone'  => true,
          'sort_clone' => true,
          'add_button' => '+ Add FAQ',
          'fields' => array(
            Phila_Gov_Standard_Metaboxes::phila_metabox_double_wysiwyg($section_name = 'FAQ title', $wysiwyg_desc = 'FAQ content', $columns = 12, $clone = true ),
          )
        )
      ),
    ),
  ),
);

$meta_boxes[] = array(
  'title' => 'Additional Content',
  'pages' => array ( 'service_page', 'programs' ),
  'revision' => true,
  'context'  => 'advanced',
  // see this partial to add more page templates - DD phila.gov-theme/partials/content-additional.php
  'hidden' => array(
    'when'  => array(
      array('phila_template_select', '=', 'topic_page'),
      array('phila_template_select', '=', 'service_stub'),
      array('phila_template_select', '=', 'default_v2'),
      array('phila_template_select', '=', 'custom_content'),
      array('phila_template_select', '=', 'translated_content'),
      array('phila_template_select', '=', 'covid_guidance'),
    ),
    'relation' => 'or',
  ),

  'fields' =>   Phila_Gov_Standard_Metaboxes::phila_meta_var_addtional_content()
);

$meta_boxes[] = array(
  'title' => 'Document tables',
  'pages' => array('department_page', 'programs'),
  'revision' => true,
  'visible' => array(
    'when'  => array(
      array('phila_template_select', '=', 'document_finder_v2'),
    ),
  ),
  'fields' => array(
    array (
      'id'  => 'phila_vue_toggle',
      'type'  => 'switch',
      'name'  => 'Use data tables?',
      'on_label'  => 'Yes',
      'off_label' => 'No'
    ),
    array(
      'id'  => 'phila_vue_app_title',
      'type'  => 'text',
      'name'  => 'Optional title for document tables section',
      'visible' => array(
        'when'  => array(
          array('phila_vue_toggle', '=', true),
        ),
      ),
    ),
    array(
      'id'  => 'phila_doc_no_paginate',
      'type'  => 'switch',
      'name'  => 'Turn off pagination for all tables on this page?',
      'on_label'  => 'Yes',
      'off_label' => 'No',
    ),
    array(
      'id' => 'phila_document_table',
      'type'  => 'group',
      'clone' => true,
      'sort_clone' => true,
      'add_button'  => 'Add another table',
      'fields' =>
      array(
        Phila_Gov_Standard_Metaboxes::phila_metabox_v2_wysiwyg( $section_title = 'Table title', $wysiwyg_desc = 'Enter a description to describe the contents of this table for users with screenreaders. '),
        array(
          'id'  => 'phila_doc_label_column_title',
          'type'  => 'text',
          'name'  => 'Optional label column title (will add label column)',
          'class' => 'margin-bottom-10',
          'visible' => array(
            'when'  => array(
              array('phila_vue_toggle', '=', true),
            ),
          ),
        ),
        array(
          'id'  => 'phila_doc_label_sort',
          'name'  => 'Default table to sort by label column (if it exists)',
          'type'  => 'radio',
          'std'=> '0',
          'options' =>  array(
              '0' => 'None',
              'ascending' => 'Ascending',
              'descending' => 'Descending'
          ),
          'class' => 'margin-bottom-10',
          'visible' => array(
            'when'  => array(
              array('phila_vue_toggle', '=', true),
            ),
          ),
        ),
        array(
          'id'  => 'phila_search_bar_text',
          'type'  => 'text',
          'name'  => 'Text for the document search',
          'desc'  => 'Defaults to: Begin typing to filter documents',
        ),
        array(
          'id'  => 'phila_reverse_category_order',
          'type'  => 'switch',
          'name'  => 'Display categories in reverse alphabetical order',
          'desc'  => 'Default: alphabetical order',
          'on_label'  => 'Yes',
          'off_label' => 'No',
          'visible' => array(
            'when'  => array(
              array('phila_vue_toggle', '=', true),
            ),
          ),
        ),
        array(
          'id'  => 'phila_hide_date_column',
          'type'  => 'switch',
          'name'  => 'Hide date column',
          'desc'  => 'Default: visible dates',
          'on_label'  => 'Yes',
          'off_label' => 'No',
          'visible' => array(
            'when'  => array(
              array('phila_vue_toggle', '=', true),
            ),
          ),
        ),
        array(
          'name'  => 'Add files to table',
          'id'    => 'phila_files',
          'type'  => 'file_advanced',
          'class' =>  'add-files',
          'mime_type' => 'application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document,
          application/vnd.ms-powerpointtd, application/vnd.openxmlformats-officedocument.presentationml.presentation,
          application/vnd.ms-excel,
          application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
          text/csv,
          text/plain,
          application/zip'
        ),
      )
    ),
  ),
);

$meta_boxes[] = array(
  'title' => 'Our services',
  'pages'    => array( 'department_page', 'programs' ),
  'visible' => array(
    'when'  =>  array(
        array('phila_template_select', '=', 'homepage_v2'),
        array('phila_template_select', '=', 'homepage_v3'),
        array('phila_template_select', '=', 'prog_landing_page')
      ),
    'relation'  => 'or'
  ),
  'fields' => Phila_Gov_Standard_Metaboxes::phila_our_services(),
);

$meta_boxes[] = array(
  'id'       => 'homepage_programs',
  'title'    => 'Our programs',
  'pages'    => array( 'department_page' ),
  'context'  => 'normal',
  'visible' => array(
    'when'  => array(
      array('phila_template_select', '=', 'homepage_v2' ),
    ),
  ),
  'fields' =>
    Phila_Gov_Standard_Metaboxes::phila_program_page_selector($multiple = true),

);


$meta_boxes[] = array(
  'id'       => 'phila_custom_markup',
  'title'    => 'Custom Markup',
  'pages'    => array( 'department_page', 'page', 'service_page', 'programs', 'guides', 'event_spotlight' ),
  'context'  => 'advanced',
  'priority' => 'low',

    'include' => array(
      'user_role'  => array( 'administrator', 'primary_department_homepage_editor', 'editor' ),
    ),

  'fields' => array(
    array(
      'name' => 'Description',
      'id'   => 'phila_custom_markup_description',
      'type' => 'custom_html',
      'std'  => '<span>Use this area to insert CSS, HTML or JS.</span>',
    ),
    array(
      'name' => 'Append to head',
      'id'   => 'phila_append_to_head',
      'type' => 'textarea',
      'sanitize_callback' => 'none',
    ),
    array(
      'name' => 'Append before WYSIWYG',
      'id'   => 'phila_append_before_wysiwyg',
      'type' => 'textarea',
      'sanitize_callback' => 'none',

    ),
    array(
      'name' => 'Append after WYSIWYG',
      'id'   => 'phila_append_after_wysiwyg',
      'type' => 'textarea',
      'sanitize_callback' => 'none',

    ),
    array(
      'name' => 'Append after footer',
      'id'   => 'phila_append_after_footer',
      'type' => 'textarea',
      'sanitize_callback' => 'none',
    ),
  ),
);


$meta_boxes[] = array(
  'title' => 'Disclaimer Modal',
  'pages'    => array( 'department_page', 'programs' ),
  'visible' => array(
    'when'  =>  array(
        array('phila_template_select', '=', 'homepage_v2'),
        array('phila_template_select', '=', 'homepage_v3'),
        array('phila_template_select', '=', 'prog_landing_page')
      ),
    'relation'  => 'or'
  ),
  'fields' => Phila_Gov_Standard_Metaboxes::phila_disclaimer_modal(),
);


$meta_boxes[] = array(
  'title' => 'Prereq Row',
  'id'       => 'prereq_row',
  'pages' => array ( 'service_page' ),
  'revision' => true,
  'context'  => 'advanced',

  'visible' => array(
    'when'  => array(
      array('phila_template_select', '=', 'default_v2'),
      array('phila_template_select', '=', 'default')
    ),
    'relation' => 'or',
  ),

  'fields' =>   Phila_Gov_Standard_Metaboxes::phila_meta_prereq_row('Prerequisite row title')
);


$meta_boxes[] = array(
  'title' => 'Timeline',
  'pages' => array('department_page', 'programs'),
  'revision' => true,
  'visible' => array(
    'when'  => array(
      array('phila_template_select', '=', 'timeline'),
    ),
  ),
  'fields' => Phila_Gov_Standard_Metaboxes::phila_metabox_timeline()
);

$meta_boxes[] = array(
  'id'       => 'homepage_timeline',
  'title'    => 'Homepage timeline',
  'pages'    => array( 'department_page' ),
  'context'  => 'normal',
  'visible' => array(
    'when'  => array(
      array('phila_template_select', '=', 'homepage_v2' ),
    ),
  ),
  'fields' => array(
    Phila_Gov_Standard_Metaboxes::phila_timeline_page_selector(),
    array(
      'name' => 'Timeline item count',
      'id'   => 'homepage_timeline_item_count',
      'desc'  => 'Select the number of items from the timeline to display',
      'type' => 'number'
    ),
  )

);

$meta_boxes[] = array(
  'title' => 'Translated content',
  'pages'    => array( 'programs' ),
  'revision' => true,
  'visible' => array(
    'when'  =>  array(
        array('phila_template_select', '=', 'translated_content'),
        array('phila_template_select', '=', 'covid_guidance'),
      ),
    'relation'  => 'or'
  ),
  'fields' => array(
    array(
      'id'       => 'phila_v2_translated_content',
      'title'    => 'Translated content',
      'context'  => 'normal',
      'priority' => 'high',
      'type'  => 'group',
      'clone' => true,
      'sort_clone' => true,
      'add_button'  => '+ Add another translation',

      'fields' => array(
        Phila_Gov_Standard_Metaboxes::phila_language_selector( 'translated_language', 'margin-bottom-10' ),
        Phila_Gov_Standard_Metaboxes::phila_metabox_v2_wysiwyg(),
      ),
    ),
  ),
);

return $meta_boxes;

}

/**
 *
 * Returns true/false based on existence of secondary role. Metabox user_role doesn't see secondary roles, so a custom function is required.
 *
 **/
add_action( 'admin_head', 'phila_master_homepage_editor' );

function phila_master_homepage_editor(){

  $assigned_roles = array_values( wp_get_current_user()->roles );

  if (in_array( 'secondary_master_homepage_editor', $assigned_roles )){
    return true;
  }
  return false;
}