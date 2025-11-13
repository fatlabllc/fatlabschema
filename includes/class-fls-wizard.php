<?php
/**
 * Wizard functionality for the meta box.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wizard class.
 */
class FLS_Wizard {

	/**
	 * Get available schema types.
	 *
	 * @return array
	 */
	public static function get_schema_types() {
		$types = array(
			'none'          => array(
				'label'       => __( 'None of the above', 'fatlabschema' ),
				'description' => __( 'This page doesn\'t fit any of these categories', 'fatlabschema' ),
				'icon'        => 'dashicons-no',
			),
			'localbusiness' => array(
				'label'       => __( 'Business/Organization Location', 'fatlabschema' ),
				'description' => __( 'A physical location with address and contact info', 'fatlabschema' ),
				'icon'        => 'dashicons-store',
			),
			'event'         => array(
				'label'       => __( 'Event (Fundraiser, Rally, Meeting)', 'fatlabschema' ),
				'description' => __( 'An event with date, time, and location', 'fatlabschema' ),
				'icon'        => 'dashicons-calendar-alt',
			),
			'video'         => array(
				'label'       => __( 'Video Content', 'fatlabschema' ),
				'description' => __( 'Video content including YouTube, Vimeo, or self-hosted videos', 'fatlabschema' ),
				'icon'        => 'dashicons-video-alt3',
			),
			'jobposting'    => array(
				'label'       => __( 'Job Posting', 'fatlabschema' ),
				'description' => __( 'A job opening your organization is hiring for', 'fatlabschema' ),
				'icon'        => 'dashicons-businessman',
			),
			'course'        => array(
				'label'       => __( 'Course or Training Program', 'fatlabschema' ),
				'description' => __( 'Educational course, workshop, or training', 'fatlabschema' ),
				'icon'        => 'dashicons-welcome-learn-more',
			),
			'faqpage'       => array(
				'label'       => __( 'FAQ or Support Content', 'fatlabschema' ),
				'description' => __( 'Questions and answers about a topic', 'fatlabschema' ),
				'icon'        => 'dashicons-editor-help',
			),
			'article'       => array(
				'label'       => __( 'Blog Post or News Article', 'fatlabschema' ),
				'description' => __( 'A standard blog post or news article', 'fatlabschema' ),
				'icon'        => 'dashicons-media-text',
			),
			'scholarly'     => array(
				'label'       => __( 'Research Paper or Publication', 'fatlabschema' ),
				'description' => __( 'Academic or scholarly publication', 'fatlabschema' ),
				'icon'        => 'dashicons-book-alt',
			),
			'service'       => array(
				'label'       => __( 'Service We Offer', 'fatlabschema' ),
				'description' => __( 'A service provided by your organization', 'fatlabschema' ),
				'icon'        => 'dashicons-admin-tools',
			),
			'howto'         => array(
				'label'       => __( 'How-To Guide or Tutorial', 'fatlabschema' ),
				'description' => __( 'Step-by-step instructions', 'fatlabschema' ),
				'icon'        => 'dashicons-list-view',
			),
			'person'        => array(
				'label'       => __( 'Person Profile (Candidate, Staff)', 'fatlabschema' ),
				'description' => __( 'Profile of an individual person', 'fatlabschema' ),
				'icon'        => 'dashicons-admin-users',
			),
		);

		return apply_filters( 'fls_schema_types', $types );
	}

