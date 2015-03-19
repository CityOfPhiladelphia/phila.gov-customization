<?php
/**
 * @package Make
 */

if ( ! class_exists( 'TTFMAKE_Section_Definitions_TEST' ) ) :
/**
 * Collector for builder sections.
 *
 * @since 1.0.0.
 *
 * Class TTFMAKE_Section_Definitions_TEST
 */
class TTFMAKE_Section_Definitions_TEST {
	/**
	 * The one instance of TTFMAKE_Section_Definitions_TEST.
	 *
	 * @since 1.0.0.
	 *
	 * @var   TTFMAKE_Section_Definitions_TEST
	 */
	private static $instance;

	/**
	 * Instantiate or return the one TTFMAKE_Section_Definitions_TEST instance.
	 *
	 * @since  1.0.0.
	 *
	 * @return TTFMAKE_Section_Definitions_TEST
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register the sections.
	 *
	 * @since  1.0.0.
	 *
	 * @return TTFMAKE_Section_Definitions_TEST
	 */
	public function __construct() {
		// Register all of the sections via the section API
		$this->register_phila_gov_section();

		// Add the section JS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Add additional templating
		add_action( 'admin_footer', array( $this, 'print_templates' ) );
	}

	/**
	 * Register the text section.
	 *
	 * Note that in 1.4.0, the "text" section was renamed to "columns". In order to provide good back compatibility,
	 * only the section label is changed to "Columns". All other internal references for this section will remain as
	 * "text".
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function register_phila_gov_section() {
		ttfmake_add_section(
			'phila_gov',
			_x( 'Phila_Gov_Section', 'section name', 'make' ),
			get_template_directory_uri() . '/inc/builder/sections/css/images/text.png',
			__( 'PHILA.GOV YEAH.', 'make' ),
			array( $this, 'save_phila_gov' ),
			get_template_directory_uri() . '/../../plugins/phila.gov-customization/make-columns-test/sections/builder-templates/phila_gov',
			get_template_directory_uri() . '/../../plugins/phila.gov-customization/make-columns-test/sections/front-end-tempaltes/phila_gov',
			100,
			'inc/builder/',
			array(
				100 => array(
					'type'  => 'section_title',
					'name'  => 'title',
					'label' => __( 'Enter section title', 'make' ),
					'class' => 'ttfmake-configuration-title ttfmake-section-header-title-input',
				),
				200 => array(
					'type'    => 'select',
					'name'    => 'columns-number',
					'class'   => 'ttfmake-phila_gov-columns',
					'label'   => __( 'phila_gov', 'make' ),
					'default' => 2,
					'options' => array(
						1 => 1,
						2 => 2,
						3 => 3,
						4 => 4,
					),
				),
			)
		);
	}

	/**
	 * Save the data for the text section.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $data    The data from the $_POST array for the section.
	 * @return array             The cleaned data.
	 */
	public function save_phila_gov( $data ) {
		$clean_data = array();

		if ( isset( $data['columns-number'] ) ) {
			if ( in_array( $data['columns-number'], range( 1, 4 ) ) ) {
				$clean_data['columns-number'] = $data['columns-number'];
			}
		}

		$clean_data['title'] = $clean_data['label'] = ( isset( $data['title'] ) ) ? apply_filters( 'title_save_pre', $data['title'] ) : '';

		if ( isset( $data['columns-order'] ) ) {
			$clean_data['columns-order'] = array_map( array( 'TTFMAKE_Builder_Save', 'clean_section_id' ), explode( ',', $data['columns-order'] ) );
		}

		if ( isset( $data['columns'] ) && is_array( $data['columns'] ) ) {
			foreach ( $data['columns'] as $id => $item ) {
				if ( isset( $item['title'] ) ) {
					$clean_data['columns'][ $id ]['title'] = apply_filters( 'title_save_pre', $item['title'] );
				}

				if ( isset( $item['image-link'] ) ) {
					$clean_data['columns'][ $id ]['image-link'] = esc_url_raw( $item['image-link'] );
				}

				if ( isset( $item['image-id'] ) ) {
					$clean_data['columns'][ $id ]['image-id'] = ttfmake_sanitize_image_id( $item['image-id'] );
				}

				if ( isset( $item['content'] ) ) {
					$clean_data['columns'][ $id ]['content'] = sanitize_post_field( 'post_content', $item['content'], ( get_post() ) ? get_the_ID() : 0, 'db' );
				}
			}
		}

		return $clean_data;
	}


	/**
	 * Enqueue the JS and CSS for the admin.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $hook_suffix    The suffix for the screen.
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {
		// Only load resources if they are needed on the current page
		if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) || ! ttfmake_post_type_supports_builder( get_post_type() ) ) {
			return;
		}


		wp_register_script(
			'ttfmake-sections/js/views/phila_gov.js',
			get_template_directory_uri() . '/../../plugins/phila.gov-customization/make-columns-test/js/views/phila_gov.js',
			array(),
			TTFMAKE_VERSION,
			true
		);

		// Add additional dependencies to the Builder JS
		add_filter( 'ttfmake_builder_js_dependencies', array( $this, 'add_js_dependencies' ) );

		// Add the section CSS
		wp_enqueue_style(
			'ttfmake-sections/css/sections.css',
			get_template_directory_uri() . '/inc/builder/sections/css/sections.css',
			array(),
			TTFMAKE_VERSION,
			'all'
		);
	}

	/**
	 * Append more JS to the list of JS deps.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $deps    The current deps.
	 * @return array             The modified deps.
	 */
	public function add_js_dependencies( $deps ) {
		if ( ! is_array( $deps ) ) {
			$deps = array();
		}

		return array_merge( $deps, array(

			'ttfmake-sections/js/views/phila_gov.js',

		) );
	}

}



endif;

/**
 * Instantiate or return the one TTFMAKE_Section_Definitions_TEST instance.
 *
 * @since  1.0.0.
 *
 * @return TTFMAKE_Section_Definitions_TEST
 */
function ttfmake_get_section_definitions_test() {
	return TTFMAKE_Section_Definitions_TEST::instance();
}

// Kick off the section definitions immediately
if ( is_admin() ) {
	add_action( 'after_setup_theme', 'ttfmake_get_section_definitions_test', 11 );
}
