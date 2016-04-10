<?php

/**
 * Plugin Name: WP REST API V2 Category Images
 * Description: Adds Category Images to WP REST API V2 JSON output.
 * Version: 0.1
 * Author: Deyan Vatsov
 * Plugin URI: https://github.com/Vatsov/wp-rest-api-v2-category-image/
 */

if ( !function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

if ( is_plugin_active('rest-api/plugin.php') ) {
	new CategoryImageDataPlugin();
}

class CategoryImageDataPlugin {
	public function __construct() {
		// Add Custom Data
		add_action('rest_api_init', array( $this, 'add_custom_data' ) );
	}

	function add_custom_data() {
		// Register the category type
		register_rest_field('category', 'img', array(
				'get_callback' => array( $this, 'get_custom_data' ),
				'update_callback' => array( $this, 'update_custom_data' ),
				'schema' => array(
					'description' => 'My custom field',
					'type' => 'string',
					'context' => array('view', 'edit')
				)
			)
		);
	}

	/**
	 * Handler for getting custom data.
	 *
	 */
	function get_custom_data($object, $field_name, $request) {
		if (function_exists('get_wp_term_image')) {
			return get_wp_term_image($object['id']);
		}
	}

	 /**
	 * Handler for updating custom data.
	 */
	function update_custom_data($value, $post, $field_name) {
		if (!$value || !is_string($value)) {
			return;
		}

		return update_post_meta($post->ID, $field_name, strip_tags($value));
	}
}
