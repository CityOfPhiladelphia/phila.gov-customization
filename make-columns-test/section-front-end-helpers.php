<?php
/**
 * @package Make
 */

/**
 * Determine if a section of a specified type.
 *
 * @since  1.0.0.
 *
 * @param  string    $type    The section type to check.
 * @param  array     $data    The section data.
 * @return bool               True if the section is the specified type; false if it is not.
 */
function ttfmake_builder_is_section_type( $type, $data ) {
	$is_section_type = ( isset( $data['section-type'] ) && $type === $data['section-type'] );

	/**
	 * Allow developers to alter if a set of data is a specified section type.
	 *
	 * @since 1.2.3.
	 *
	 * @param bool      $is_section_type    Whether or not the data represents a specific section.
	 * @param string    $type               The section type to check.
	 * @param array     $data               The section data.
	 */
	return apply_filters( 'make_builder_is_section_type', $is_section_type, $type, $data );
}


/**
 * Get the columns data for a text section.
 *
 * @since  1.0.0.
 *
 * @param  array     $ttfmake_section_data    The section data.
 * @return array                              Array of data for columns in a text section.
 */
function ttfmake_builder_get_text_array( $ttfmake_section_data ) {
	if ( ! ttfmake_builder_is_section_type( 'phila_gov', $ttfmake_section_data ) ) {
		return array();
	}

	$columns_number = ( isset( $ttfmake_section_data['columns-number'] ) ) ? absint( $ttfmake_section_data['columns-number'] ) : 1;

	$columns_order = array();
	if ( isset( $ttfmake_section_data['columns-order'] ) ) {
		$columns_order = $ttfmake_section_data['columns-order'];
	}

	$columns_data = array();
	if ( isset( $ttfmake_section_data['columns'] ) ) {
		$columns_data = $ttfmake_section_data['columns'];
	}

	$columns_array = array();
	if ( ! empty( $columns_order ) && ! empty( $columns_data ) ) {
		$count = 0;
		foreach ( $columns_order as $order => $key ) {
			$columns_array[$order] = $columns_data[$key];
			$count++;
			if ( $count >= $columns_number ) {
				break;
			}
		}
	}

	/**
	 * Filter the array of builder data for the text section.
	 *
	 * @since 1.2.3.
	 *
	 * @param array    $columns_array           The ordered data for the text section.
	 * @param array    $ttfmake_section_data    The raw section data.
	 */
	return apply_filters( 'make_builder_get_text_array', $columns_array, $ttfmake_section_data );
}

/**
 * Get the class for the text section.
 *
 * @since  1.0.0.
 *
 * @param  array     $ttfmake_section_data    The section data.
 * @param  array     $sections                The list of sections.
 * @return string                             The class.
 */
function ttfmake_builder_get_text_class( $ttfmake_section_data, $sections ) {
	if ( ! ttfmake_builder_is_section_type( 'text', $ttfmake_section_data ) ) {
		return '';
	}

	$text_class = ' ';

	// Section classes
	$text_class .= ttfmake_get_builder_save()->section_classes( $ttfmake_section_data, $sections );

	// Columns
	$columns_number = ( isset( $ttfmake_section_data['columns-number'] ) ) ? absint( $ttfmake_section_data['columns-number'] ) : 1;
	$text_class .= ' builder-text-columns-' . $columns_number;

	/**
	 * Filter the text section class.
	 *
	 * @since 1.2.3.
	 *
	 * @param string    $text_class              The computed class string.
	 * @param array     $ttfmake_section_data    The section data.
	 * @param array     $sections                The list of sections.
	 */
	return apply_filters( 'make_builder_get_text_class', $text_class, $ttfmake_section_data, $sections );
}
