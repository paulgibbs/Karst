<?php
/*
Plugin Name: Karst plugin
Plugin URI: http://byotos.com
Description: Provides features for the Karst theme. Karst allows you to easily create and display beautiful documentation for an API.
Version: 1.0
Author: Paul Gibbs
Author URI: http://byotos.com
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Network: false
Text Domain: dpk

"Karst"
Copyright (C) 2012 Paul Gibbs

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License version 3 as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see http://www.gnu.org/licenses/.
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * On activation, set an option so we know to flush the rewrite rules later.
 *
 * @since 1.0
 */
function dpk_activation() {
	add_option( 'dpk_flush_rewrite_rules', true );
}
register_activation_hook( __FILE__, 'dpk_activation' );

// Clear rewrite rules on plugin deactivation. @todo Check if this works?
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

/**
 * Register the 'Entity' post type and taxonomies.
 *
 * @since 1.0
 */
function dpk_register_post_types_and_taxonomies() {
	// Post type - Resource
	$labels = array(
		'add_new'            => _x( 'Add New', 'entity', 'dpk' ),
		'add_new_item'       => _x( 'Add New Entity', 'entity', 'dpk' ),
		'edit_item'          => _x( 'Edit Entity', 'entity', 'dpk' ),
		'menu_name'          => _x( 'Entities', 'entity', 'dpk' ),
		'name'               => _x( 'Entities', 'entity', 'dpk' ),
		'new_item'           => _x( 'New Entity', 'entity', 'dpk' ),
		'not_found'          => _x( 'No entities found', 'entity', 'dpk' ),
		'not_found_in_trash' => _x( 'No entities found in Trash', 'entity', 'dpk' ),
		'parent_item_colon'  => _x( 'Parent Entity:', 'entity', 'dpk' ),
		'search_items'       => _x( 'Search Entities', 'entity', 'dpk' ),
		'singular_name'      => _x( 'Entity', 'entity', 'dpk' ),
		'view_item'          => _x( 'View Entity', 'entity', 'dpk' ),
	);

	$args = array(
		'can_export'           => true,
		'capability_type'      => 'post',
		'description'          => __( 'An Entity represents part of your API. This is usually a class, function, method, or resource.', 'dpk' ),
		'exclude_from_search'  => false,
		'has_archive'          => false,
		'hierarchical'         => true,
		'labels'               => $labels,
		'public'               => true,
		'publicly_queryable'   => true,
		'query_var'            => true,
		'register_meta_box_cb' => 'dpk_resource_mb_callback',
		'rewrite'              => array(
			'slug'       => 'entity',
			'with_front' => false,
			),
		'show_in_menu'         => true,
		'show_in_nav_menus'    => true,
		'show_ui'              => true,
		'supports'             => array( 'title', 'editor', 'excerpt', 'page-attributes', 'revisions', ),
		'taxonomies'           => array(),
	);
	register_post_type( 'dpk_resource', $args );


	// Taxonomy - Category
	$labels = array(
		'add_new_item'               => _x( 'Add New Category', 'dpk' ),
		'add_or_remove_items'        => _x( 'Add or remove categories', 'dpk' ),
		'all_items'                  => _x( 'All Categories', 'dpk' ),
		'choose_from_most_used'      => _x( 'Choose from the most used categories', 'dpk' ),
		'edit_item'                  => _x( 'Edit Category', 'dpk' ),
		'menu_name'                  => _x( 'Categories', 'dpk' ),
		'name'                       => _x( 'Categories', 'dpk' ),
		'new_item_name'              => _x( 'New Category', 'dpk' ),
		'parent_item'                => _x( 'Parent Category', 'dpk' ),
		'parent_item_colon'          => _x( 'Parent Category:', 'dpk' ),
		'popular_items'              => _x( 'Popular Categories', 'dpk' ),
		'search_items'               => _x( 'Search Categories', 'dpk' ),
		'separate_items_with_commas' => _x( 'Separate categories with commas', 'dpk' ),
		'singular_name'              => _x( 'Category', 'dpk' ),
		'update_item'                => _x( 'Update Category', 'dpk' ),
	);

	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'public'                => true,
		'query_var'             => true,
		'rewrite'               => true,
		'show_in_nav_menus'     => true,
		'show_tagcloud'         => true,
		'show_ui'               => true,
		'update_count_callback' => '_update_post_term_count',
	);
	register_taxonomy( 'dpk_category', array( 'dpk_resource' ), $args );


	// Taxonomy - Tags
	$labels = array(
		'add_new_item'               => _x( 'Add New Tag', 'dpk' ),
		'add_or_remove_items'        => _x( 'Add or remove tags', 'dpk' ),
		'all_items'                  => _x( 'All Tags', 'dpk' ),
		'choose_from_most_used'      => _x( 'Choose from the most used tags', 'dpk' ),
		'edit_item'                  => _x( 'Edit Tag', 'dpk' ),
		'menu_name'                  => _x( 'Tags', 'dpk' ),
		'name'                       => _x( 'Tags', 'dpk' ),
		'new_item_name'              => _x( 'New Tag', 'dpk' ),
		'parent_item'                => _x( 'Parent Tag', 'dpk' ),
		'parent_item_colon'          => _x( 'Parent Tag:', 'dpk' ),
		'popular_items'              => _x( 'Popular Tags', 'dpk' ),
		'search_items'               => _x( 'Search Tags', 'dpk' ),
		'separate_items_with_commas' => _x( 'Separate tags with commas', 'dpk' ),
		'singular_name'              => _x( 'Tag', 'dpk' ),
		'update_item'                => _x( 'Update Tag', 'dpk' ),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'public'                => true,
		'query_var'             => true,
		'rewrite'               => true,
		'show_in_nav_menus'     => true,
		'show_tagcloud'         => true,
		'show_ui'               => true,
		'update_count_callback' => '_update_post_term_count',
	);
	register_taxonomy( 'dpk_tag', array( 'dpk_entity' ), $args );

	// If plugin has just been activated, flush rewrite rules
	if ( get_option( 'dpk_flush_rewrite_rules' ) ) {
		flush_rewrite_rules();
		delete_option( 'dpk_flush_rewrite_rules' );
	}
}
add_action( 'init', 'dpk_register_post_types_and_taxonomies' );