	/**
	 * Get recommendation for a schema type.
	 *
	 * @param string $type Schema type.
	 * @return array
	 */
	public static function get_recommendation( $type ) {
		$recommendations = array(
			'none'          => array(
				'recommended'  => false,
				'title'        => __( 'No Schema Recommended', 'fatlabschema' ),
				'message'      => __( 'That\'s perfectly fine! Not every page needs schema.', 'fatlabschema' ),
				'benefits'     => array(
					__( 'About Us pages', 'fatlabschema' ),
					__( 'Thank You pages', 'fatlabschema' ),
					__( 'Privacy policies', 'fatlabschema' ),
					__( 'General information pages', 'fatlabschema' ),
					__( 'Contact forms', 'fatlabschema' ),
				),
				'benefits_title' => __( 'Pages that typically DON\'T need schema:', 'fatlabschema' ),
				'note'         => __( 'Your page will still rank normally in search engines. Schema is just an enhancement for specific content types.', 'fatlabschema' ),
			),
			'localbusiness' => array(
				'recommended'  => true,
				'title'        => __( 'Recommended: LocalBusiness Schema', 'fatlabschema' ),
				'message'      => __( 'This schema will help your location appear in Google Maps and local search results.', 'fatlabschema' ),
				'benefits'     => array(
					__( 'Appear in Google Maps', 'fatlabschema' ),
					__( 'Show business hours in search results', 'fatlabschema' ),
					__( 'Display contact information prominently', 'fatlabschema' ),
					__( 'Enable directions and click-to-call', 'fatlabschema' ),
				),
				'benefits_title' => __( 'This schema will help:', 'fatlabschema' ),
				'use_cases'    => __( 'Great for: Physical offices, campaign headquarters, retail stores, service locations', 'fatlabschema' ),
			),
			'event'         => array(
				'recommended'  => true,
				'title'        => __( 'Recommended: Event Schema', 'fatlabschema' ),
				'message'      => __( 'This schema will help your event appear in Google event search and calendar apps.', 'fatlabschema' ),
				'benefits'     => array(
					__( 'Appear in Google event search', 'fatlabschema' ),
					__( 'Show date/time/location in search results', 'fatlabschema' ),
					__( 'Display in AI assistant responses (ChatGPT, etc.)', 'fatlabschema' ),
					__( 'Enable ticket/registration buttons', 'fatlabschema' ),
				),
				'benefits_title' => __( 'This schema will help:', 'fatlabschema' ),
				'use_cases'    => __( 'Great for: Fundraisers, rallies, town halls, volunteer events, webinars', 'fatlabschema' ),
			),
			'faqpage'       => array(
				'recommended'  => true,
				'title'        => __( 'Recommended: FAQPage Schema', 'fatlabschema' ),
				'message'      => __( 'This schema creates expandable Q&A sections in search results.', 'fatlabschema' ),
				'benefits'     => array(
					__( 'Expandable Q&A in search results', 'fatlabschema' ),
					__( 'Featured snippets for questions', 'fatlabschema' ),
					__( 'Better visibility for voice search', 'fatlabschema' ),
					__( 'AI assistants can cite your answers', 'fatlabschema' ),
				),
				'benefits_title' => __( 'This schema will help:', 'fatlabschema' ),
				'use_cases'    => __( 'Great for: FAQ pages, policy positions, campaign stances, "What We Do" pages', 'fatlabschema' ),
			),
			'article'       => array(
				'recommended'  => true,
				'title'        => __( 'Recommended: Article Schema', 'fatlabschema' ),
				'message'      => __( 'This schema helps search engines understand your article content.', 'fatlabschema' ),
				'benefits'     => array(
					__( 'Display author info in search results', 'fatlabschema' ),
					__( 'Show publish/update dates', 'fatlabschema' ),
					__( 'Featured images in search', 'fatlabschema' ),
					__( 'Better AI assistant citations', 'fatlabschema' ),
				),
				'benefits_title' => __( 'This schema will help:', 'fatlabschema' ),
				'use_cases'    => __( 'Great for: Blog posts, news articles, opinion pieces', 'fatlabschema' ),
				'conflict_check' => true,
			),
			'scholarly'     => array(
				'recommended'  => true,
				'title'        => __( 'Recommended: ScholarlyArticle Schema', 'fatlabschema' ),
				'message'      => __( 'This schema identifies academic and research publications.', 'fatlabschema' ),
				'benefits'     => array(
					__( 'Appear in Google Scholar', 'fatlabschema' ),
					__( 'Show publication details', 'fatlabschema' ),
					__( 'Enable academic citations', 'fatlabschema' ),
					__( 'Better research discovery', 'fatlabschema' ),
				),
				'benefits_title' => __( 'This schema will help:', 'fatlabschema' ),
				'use_cases'    => __( 'Great for: Research papers, white papers, policy publications, studies', 'fatlabschema' ),
			),
			'service'       => array(
				'recommended'  => true,
				'title'        => __( 'Recommended: Service Schema', 'fatlabschema' ),
				'message'      => __( 'This schema describes services your organization offers.', 'fatlabschema' ),
				'benefits'     => array(
					__( 'Appear in service search results', 'fatlabschema' ),
					__( 'Show service descriptions', 'fatlabschema' ),
					__( 'Display areas served', 'fatlabschema' ),
					__( 'AI assistants can recommend your services', 'fatlabschema' ),
				),
				'benefits_title' => __( 'This schema will help:', 'fatlabschema' ),
				'use_cases'    => __( 'Great for: Counseling, legal aid, hosting services, consulting', 'fatlabschema' ),
			),
			'howto'         => array(
				'recommended'  => true,
				'title'        => __( 'Recommended: HowTo Schema', 'fatlabschema' ),
				'message'      => __( 'This schema creates step-by-step instructions in search results.', 'fatlabschema' ),
				'benefits'     => array(
					__( 'Step-by-step display in search', 'fatlabschema' ),
					__( 'Featured snippets for how-to queries', 'fatlabschema' ),
					__( 'Show images with steps', 'fatlabschema' ),
					__( 'Voice assistant instructions', 'fatlabschema' ),
				),
				'benefits_title' => __( 'This schema will help:', 'fatlabschema' ),
				'use_cases'    => __( 'Great for: "How to volunteer," "How to donate," instructional guides', 'fatlabschema' ),
			),
			'person'        => array(
				'recommended'  => true,
				'title'        => __( 'Recommended: Person Schema', 'fatlabschema' ),
				'message'      => __( 'This schema creates knowledge panels for notable individuals.', 'fatlabschema' ),
				'benefits'     => array(
					__( 'Knowledge panels for individuals', 'fatlabschema' ),
					__( 'Display role and affiliation', 'fatlabschema' ),
					__( 'Social media links', 'fatlabschema' ),
					__( 'Better AI assistant recognition', 'fatlabschema' ),
				),
				'benefits_title' => __( 'This schema will help:', 'fatlabschema' ),
				'use_cases'    => __( 'Great for: Political candidates, executive directors, board members, staff profiles', 'fatlabschema' ),
			),
			'jobposting'    => array(
				'recommended'  => true,
				'title'        => __( 'Recommended: JobPosting Schema', 'fatlabschema' ),
				'message'      => __( 'This schema helps your job appear in Google for Jobs and other job search platforms.', 'fatlabschema' ),
				'benefits'     => array(
					__( 'Appear in Google for Jobs', 'fatlabschema' ),
					__( 'Show salary, location, and job type', 'fatlabschema' ),
					__( 'Display in job search aggregators', 'fatlabschema' ),
					__( 'AI assistants can recommend your openings', 'fatlabschema' ),
				),
				'benefits_title' => __( 'This schema will help:', 'fatlabschema' ),
				'use_cases'    => __( 'Great for: Staff positions, volunteer roles, internships, contractor positions', 'fatlabschema' ),
			),
			'course'        => array(
				'recommended'  => true,
				'title'        => __( 'Recommended: Course Schema', 'fatlabschema' ),
				'message'      => __( 'This schema helps your course appear in education search results and learning platforms.', 'fatlabschema' ),
				'benefits'     => array(
					__( 'Appear in course search results', 'fatlabschema' ),
					__( 'Show pricing and format (online/in-person)', 'fatlabschema' ),
					__( 'Display instructor and duration', 'fatlabschema' ),
					__( 'Better visibility on learning platforms', 'fatlabschema' ),
				),
				'benefits_title' => __( 'This schema will help:', 'fatlabschema' ),
				'use_cases'    => __( 'Great for: Training programs, workshops, volunteer orientation, certification courses, webinars', 'fatlabschema' ),
			),
			'video'         => array(
				'recommended'  => true,
				'title'        => __( 'Recommended: Video Schema', 'fatlabschema' ),
				'message'      => __( 'This schema helps your video content appear in video search results with rich snippets.', 'fatlabschema' ),
				'benefits'     => array(
					__( 'Appear in Google Video Search', 'fatlabschema' ),
					__( 'Show thumbnail, duration, and upload date', 'fatlabschema' ),
					__( 'Display in video carousels and rich results', 'fatlabschema' ),
					__( 'AI assistants can reference your video content', 'fatlabschema' ),
				),
				'benefits_title' => __( 'This schema will help:', 'fatlabschema' ),
				'use_cases'    => __( 'Great for: YouTube videos, Vimeo, video tutorials, webinars, promotional videos, testimonials', 'fatlabschema' ),
			),
		);

		$recommendation = isset( $recommendations[ $type ] ) ? $recommendations[ $type ] : $recommendations['none'];

		return apply_filters( 'fls_schema_recommendation', $recommendation, $type );
	}

