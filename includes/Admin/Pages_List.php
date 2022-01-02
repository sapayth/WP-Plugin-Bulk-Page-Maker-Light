<?php

namespace Bulk\Page\Maker\Admin;

if( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}


class Pages_List extends \WP_List_Table {

	public function __construct() {
		parent::__construct(
            [
			    'plural'   => 'Pages',
			    'singular' => 'Page',
			    'ajax'     => false,
		    ]
        );
	}

	public function get_columns() {
		return [
			'cb'         => '<input type="checkbox" />',
			'post_title' => __( 'Title', 'sh-bpm-light' ),
			'created_by' => __( 'Author', 'sh-bpm-light' ),
			'post_type'  => __( 'Type', 'sh-bpm-light' ),
			'post_date'  => __( 'Date', 'sh-bpm-light' ),

		];
	}

	protected function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'created_by':
				return get_the_author_meta( 'display_name', $item->created_by );

			case 'post_date':
				$mysqldate = strtotime( $item->$column_name );
				return gmdate( 'Y/m/d H:i A', $mysqldate );

			default:
				return isset( $item->$column_name ) ? esc_html( $item->$column_name ) : '';
		}
	}

	public function column_post_title( $item ) {
		$actions = [];

		$actions['edit']   = sprintf(
			'<a href="%s" title="%s">%s</a>',
			esc_url( admin_url( '/post.php?post=' . $item->page_id . '&action=edit' ) ),
			__( 'Edit', 'sh-bpm-light' ),
			__( 'Edit', 'sh-bpm-light' )
		);

        $actions['delete'] = sprintf(
        	'<a href="%s" class="submitdelete" onclick="return confirm(\'Are you sure?\');" title="%s">%s</a>',
        	wp_nonce_url( admin_url( 'admin-post.php?page=bulk-page-maker&action=bpm-delete-action&id=' . $item->page_id ), 'bpm-delete-action' ),
        	__( 'Delete', 'sh-bpm-light' ),
        	__( 'Delete', 'sh-bpm-light' )
        );

		return sprintf(
			'<a href="%1$s"><strong>%2$s</strong></a> %3$s',
			esc_url( admin_url( '/post.php?post=' . $item->page_id . '&action=edit' ) ),
			$item->post_title,
			$this->row_actions( $actions )
		);
	}

	protected function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="page_id[]" value="%d">',
			$item->id
		);
	}

	/**
     * Get sortable columns
     *
     * @return array
     */
    public function get_sortable_columns() {
        $sortable_columns = [
			'post_date'  => [ 'created_at', true ],
			'post_type'  => [ 'post_type', true ],
			'post_title' => [ 'post_title', true ],
        ];

        return $sortable_columns;
    }

    /**
	 * Prepares the list of items for displaying.
	 * overridden from WP_List_Table
     * @since 1
     *
	 * @uses WP_List_Table::set_pagination_args()
	 *
	 * @abstract
	*/
	public function prepare_items() {
		$per_page     = 20;
		$current_page = $this->get_pagenum();
		$offset       = ( $current_page - 1 ) * $per_page;
		$column       = $this->get_columns();
		$hidden       = [];
		$sortable     = $this->get_sortable_columns();

		$this->_column_headers = [ $column, $hidden, $sortable ];

		$args = [
            'number' => $per_page,
            'offset' => $offset,
        ];

        if ( ! empty( $_REQUEST['orderby'] ) && ! empty( $_REQUEST['order'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$allowed_keys = [ 'post_title', 'post_type', 'date', 'asc', 'desc' ];
			$orderby      = sanitize_key( $_REQUEST['orderby'] ); // phpcs:ignore WordPress.Security.NonceVerification
			$order        = sanitize_key( $_REQUEST['order'] ); // phpcs:ignore WordPress.Security.NonceVerification

        	if ( in_array( $orderby, $allowed_keys, true ) ) {
        		$args['orderby'] = $orderby;
        	}

        	if ( in_array( $order, $allowed_keys, true ) ) {
				$args['order'] = $order;
        	}
        }

		$this->items = bpmaker_get_pages( $args );

		$this->set_pagination_args(
            [
			    'total_items' => bpmaker_get_pages_count(),
			    'per_page'    => $per_page,
		    ]
        );
	}
}