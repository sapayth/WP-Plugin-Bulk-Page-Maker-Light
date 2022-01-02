<?php

/**
 * Insert newly generated pages info
 * @param  array  $args [description]
 * @return int|WP_Error
 */
function bpmaker_insert_pages_info( $args = [] ) {
	global $wpdb;

	$defaults = [
		'created_by' => get_current_user_id(),
		'created_at' => current_time( 'mysql' ),
	];

	$data = wp_parse_args( $args, $defaults );

	$inserted = $wpdb->insert(
		$wpdb->prefix . 'bpm_pages',
		$data,
		[
			'%s',
			'%s',
		]
	);

	if ( ! $inserted ) {
		return new \WP_Error( 'failed-to-insert', __( 'Failed to insert data', 'sh-bpm-light' ) );
	}

	return $wpdb->insert_id;
}

/**
 * Return all bulk pages
 * @param  array  $args
 * @return array
 */
function bpmaker_get_pages( $args = [] ) {
	global $wpdb;

	$defaults = [
		'numbers' => 20,
		'offset'  => 0,
		'orderby' => 'created_at',
		'order'   => 'ASC',
	];

	$args = wp_parse_args( $args, $defaults );

	$sql = $wpdb->prepare(
		"SELECT bpm.*, wp.post_title, wp.post_type, wp.post_date
		FROM {$wpdb->prefix}bpm_pages bpm, {$wpdb->prefix}posts wp
		WHERE bpm.page_id = wp.id
		ORDER BY %s %s
		LIMIT %d, %d",
        $args['orderby'],
        $args['order'],
		$args['offset'],
        $args['numbers']
	);

	$items = $wpdb->get_results( $sql );

	return $items;
}

/**
 * Get total number of page created
 * @return int
 */
function bpmaker_get_pages_count() {
	global $wpdb;

	$count = (int) $wpdb->get_var( "SELECT count(id) FROM {$wpdb->prefix}bpm_pages" );

	return $count;
}

/**
 * Get selected page
 * @param  int $id [description]
 * @return object
 */
function bpmaker_get_page( $id ) {
	global $wpdb;

	return $wpdb->get_row(
		$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bpm_pages WHERE ID = %d", $id )
	);
}

/**
 * Delete page
 * @param  int $post_id
 * @return int|bool
 */
function bpmaker_delete_page( $post_id ) {
	global $wpdb;

	// delete from posts table
	$wpdb->delete(
		$wpdb->prefix . 'posts',
		[ 'id' => $post_id ],
		[ '%d' ]
	);

	// delete from plugin table
	return $wpdb->delete(
		$wpdb->prefix . 'bpm_pages',
		[ 'page_id' => $post_id ],
		[ '%d' ]
	);
}