<?php
/**



































































































































































{% endblock %}	</div>		{% endif %}			</section>				</div>					</div>						</div>							{{ current_category.description|safe }}						<div class="shop__category-info-content">						<h2>{{ _e('About', 'starter-theme-1.x') }} {{ current_category.name }}</h2>					<div class="shop__category-info-inner">				<div class="container">			<section class="shop__category-info">		{% if current_category and current_category.description %}		{# Category Description Section (Optional) #}		</div>			</div>				</main>					</nav>						{{ posts_pagination }}					<nav class="shop__pagination">					{# Pagination #}					{% endif %}						</div>							<p>{{ _e('No products found in this category', 'starter-theme-1.x') }}</p>						<div class="shop__empty">					{% else %}						</div>							{% do action('woocommerce_after_shop_loop') %}							{% endfor %}								} %}									product: product								{% include "components/product-card.twig" with {							{% for product in posts %}							{% do action('woocommerce_before_shop_loop') %}						<div class="shop__products shop__products--grid">					{% if posts %}					{# Products grid #}					</div>						</div>							</div>								{% do action('woocommerce_catalog_ordering') %}							<div class="shop__sorting">							{# Sorting dropdown #}						<div class="shop__toolbar-right">						</div>							</div>								{% do action('woocommerce_result_count') %}							<div class="shop__results-count">							{# Results count #}						<div class="shop__toolbar-left">					<div class="shop__toolbar">					{# Shop toolbar (sorting, view options) #}				<main class="shop__main">				{# Main shop content #}				{% endif %}					</aside>						</div>							{% endif %}								</div>									</ul>										{% endfor %}											</li>												</a>													<span class="shop__related-cat-count">({{ cat.count }})</span>													{{ cat.name }}												<a href="{{ cat.url }}" class="shop__related-cat-link">											<li class="shop__related-cat-item">										{% for cat in related_cats %}									<ul class="shop__related-cats-list">									<h4 class="shop__related-cats-title">{{ _e('Browse Categories', 'starter-theme-1.x') }}</h4>								<div class="shop__related-cats">							{% if related_cats and related_cats|length > 0 %}							}) %}								'order': 'DESC'								'orderby': 'count',								'number': 5,								'exclude': current_category.term_id,								'hide_empty': true,								'taxonomy': 'product_cat',							{% set related_cats = get_terms({							{# Related Categories Widget #}							{% do action('woocommerce_before_main_content') %}							{# WooCommerce filters #}							{% include "sidebar.twig" %}							<h3 class="shop__sidebar-title">{{ _e('Filter Products', 'starter-theme-1.x') }}</h3>						<div class="shop__sidebar-inner">					<aside class="shop__sidebar">				{% if sidebar %}				{# Sidebar with filters #}			<div class="shop__wrapper">		<div class="container">		{# Main shop content #}		</div>			</div>				{% do action('woocommerce_breadcrumb') %}			<div class="container">		<div class="shop__breadcrumb">		{# Breadcrumb Navigation #}		</div>			{% endif %}				</div>					</div>						</div>							{% endif %}								<p class="shop__hero-subtitle">{{ shop_description|striptags }}</p>							{% if shop_description %}							<h1 class="shop__hero-title">{{ current_category.name }}</h1>						<div class="shop__hero-content">					<div class="container">				<div class="shop__hero-fallback">			{% else %}				</div>					</div>						</div>							{% endif %}								<p class="shop__hero-subtitle">{{ shop_description|striptags }}</p>							{% if shop_description %}							<h1 class="shop__hero-title">{{ current_category.name }}</h1>						<div class="shop__hero-content">					<div class="container">					<div class="shop__hero-overlay"></div>				<div class="shop__hero-bg" style="background-image: url('{{ category_banner.url }}')">			{% if category_banner %}		<div class="shop__hero">		{# Category Hero Section #}	<div class="shop">{% block content %}{% extends "base.twig" %}#}  - Sorting and view options  - Responsive product grid (3/2/1 columns)  - Product filtering sidebar  - Related categories section  - Breadcrumb navigation  - Category description  - Category hero banner (optional ACF image override)  Features:    Displays products within a specific category with category-specific customizations * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$context          = Timber::context();
$context['posts'] = new Timber\PostQuery();
$context['foo']   = 'bar';
$templates        = array( 'index.twig' );

if ( is_home() ) {
	array_unshift( $templates, 'front-page.twig', 'home.twig' );
}

// WooCommerce template detection
if ( function_exists( 'is_shop' ) ) {
	if ( is_product() ) {
		// Single product page
		$context['product'] = new Timber\Post();
		$templates = array( 'single-product.twig', 'single.twig', 'index.twig' );
	} elseif ( is_product_cat() ) {
		// Product category archive page
		$context['posts'] = new Timber\PostQuery();
		$context['current_category'] = get_queried_object();
		$context['category_banner'] = get_field( 'category_banner_image', 'product_cat_' . $context['current_category']->term_id );
		$context['category_description'] = get_field( 'category_description_override', 'product_cat_' . $context['current_category']->term_id ) ?: $context['current_category']->description;
		$context['shop_title'] = $context['current_category']->name;
		$context['shop_description'] = $context['category_description'];
		$templates = array( 'archive-product_cat.twig', 'shop.twig', 'archive.twig', 'index.twig' );
	} elseif ( is_shop() || is_product_taxonomy() ) {
		// Shop page or product tag/other taxonomy archive
		// Get WooCommerce products using the global query
		$context['posts'] = new Timber\PostQuery();
		$context['shop_description'] = woocommerce_taxonomy_archive_description();
		$context['shop_title'] = __( 'Shop', 'starter-theme-1.x' );
		$templates = array( 'shop.twig', 'archive.twig', 'index.twig' );
	}
}

Timber::render( $templates, $context );
