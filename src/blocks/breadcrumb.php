<?php
/**
 * Breadcrumb Navigation Gutenberg Block
 *
 * @package StarterTheme
 */

return array(
	'title'       => 'Breadcrumb Navigation',
	'description' => 'Display navigation breadcrumbs for current page',
	'category'    => 'common',
	'icon'        => '
		<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="#fff" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right">
			<polyline points="13 17 20 10 13 3"></polyline>
			<polyline points="4 17 11 10 4 3"></polyline>
		</svg>
	',
	'keywords'    => array( 'breadcrumb', 'navigation', 'seo', 'menu' ),
	'mode'        => 'preview',
	'supports'    => array(
		'align' => array( 'wide', 'full' ),
		'mode' => true,
		'multiple' => false,
	),
	'acf'         => array(
		'fields' => array(
			array(
				'key'   => 'field_breadcrumb_separator',
				'name'  => 'separator',
				'label' => 'Separator',
				'type'  => 'select',
				'choices' => array(
					'/' => '/ (Slash)',
					'>' => '> (Greater Than)',
					'→' => '→ (Arrow)',
					'•' => '• (Bullet)',
					'|' => '| (Pipe)',
				),
				'default_value' => '/',
				'instructions' => 'Choose separator between breadcrumb items',
			),
			array(
				'key'   => 'field_breadcrumb_show_home',
				'name'  => 'show_home',
				'label' => 'Show Home Link',
				'type'  => 'true_false',
				'default_value' => true,
				'instructions' => 'Display home link as first breadcrumb item',
			),
		),
	),
	'render_callback' => function( $block, $content, $is_preview ) {
		// Get field values
		$separator = get_field( 'separator' ) ?: '/';
		$show_home = get_field( 'show_home' );

		// Get breadcrumb trail
		require_once get_theme_file_path( 'src/helpers/breadcrumbs.php' );
		$trail = \StarterTheme\Helpers\Breadcrumb::get_trail();

		// Remove home if setting disabled
		if ( ! $show_home && ! empty( $trail ) ) {
			array_shift( $trail );
		}

		// Render template with context
		Timber::render(
			'components/breadcrumb.twig',
			array(
				'trail'              => $trail,
				'breadcrumb_mobile'  => \StarterTheme\Helpers\Breadcrumb::get_abbreviated_trail( $trail ),
				'breadcrumb_schema'  => \StarterTheme\Helpers\Breadcrumb::get_schema( $trail ),
				'separator'          => $separator,
				'block'              => $block,
			)
		);
	},
);
