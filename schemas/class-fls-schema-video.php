<?php
/**
 * Video Schema Type.
 *
 * Generates JSON-LD structured data for VideoObject schema type
 * following schema.org/VideoObject specifications.
 *
 * @package FatLab_Schema_Wizard
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Video schema class.
 *
 * Handles generation of VideoObject schema markup for video content
 * including YouTube, Vimeo, self-hosted, and embedded videos.
 */
class FLS_Schema_Video {

	/**
	 * Generate VideoObject schema.
	 *
	 * Creates a schema.org/VideoObject structured data object optimized
	 * for video content, helping it appear in video search results with
	 * rich snippets including thumbnail, duration, and upload date.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data     Schema data containing video information.
	 * @param int   $post_id  Optional. Post ID for context. Default null.
	 * @return array Schema array ready for JSON-LD encoding.
	 */
	public static function generate( $data, $post_id = null ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'VideoObject',
		);

		// Required: Video name/title.
		if ( ! empty( $data['name'] ) ) {
			$schema['name'] = sanitize_text_field( $data['name'] );
		}

		// Required: Description.
		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = sanitize_textarea_field( $data['description'] );
		}

		// Required: Thumbnail URL.
		if ( ! empty( $data['thumbnail_url'] ) ) {
			$schema['thumbnailUrl'] = esc_url_raw( $data['thumbnail_url'] );
		}

		// Required: Upload date.
		if ( ! empty( $data['upload_date'] ) ) {
			$schema['uploadDate'] = sanitize_text_field( $data['upload_date'] );
		} elseif ( $post_id ) {
			$schema['uploadDate'] = get_the_date( 'c', $post_id );
		}

		// Required: Content URL or Embed URL.
		if ( ! empty( $data['content_url'] ) ) {
			$schema['contentUrl'] = esc_url_raw( $data['content_url'] );
		}

		if ( ! empty( $data['embed_url'] ) ) {
			$schema['embedUrl'] = esc_url_raw( $data['embed_url'] );
		}

		// Duration (recommended) - ISO 8601 format.
		if ( ! empty( $data['duration'] ) ) {
			$schema['duration'] = self::format_duration( $data['duration'] );
		}

		// Transcript (optional but good for SEO/accessibility).
		if ( ! empty( $data['transcript'] ) ) {
			$schema['transcript'] = wp_kses_post( $data['transcript'] );
		}

		// Publisher.
		$publisher = self::build_publisher( $data );
		if ( ! empty( $publisher ) ) {
			$schema['publisher'] = $publisher;
		}

		// Author/Creator.
		if ( ! empty( $data['author_name'] ) ) {
			$schema['author'] = array(
				'@type' => 'Person',
				'name'  => sanitize_text_field( $data['author_name'] ),
			);

			if ( ! empty( $data['author_url'] ) ) {
				$schema['author']['url'] = esc_url_raw( $data['author_url'] );
			}
		}

		// Video quality (optional).
		if ( ! empty( $data['video_quality'] ) ) {
			$schema['videoQuality'] = sanitize_text_field( $data['video_quality'] );
		}

		// Interaction statistics (optional).
		if ( isset( $data['interaction_count'] ) && $data['interaction_count'] !== '' ) {
			$schema['interactionStatistic'] = array(
				'@type'                => 'InteractionCounter',
				'interactionType'      => 'https://schema.org/WatchAction',
				'userInteractionCount' => absint( $data['interaction_count'] ),
			);
		}

		// Main entity of page.
		if ( $post_id ) {
			$schema['mainEntityOfPage'] = array(
				'@type' => 'WebPage',
				'@id'   => get_permalink( $post_id ),
			);
		}

		return apply_filters( 'fls_video_schema', $schema, $data, $post_id );
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

		// Publisher logo.
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

	/**
	 * Format duration to ISO 8601 format.
	 *
	 * Accepts various input formats and converts to ISO 8601 duration.
	 * Examples: "1:30" -> "PT1M30S", "90" -> "PT1M30S", "PT1M30S" -> "PT1M30S"
	 *
	 * @since 1.0.0
	 *
	 * @param string $duration Duration in various formats.
	 * @return string ISO 8601 formatted duration.
	 */
	private static function format_duration( $duration ) {
		// If already in ISO 8601 format, return as-is.
		if ( strpos( $duration, 'PT' ) === 0 ) {
			return sanitize_text_field( $duration );
		}

		// Handle MM:SS or HH:MM:SS format.
		if ( strpos( $duration, ':' ) !== false ) {
			$parts = explode( ':', $duration );
			$parts = array_map( 'intval', $parts );

			if ( count( $parts ) === 2 ) {
				// MM:SS format.
				$minutes = $parts[0];
				$seconds = $parts[1];
				return sprintf( 'PT%dM%dS', $minutes, $seconds );
			} elseif ( count( $parts ) === 3 ) {
				// HH:MM:SS format.
				$hours   = $parts[0];
				$minutes = $parts[1];
				$seconds = $parts[2];
				return sprintf( 'PT%dH%dM%dS', $hours, $minutes, $seconds );
			}
		}

		// Handle seconds as integer.
		$total_seconds = intval( $duration );
		if ( $total_seconds > 0 ) {
			$hours   = floor( $total_seconds / 3600 );
			$minutes = floor( ( $total_seconds % 3600 ) / 60 );
			$seconds = $total_seconds % 60;

			$iso_duration = 'PT';
			if ( $hours > 0 ) {
				$iso_duration .= $hours . 'H';
			}
			if ( $minutes > 0 ) {
				$iso_duration .= $minutes . 'M';
			}
			if ( $seconds > 0 || ( $hours === 0 && $minutes === 0 ) ) {
				$iso_duration .= $seconds . 'S';
			}

			return $iso_duration;
		}

		// Default fallback.
		return 'PT0S';
	}
}