/**
 * Add the Karst-specific rewrite tags
 *
 * @since 1.0
 */
/*function dpk_add_rewrite_tags() {
	// Pad attributes
	$pad      = 2;
	$wrapper  = '%';

	$entity_category = str_pad( 'dpk_category', strlen( 'dpk_category' ) + $pad, 'dpk_category', STR_PAD_BOTH );

	add_rewrite_tag( $bbp_view, '([^/]+)'   );
	add_rewrite_tag( $bbp_edit, '([1]{1,})' );
}
add_action( 'init', 'dpk_add_rewrite_tags' );*/

/**
 * Generate Karst-specific rewrite rules
 *
 * @param WP_Rewrite $wp_rewrite
 * @return WP_Rewrite Updated rewrite rules
 * @since 1.0
 */

function dpk_generate_rewrite_rules( $wp_rewrite ) {
	$karst_rules = array(
		'entity/([^/]+)/([^/]+)/?$' => 'index.php?dpk_category=' . $wp_rewrite->preg_index( 1 ) . '&dpk_resource=' . $wp_rewrite->preg_index( 2 ),
	);

	// Merge Karst rules with existing
	$wp_rewrite->rules = array_merge( $karst_rules, $wp_rewrite->rules );
	return $karst_rules;
}
add_action( 'generate_rewrite_rules', 'dpk_generate_rewrite_rules' );

/**
 * Called when setting up the meta boxes for the Resource post type
 *
 * @since 1.0
 */
function dpk_resource_mb_callback() {
//	add_meta_box( 'dpk-resource-info-mb', __( 'Resource Information', 'dpk' ), 'dpk_resource_mb_info', 'dpk_resource', 'side', 'high' );
}

/**
 * Save handler for the custom meta for the dpk_resource post type.
 *
 * @param integer $post_id
 * @param object $post
 * @since 1.0
 */
