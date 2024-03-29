<?php

class Phila_Pages_Controller {

  // Initialize the namespace and resource name.
  public function __construct() {
    $this->namespace     = 'pages/v1';
    $this->resource_name = 'page';
  }

  // Register our routes.
  public function register_routes() {
  // Register the endpoint for collections.
    register_rest_route( $this->namespace, '/' . $this->resource_name, array(
      array(
        'methods'   => WP_REST_Server::READABLE,
        'callback'  => array( $this, 'get_items' ),
        'permission_callback' => '__return_true',
      ),
      'schema' => array( $this, 'get_item_schema' ),
    ) );
  }

  /**
   * Return all pages
   *
   * @param WP_REST_Request $request Current request.
  */
  public function get_items( $request ) {

    if ($request['department']) {
      $department_id = $request['department'];

      $posts = new WP_Query( array(
        'category__in' => $department_id,
        'post_type' => array('post', 'page', 'service_updates', 'site_wide_alert', 'document', 'staff_directory', 'announcement', 'service_page', 'department_page', 'event_spotlight', 'guides', 'programs', 'calendar', 'phila_post', 'news_post', 'press_release'),
        'posts_per_page'  => -1,
        'order' => 'asc',
        'orderby' => 'title',
        'post_status' => 'publish',
      ) );
    } else {
      $posts = new WP_Query( array(
        'post_type' => array('post', 'page', 'service_updates', 'site_wide_alert', 'document', 'staff_directory', 'announcement', 'service_page', 'department_page', 'event_spotlight', 'guides', 'programs', 'calendar', 'phila_post', 'news_post', 'press_release'),
        'posts_per_page'  => -1,
        'order' => 'asc',
        'orderby' => 'title',
        'post_status' => 'publish',
      ) );
    }

    if ( $posts->have_posts() ) {
      $pages = array();
      while ( $posts->have_posts() ) {
        $posts->the_post();
        $terms = get_the_terms( get_the_id(), 'category' ); 
        if ($terms) {
          $term_names = '';
          $first = true;
          foreach($terms as $term) {
            if ($first) {
              $first = false;
              $term_names .= $term->name;
            }
            else {
              $term_names .= '; '.$term->name;
            }
          }
          foreach($terms as $term) {
            $page = new stdClass();
            $page->id = get_the_id();
            $page->last_modified = get_the_modified_time('F jS, Y') . ', ' . get_the_modified_time();
            if (get_userdata( rwmb_meta( '_edit_last', $args = array(), $post_id = get_the_id() ) )) {
              $user = get_userdata( rwmb_meta( '_edit_last', $args = array(), $post_id = get_the_id() ) );
              $page->last_modified_user = $user->first_name . ' ' . $user->last_name;
            }
            else {
              $page->last_modified_user = '';
            }
            $page->owner = $term->name;
            $page->all_owners = $term_names;
            $page->post_type = get_post_type_object(get_post_type())->labels->singular_name;
            $page->short_description = rwmb_meta( 'phila_meta_desc', $args = array(), $post_id = get_the_id() );
            $page->status = get_post_status();
            $page->template = rwmb_meta( 'phila_template_select', $args = array(), $post_id = get_the_id() );
            $page->title = get_the_title();
            $page->url = get_the_permalink();
            array_push($pages, $page);
          }
        }
      }
      wp_reset_postdata();
    }

    foreach ( $pages as $page ) {
      $response = $this->prepare_item_for_response( $page, $request );

      $data[] = $this->prepare_response_for_collection( $response );
    }

    // Return all response data.
    return rest_ensure_response( $data );

  }

  /**
   * Matches the post data to the schema. Also, rename the fields to nicer names.
   *
   * @param WP_Post $post The comment object whose response is being prepared.
   */

  public function prepare_item_for_response( $post, $request ) {
    $post_data = array();

    $schema = $this->get_item_schema( $request );
    $post_data['id'] = (integer) $post->id ?? '';
    $post_data['last_modified'] = (string) $post->last_modified ?? '';
    $post_data['last_modified_user'] = (string) $post->last_modified_user ?? '';
    $post_data['owner'] = (string) $post->owner ?? '';
    $post_data['all_owners'] = (string) $post->all_owners ?? '';
    $post_data['post_type'] = (string) $post->post_type ?? '';
    $post_data['short_description'] = (string) $post->short_description ?? '';
    $post_data['status'] = (string) $post->status ?? '';
    $post_data['template'] = (string) $post->template ?? '';
    $post_data['title'] = (string) $post->title ?? '';
    $post_data['url'] = (string) $post->url ?? '';


    return rest_ensure_response( $post_data );
}

  /**
   * Prepare a response for inserting into a collection of responses.
   *
   * This is copied from WP_REST_Controller class in the WP REST API v2 plugin.
   *
   * @param WP_REST_Response $response Response object.
   * @return array Response data, ready for insertion into collection data.
   */
  public function prepare_response_for_collection( $response ) {
    if ( ! ( $response instanceof WP_REST_Response ) ) {
      return $response;
    }

    $data = (array) $response->get_data();
    $server = rest_get_server();

    if ( method_exists( $server, 'get_compact_response_links' ) ) {
      $links = call_user_func( array( $server, 'get_compact_response_links' ), $response );
    } else {
      $links = call_user_func( array( $server, 'get_response_links' ), $response );
    }

    if ( ! empty( $links ) ) {
      $data['_links'] = $links;
    }

    return $data;
  }

  /**
   * Get sample schema for a collection.
   *
   * @param WP_REST_Request $request Current request.
   */
  public function get_item_schema( $request ) {
    $schema = array(
      // This tells the spec of JSON Schema we are using which is draft 4.
      '$schema'              => 'http://json-schema.org/draft-04/schema#',
      // The title property marks the identity of the resource.
      'title'                => 'post',
      'type'                 => 'object',
      // Specify object properties in the properties attribute.
      'properties'           => array(
        'id' => array(
          'description' => esc_html__('item', 'id'),
          'type' => 'integer',
          'readonly' => true,
        ),
        'last_modified' => array(
          'description' => esc_html__('item', 'last modified time'),
          'type' => 'string',
          'readonly' => true,
        ),
        'last_modified_user' => array(
          'description' => esc_html__('item', 'last modified user name'),
          'type' => 'string',
          'readonly' => true,
        ),
        'owner' => array(
          'description' => esc_html__('item', 'owner'),
          'type' => 'string',
          'readonly' => true,
        ),
        'all_owners' => array(
          'description' => esc_html__('item', 'all owners'),
          'type' => 'string',
          'readonly' => true,
        ),
        'post_type' => array(
          'description' => esc_html__('item', 'post type'),
          'type' => 'string',
          'readonly' => true,
        ),
        'short_description' => array(
          'description' => esc_html__('item', 'short description'),
          'type' => 'string',
          'readonly' => true,
        ),
        'status' => array(
          'description' => esc_html__('item', 'status'),
          'type' => 'string',
          'readonly' => true,
        ),
        'template' => array(
          'description' => esc_html__('item', 'page template'),
          'type' => 'string',
          'readonly' => true,
        ),
        'title' => array(
          'description' => esc_html__('item', 'page title'),
          'type' => 'string',
          'readonly' => true,
        ),
        'url' => array(
          'description' => esc_html__('item', 'page url'),
          'type' => 'string',
          'readonly' => true,
        )
      ),
    );

    return $schema;
  }

}

// Function to register our new routes from the controller.
function phila_register_pages_rest_routes() {
  $controller = new Phila_Pages_Controller();
  $controller->register_routes();
}

add_action( 'rest_api_init', 'phila_register_pages_rest_routes' );