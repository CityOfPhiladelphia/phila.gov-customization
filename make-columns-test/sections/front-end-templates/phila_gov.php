<?php
/**
 * @package Make
 */

global $ttfmake_section_data, $ttfmake_sections;
$phila_gov_columns = ttfmake_builder_get_phila_gov_array( $ttfmake_section_data );
?>

<section id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>" class="builder-section<?php echo esc_attr( ttfmake_builder_get_phila_gov_class( $ttfmake_section_data, $ttfmake_sections ) ); ?>">
	<?php if ( '' !== $ttfmake_section_data['title'] ) : ?>
	<h3 class="builder-phila_gov-section-title">
		<?php echo apply_filters( 'the_title', $ttfmake_section_data['title'] ); ?>
	</h3>
	<?php endif; ?>
	<div class="builder-section-content">
		<?php if ( ! empty( $phila_gov_columns ) ) : $i = 1; foreach ( $phila_gov_columns as $column ) :
			$link_front = '';
			$link_back = '';
			if ( '' !== $column['image-link'] ) :
				$link_front = '<a href="' . esc_url( $column['image-link'] ) . '">';
				$link_back = '</a>';
			endif;
			?>
		<div class="builder-phila_gov-column builder-phila_gov-column-<?php echo $i; ?>" id="builder-section-<?php echo esc_attr( $ttfmake_section_data['id'] ); ?>-column-<?php echo $i; ?>">
			<?php $image_html =  ttfmake_get_image( $column['image-id'], 'large' ); ?>
			<?php if ( '' !== $image_html ) : ?>
			<figure class="builder-phila_gov-image">
				<?php echo $link_front . $image_html . $link_back; ?>
			</figure>
			<?php endif; ?>
			<?php if ( '' !== $column['title'] ) : ?>
			<h3 class="builder-phila_gov-title">
				<?php echo apply_filters( 'the_title', $column['title'] ); ?>
			</h3>
			<?php endif; ?>
			<?php if ( '' !== $column['content'] ) : ?>
			<div class="builder-phila_gov-content">
				<?php ttfmake_get_builder_save()->the_builder_content( $column['content'] ); ?>
			</div>
			<?php endif; ?>
		</div>
		<?php $i++; endforeach; endif; ?>
	</div>
</section>