function dpk_resource_mb_save( $post_id, $post ) {
	if ( 'dpk_resource' != $post->post_type )
		return;

	// Check nonce
	check_admin_referer( 'dpk_resource_' . $post_id, '_dpk_resource_nonce' );

	// Get the post type object
	$post_type = get_post_type_object( $post->post_type );

	// Check if the current user has permission to edit the post.
	if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) )
		return;

	// Try to get the existing values from the database
	$rate_limited    = get_post_meta( $post_id, 'dpk_rate_limited',    true );
	$require_auth    = get_post_meta( $post_id, 'dpk_require_auth',    true );
	$http_methods    = get_post_meta( $post_id, 'dpk_http_methods',    true );
	$response_format = get_post_meta( $post_id, 'dpk_response_format', true );
	//$response_obj    = get_post_meta( $post_id, 'dpk_response_obj',    true );

	// Update the "rate limited" and "require auth" options.
	update_post_meta( $post_id, 'dpk_rate_limited', empty( $_POST['dpk_ratelimited']  ) ? 0 : 1 );
	update_post_meta( $post_id, 'dpk_require_auth', empty( $_POST['dpk_requiresauth'] ) ? 0 : 1 );

	// Update the "http methods" option.
	$http_methods = array();
	if ( ! empty( $_POST['dpk_http_methods'] ) ) {
		foreach ( (array) $_POST['dpk_http_methods'] as $raw_http_method ) {

			// Check for any sneakiness
			if ( ! in_array( $raw_http_method, array( 'delete', 'get', 'none', 'post', 'put', ) ) )
				continue;

			$http_methods[] = $raw_http_method;
		}

	} else {
		$http_methods[] = 'none';
	}
	update_post_meta( $post_id, 'dpk_http_methods', $http_methods );

	// Update the "response format" option.
	$response_formats = array();
	if ( ! empty( $_POST['dpk_responseformat'] ) ) {
		foreach ( (array) $_POST['dpk_responseformat'] as $raw_response_format ) {

			// Check for any sneakiness
			if ( ! in_array( $raw_response_format, array( 'atom', 'html', 'json', 'rss', 'xml', ) ) )
				continue;

			$response_formats[] = $raw_response_format;
		}

	} else {
		$response_formats[] = 'none';
	}
	update_post_meta( $post_id, 'dpk_response_format', $response_formats );

	// Handle response_obj here...
}
//add_action( 'publish_dpk_resource', 'dpk_resource_mb_save', 10, 2 );

/**
 * Inline CSS for the Resource post type screen.
 *
 * It's only inline so that people only have to move a single file into
 * the /plugins/ folder; it keeps it all simple. Please don't be angry :(
 *
 * @since 1.0
 */
function dpk_resource_css() {
	// Only add the CSS to the Resource CPT screen
	if ( 'dpk_resource' != get_current_screen()->post_type )
		return;
?>

	<style type="text/css">
		#dpk-resource-info-mb li input,
		#dpk-resource-info-mb li select {
			float: right;
		}
		#dpk-resource-info-mb li {
			clear: both;
			float: left;
			padding: 6px 0;
			width: 100%;
		}
		#dpk-resource-info-mb li.last {
			margin-bottom: 0;
			padding-bottom: 0;
		}
		#dpk-resource-info-mb li.last label { text-align: left; }
		#dpk-resource-info-mb .responseformat { float: right; }
		#dpk-resource-info-mb .responseformat input {
			margin-left: 8px;
			text-align: right;
		}

		/* RTL */
		body.rtl #dpk-resource-info-mb li input,
		body.rtl #dpk-resource-info-mb li select { float: left; }
		body.rtl #dpk-resource-info-mb li { float: right; }
		body.rtl #dpk-resource-info-mb li.last label { text-align: right; }
		body.rtl #dpk-resource-info-mb .responseformat { float: left; }
		body.rtl #dpk-resource-info-mb .responseformat input {
			margin-left: auto;
			margin-right: 8px;
			text-align: left;
		}
	</style>

<?php
}
add_action( 'admin_head-post-new.php', 'dpk_resource_css' );
add_action( 'admin_head-post.php',     'dpk_resource_css' );

/**
 * 'Resource Information' metabox.
 *
 * These values are stored individually as post meta, to allow them to be easily
 * retrieved by WP_Query, as opposed to serializing them in a single array which
 * would, of course, be more efficent.
 *
 * @param object $post
 * @since 1.0
 */