	/**
	 * Detect content type from post content.
	 *
	 * @param WP_Post $post Post object.
	 * @return string|null Detected type or null.
	 */
	public static function detect_content_type( $post ) {
		// This is a simple implementation - could be enhanced with AI in the future
		$content = strtolower( $post->post_content . ' ' . $post->post_title );

		// Check for common patterns
		if ( preg_match( '/\b(what|where|when|who|why|how)\b.*\?/i', $content ) ) {
			// Might be FAQ
			return 'faqpage';
		}

		if ( preg_match( '/\b(step 1|step 2|how to|tutorial|guide)\b/i', $content ) ) {
			// Might be HowTo
			return 'howto';
		}

		if ( preg_match( '/\b(event|rally|fundraiser|meeting|conference)\b/i', $content ) ) {
			// Might be Event
			return 'event';
		}

		// Default to article for blog posts
		if ( 'post' === $post->post_type ) {
			return 'article';
		}

		return null;
	}

	/**
	 * Auto-fill data from post.
	 *
	 * @param WP_Post $post Post object.
	 * @param string  $type Schema type.
	 * @return array
	 */
	public static function auto_fill_data( $post, $type ) {
		$data = array();
		$organization = get_option( 'fatlabschema_organization', array() );

		// Common fields
		$data['name'] = $post->post_title;
		$data['description'] = $post->post_excerpt ? $post->post_excerpt : wp_trim_words( $post->post_content, 30 );

		// Get featured image
		if ( has_post_thumbnail( $post->ID ) ) {
			$image_id = get_post_thumbnail_id( $post->ID );
			$image = wp_get_attachment_image_src( $image_id, 'full' );
			if ( $image ) {
				$data['image'] = $image[0];
			}
		}

		// Type-specific auto-fill
		switch ( $type ) {
			case 'article':
			case 'scholarly':
				$data['headline'] = $post->post_title;
				$data['datePublished'] = get_the_date( 'c', $post->ID );
				$data['dateModified'] = get_the_modified_date( 'c', $post->ID );

				// Author
				$author_id = $post->post_author;
				$data['author_name'] = get_the_author_meta( 'display_name', $author_id );

				// Publisher (from Organization)
				if ( ! empty( $organization['name'] ) ) {
					$data['publisher_name'] = $organization['name'];
					if ( ! empty( $organization['logo'] ) ) {
						$data['publisher_logo'] = $organization['logo'];
					}
				}
				break;

			case 'event':
				// Organizer (from Organization)
				if ( ! empty( $organization['name'] ) ) {
					$data['organizer_name'] = $organization['name'];
					$data['organizer_url'] = ! empty( $organization['url'] ) ? $organization['url'] : home_url();
				}
				break;

			case 'localbusiness':
				// Pre-fill from Organization if available
				if ( ! empty( $organization ) ) {
					$data['telephone'] = $organization['telephone'] ?? '';
					$data['street_address'] = $organization['street_address'] ?? '';
					$data['address_locality'] = $organization['address_locality'] ?? '';
					$data['address_region'] = $organization['address_region'] ?? '';
					$data['postal_code'] = $organization['postal_code'] ?? '';
					$data['address_country'] = $organization['address_country'] ?? 'US';
				}
				break;

			case 'service':
				// Provider (from Organization)
				if ( ! empty( $organization['name'] ) ) {
					$data['provider_name'] = $organization['name'];
				}
				break;

			case 'jobposting':
				$data['title'] = $post->post_title;
				$data['date_posted'] = get_the_date( 'Y-m-d', $post->ID );
				// Hiring organization (from Organization)
				if ( ! empty( $organization['name'] ) ) {
					$data['hiring_organization'] = $organization['name'];
					$data['organization_url'] = ! empty( $organization['url'] ) ? $organization['url'] : home_url();
					if ( ! empty( $organization['logo'] ) ) {
						$data['organization_logo'] = $organization['logo'];
					}
				}
				// Pre-fill location from Organization
				if ( ! empty( $organization ) ) {
					$data['address_locality'] = $organization['address_locality'] ?? '';
					$data['address_region'] = $organization['address_region'] ?? '';
					$data['address_country'] = $organization['address_country'] ?? 'US';
				}
				break;

			case 'course':
				// Provider (from Organization)
				if ( ! empty( $organization['name'] ) ) {
					$data['provider_name'] = $organization['name'];
					$data['provider_url'] = ! empty( $organization['url'] ) ? $organization['url'] : home_url();
				}
				break;
		}

		return apply_filters( 'fls_auto_fill_data', $data, $post, $type );
	}
}
