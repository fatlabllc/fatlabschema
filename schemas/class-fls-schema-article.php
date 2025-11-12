<?php
/**
 * Article Schema Type.
 *
 * Generates JSON-LD structured data for Article schema type
 * following schema.org/Article specifications.
 *
 * @package FatLab_Schema_Wizard
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Article schema class.
 *
 * Handles generation of Article schema markup including
 * subtypes like NewsArticle, BlogPosting, and ScholarlyArticle.
 */
class FLS_Schema_Article {

	/**
	 * Generate Article schema.
	 *
	 * Creates a schema.org/Article structured data object optimized
	 * for news articles, blog posts, and editorial content.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data     Schema data containing article information.
	 * @param int   $post_id  Optional. Post ID for context. Default null.
	 * @return array Schema array ready for JSON-LD encoding.
	 */
	public static function generate( $data, $post_id = null ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => ! empty( $data['article_type'] ) ? $data['article_type'] : 'Article',
		);

		// Required fields.
		if ( ! empty( $data['headline'] ) ) {
			$schema['headline'] = sanitize_text_field( $data['headline'] );
		}

		// Author.
		if ( ! empty( $data['author_name'] ) ) {
			$schema['author'] = array(
				'@type' => 'Person',
				'name'  => sanitize_text_field( $data['author_name'] ),
			);

			if ( ! empty( $data['author_url'] ) ) {
				$schema['author']['url'] = esc_url_raw( $data['author_url'] );
			}
		}

		// Publication date (required).
		if ( ! empty( $data['datePublished'] ) ) {
			$schema['datePublished'] = sanitize_text_field( $data['datePublished'] );
		} elseif ( $post_id ) {
			$schema['datePublished'] = get_the_date( 'c', $post_id );
		}

		// Modification date (recommended).
		if ( ! empty( $data['dateModified'] ) ) {
			$schema['dateModified'] = sanitize_text_field( $data['dateModified'] );
		} elseif ( $post_id ) {
			$schema['dateModified'] = get_the_modified_date( 'c', $post_id );
		}

		// Description.
		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = sanitize_textarea_field( $data['description'] );
		}

		// Image (recommended).
		if ( ! empty( $data['image'] ) ) {
			$schema['image'] = esc_url_raw( $data['image'] );
		}

		// Publisher (required by Google).
		$publisher = self::build_publisher( $data );
		if ( ! empty( $publisher ) ) {
			$schema['publisher'] = $publisher;
		}

		// Article body.
		if ( ! empty( $data['articleBody'] ) ) {
			$schema['articleBody'] = wp_kses_post( $data['articleBody'] );
		}

		// Word count.
		if ( ! empty( $data['wordCount'] ) ) {
			$schema['wordCount'] = absint( $data['wordCount'] );
		}

		// Main entity of page.
		if ( $post_id ) {
			$schema['mainEntityOfPage'] = array(
				'@type' => 'WebPage',
				'@id'   => get_permalink( $post_id ),
			);
		}

		return apply_filters( 'fls_article_schema', $schema, $data, $post_id );
	}

	/**
	 * Build publisher object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Schema data.
	 * @return array|null Publisher object or null if missing data.
	 */
	private static function build_publisher( $data ) {
		if ( empty( $data['publisher_name'] ) ) {
			return null;
		}

		$publisher = array(
			'@type' => 'Organization',
			'name'  => sanitize_text_field( $data['publisher_name'] ),
		);

		// Publisher logo (required by Google).
		if ( ! empty( $data['publisher_logo'] ) ) {
			$publisher['logo'] = array(
				'@type' => 'ImageObject',
				'url'   => esc_url_raw( $data['publisher_logo'] ),
			);
		}

		// Publisher URL.
		if ( ! empty( $data['publisher_url'] ) ) {
			$publisher['url'] = esc_url_raw( $data['publisher_url'] );
		}

		return $publisher;
	}
}