function dpk_resource_mb_info( $post ) {
	// Get meta keys from database.
	$rate_limited    = get_post_meta( $post->ID, 'dpk_rate_limited',    true );
	$require_auth    = get_post_meta( $post->ID, 'dpk_require_auth',    true );
	$http_methods    = get_post_meta( $post->ID, 'dpk_http_methods',    true );
	$response_format = get_post_meta( $post->ID, 'dpk_response_format', true );
	//$response_obj    = get_post_meta( $post->ID, 'dpk_response_obj',    true );

	// Fallback values if they haven't been set yet
	if ( empty( $rate_limited ) )    $rate_limited    = 0;        // No
	if ( empty( $require_auth ) )    $require_auth    = 0;        // No
	if ( empty( $http_methods ) )    $http_methods    = array();  // None
	if ( empty( $response_format ) ) $response_format = array();  // None
	//if ( empty( $response_obj ) )    $response_obj    = ??;

	// Helpers for selected() checks for the HTTP Methods option
	$http_methods_none   = in_array( 'none',   $http_methods ) ? 'none'   : '';
	$http_methods_delete = in_array( 'delete', $http_methods ) ? 'delete' : '';
	$http_methods_get    = in_array( 'get',    $http_methods ) ? 'get'    : '';
	$http_methods_post   = in_array( 'post',   $http_methods ) ? 'post'   : '';
	$http_methods_put    = in_array( 'put',    $http_methods ) ? 'put'    : '';

	// More helpers for the Response formats option
	$response_format_atom = in_array( 'atom', $response_format ) ? 'atom' : '';
	$response_format_html = in_array( 'html', $response_format ) ? 'html' : '';
	$response_format_json = in_array( 'json', $response_format ) ? 'json' : '';
	$response_format_rss  = in_array( 'rss',  $response_format ) ? 'rss'  : '';
	$response_format_xml  = in_array( 'xml',  $response_format ) ? 'xml'  : '';
?>
	<?php wp_nonce_field( 'dpk_resource_' . $post->ID, '_dpk_resource_nonce' ); ?>
	<ul>

		<li>
			<label for="dpk_ratelimited"><?php _e( 'Rate Limited?', 'dpk' ); ?></label>
			<select id="dpk_ratelimited" name="dpk_ratelimited">
				<option value="1" <?php selected( 1, (int) $rate_limited ); ?>><?php _e( 'Yes', 'dpk' ); ?></option>
				<option value="0" <?php selected( 0, (int) $rate_limited ); ?>><?php _e( 'No', 'dpk' ); ?></option>
			</select>
		</li>

		<li>
			<label for="dpk_requiresauth"><?php _e( 'Requires Authentication?', 'dpk' ); ?></label>
			<select id="dpk_requiresauth" name="dpk_requiresauth">
				<option value="1" <?php selected( 1, (int) $require_auth ); ?>><?php _e( 'Yes', 'dpk' ); ?></option>
				<option value="0" <?php selected( 0, (int) $require_auth ); ?>><?php _e( 'No', 'dpk' ); ?></option>
			</select>
		</li>

		<!--
		<li>
			<label for="dpk_responseobj"><?php _e( 'Response Object', 'dpk' ); ?></label>
			<select id="dpk_responseobj" name="dpk_responseobj">
				<option value="1">@Todo Response Objects</option>
			</select>
		</li>
		-->

		<li>
			<label for="dpk_http_methods"><?php _e( 'HTTP Methods (optional)', 'dpk' ); ?></label>
			<select id="dpk_http_methods" name="dpk_http_methods">
				<option value="none" <?php selected( 'none', $http_methods_none ); ?>></option>
				<option value="delete" <?php selected( 'delete', $http_methods_delete ); ?>><?php _ex( 'DELETE', 'HTTP method', 'dpk' ); ?></option>
				<option value="get" <?php selected( 'get', $http_methods_get ); ?>><?php _ex( 'GET', 'HTTP method', 'dpk' ); ?></option>
				<option value="post" <?php selected( 'post', $http_methods_post ); ?>><?php _ex( 'POST', 'HTTP method', 'dpk' ); ?></option>
				<option value="put" <?php selected( 'put', $http_methods_put ); ?>><?php _ex( 'PUT', 'HTTP method', 'dpk' ); ?></option>
			</select>
		</li>

		<li class="last">
			<label for="dpk_responseformat"><?php _e( 'Response Formats (optional)', 'dpk' ); ?></label><br />
			<div class="responseformat">
				<input type="checkbox" name="dpk_responseformat[]" value="json" <?php checked( 'json', $response_format_json ); ?>><?php _ex( 'JSON', 'data format', 'dpk' ); ?></input><br />
				<input type="checkbox" name="dpk_responseformat[]" value="html" <?php checked( 'html', $response_format_html ); ?>><?php _ex( 'HTML', 'data format', 'dpk' ); ?></input><br />
				<input type="checkbox" name="dpk_responseformat[]" value="atom" <?php checked( 'atom', $response_format_atom ); ?>><?php _ex( 'Atom', 'data format', 'dpk' ); ?></input><br />
				<input type="checkbox" name="dpk_responseformat[]" value="rss"  <?php checked( 'rss',  $response_format_rss  ); ?>><?php _ex( 'RSS', 'data format', 'dpk' );  ?></input><br />
				<input type="checkbox" name="dpk_responseformat[]" value="xml"  <?php checked( 'xml',  $response_format_xml  ); ?>><?php _ex( 'XML', 'data format', 'dpk' );  ?></input>
			</div>
		</li>

		<div class="clear"></div>
	</ul>
<?php
}
?>