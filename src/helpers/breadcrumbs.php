<?php
/**
 * Breadcrumb Navigation Utility
 *
 * Generates breadcrumb trails for various page types with Schema.org support
 *
 * @package StarterTheme
 */

namespace StarterTheme\Helpers;

class Breadcrumb {
	/**
	 * Get breadcrumb trail for current page
	 *
	 * @return array Array of breadcrumb items with 'url', 'label', 'current' keys
	 */
	public static function get_trail() {
		$trail = array();

		// Always include home
		$trail[] = array(
			'url'   => home_url(),
			'label' => __( 'Home', 'starter-theme' ),
			'current' => false,
		);

		if ( is_front_page() ) {
			// Home page - no additional breadcrumbs
			return $trail;
		}

		if ( is_singular() ) {
			$trail = array_merge( $trail, self::get_singular_trail() );
		} elseif ( is_archive() ) {
			$trail = array_merge( $trail, self::get_archive_trail() );
		} elseif ( is_search() ) {
			$trail[] = array(
				'url'     => '',
				'label'   => sprintf( __( 'Search: %s', 'starter-theme' ), get_search_query() ),
				'current' => true,
			);
		} elseif ( is_404() ) {
			$trail[] = array(
				'url'     => '',
				'label'   => __( 'Page Not Found', 'starter-theme' ),
				'current' => true,
			);
		}

		return $trail;
	}

	/**
	 * Get breadcrumb trail for singular pages
	 *
	 * @return array Breadcrumb items
	 */
	private static function get_singular_trail() {
		$trail = array();
		$post  = get_post();

		if ( ! $post ) {
			return $trail;
		}

		$post_type = get_post_type_object( $post->post_type );

		// Add post type archive link if available
		if ( $post_type && ! $post_type->has_archive ) {
			// Custom handling for specific post types
			if ( 'post' === $post->post_type ) {
				$trail[] = array(
					'url'   => get_post_type_archive_link( 'post' ),
					'label' => __( 'Blog', 'starter-theme' ),
					'current' => false,
				);
			}
		} elseif ( $post_type && $post_type->has_archive ) {
			$trail[] = array(
				'url'   => get_post_type_archive_link( $post->post_type ),
				'label' => $post_type->labels->name,
				'current' => false,
			);
		}

		// Add parent pages for hierarchical post types (pages)
		if ( is_page() && $post->post_parent ) {
			$ancestors = array_reverse( get_post_ancestors( $post ) );
			foreach ( $ancestors as $ancestor ) {
				$ancestor_post = get_post( $ancestor );
				if ( $ancestor_post ) {
					$trail[] = array(
						'url'     => get_permalink( $ancestor ),
						'label'   => get_the_title( $ancestor ),
						'current' => false,
					);
				}
			}
		}

		// Add current page
		$trail[] = array(
			'url'     => '',
			'label'   => get_the_title( $post ),
			'current' => true,
		);

		return $trail;
	}

	/**
	 * Get breadcrumb trail for archive pages
	 *
	 * @return array Breadcrumb items
	 */
	private static function get_archive_trail() {
		$trail = array();

		if ( is_category() ) {
			$category = get_queried_object();
			if ( $category && $category->parent ) {
				$parents = array_reverse( get_ancestors( $category->term_id, 'category' ) );
				foreach ( $parents as $parent_id ) {
					$parent = get_term( $parent_id, 'category' );
					if ( $parent ) {
						$trail[] = array(
							'url'     => get_category_link( $parent ),
							'label'   => $parent->name,
							'current' => false,
						);
					}
				}
			}

			$trail[] = array(
				'url'     => '',
				'label'   => $category->name,
				'current' => true,
			);
		} elseif ( is_tag() ) {
			$tag = get_queried_object();
			$trail[] = array(
				'url'     => '',
				'label'   => sprintf( __( 'Tag: %s', 'starter-theme' ), $tag->name ),
				'current' => true,
			);
		} elseif ( is_tax() ) {
			$term = get_queried_object();
			$trail[] = array(
				'url'     => '',
				'label'   => $term->name,
				'current' => true,
			);
		} elseif ( is_post_type_archive() ) {
			$post_type = get_queried_object();
			$trail[] = array(
				'url'     => '',
				'label'   => $post_type->labels->name,
				'current' => true,
			);
		} elseif ( is_author() ) {
			$author = get_queried_object();
			$trail[] = array(
				'url'     => '',
				'label'   => sprintf( __( 'Author: %s', 'starter-theme' ), $author->display_name ),
				'current' => true,
			);
		} elseif ( is_date() ) {
			if ( is_year() ) {
				$trail[] = array(
					'url'     => '',
					'label'   => get_the_date( 'Y' ),
					'current' => true,
				);
			} elseif ( is_month() ) {
				$trail[] = array(
					'url'     => get_year_link( get_the_date( 'Y' ) ),
					'label'   => get_the_date( 'Y' ),
					'current' => false,
				);
				$trail[] = array(
					'url'     => '',
					'label'   => get_the_date( 'F Y' ),
					'current' => true,
				);
			} elseif ( is_day() ) {
				$year_url = get_year_link( get_the_date( 'Y' ) );
				$month_url = get_month_link( get_the_date( 'Y' ), get_the_date( 'm' ) );

				$trail[] = array(
					'url'     => $year_url,
					'label'   => get_the_date( 'Y' ),
					'current' => false,
				);
				$trail[] = array(
					'url'     => $month_url,
					'label'   => get_the_date( 'F' ),
					'current' => false,
				);
				$trail[] = array(
					'url'     => '',
					'label'   => get_the_date( 'j F Y' ),
					'current' => true,
				);
			}
		}

		return $trail;
	}

	/**
	 * Get Schema.org BreadcrumbList JSON-LD markup
	 *
	 * @param array $trail Breadcrumb trail from get_trail()
	 * @return string JSON-LD markup
	 */
	public static function get_schema( $trail ) {
		$items = array();

		foreach ( $trail as $index => $crumb ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $index + 1,
				'name'     => $crumb['label'],
				'item'     => $crumb['url'] ?: get_home_url(),
			);
		}

		$schema = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $items,
		);

		return wp_json_encode( $schema );
	}

	/**
	 * Get abbreviated trail for mobile (show first, last, and current)
	 *
	 * @param array $trail Full breadcrumb trail
	 * @return array Abbreviated trail
	 */
	public static function get_abbreviated_trail( $trail ) {
		if ( count( $trail ) <= 3 ) {
			return $trail;
		}

		$abbreviated = array( $trail[0] ); // Home

		// Add ellipsis placeholder if there are hidden items
		if ( count( $trail ) > 3 ) {
			$abbreviated[] = array(
				'url'     => '',
				'label'   => '...',
				'current' => false,
				'separator' => true,
			);
		}

		// Add last two items
		$abbreviated = array_merge( $abbreviated, array_slice( $trail, -2 ) );

		return $abbreviated;
	}

	/**
	 * Check if breadcrumb should be displayed on current page
	 *
	 * @return bool
	 */
	public static function should_display() {
		// Hide on homepage
		if ( is_front_page() && is_home() ) {
			return false;
		}

		// Show on all other pages
		return true;
	}
}
