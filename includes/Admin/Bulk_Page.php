<?php

namespace Bulk\Page\Maker\Admin;

/**
 * the Bulk_Page Class
 */
class Bulk_Page {

	public $errors = [];

	/**
	 * [plugin_page description]
	 * @return [type] [description]
	 */
	public function plugin_page() {
		
		$action = isset( $_GET['action'] ) ? sanitize_key( $_GET['action'] ) : 'list';

		switch ( $action ) {
			case 'new':
				wp_enqueue_script( 'bpm-script' );
				$template = __DIR__ . '/views/bulk-page-new.php';
				break;
			
			default:
				$template = __DIR__ . '/views/bulk-page-list.php';
				break;
		}

		if( file_exists( $template ) ) {
			include $template;
		}
	}

	/**
	 * handle add new pages form
	 * @return void
	 */
	public function form_handler() {
		$pages              = '';
		$page_content 		= '';
		$post_types_arr     = [ 'page', 'post' ];
		$status_arr         = [ 'publish', 'pending', 'draft' ];
		$comment_status_arr = [ 'closed', 'open' ];

		if( ! isset( $_POST['btn_add_pages'] ) ) {
			return;
		}

		if( ! wp_verify_nonce( $_POST['_wpnonce'], 'bpm-nonce' ) ) {
			wp_die( 'Request Unauthorized' );
		}

		if( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Access Denied' );
		}

		if( empty( $_POST['txt_pages_list'] ) ) {
			$this->errors['page_names'] = __( 'Please provide at least one page/post name', 'sh-bpm-light' );
		} else {
			$pages = sanitize_textarea_field( $_POST['txt_pages_list'] );
		}

		$pages_arr      = array_map( 'trim', explode( ',', $pages ) );
		$page_content   = filter_input( INPUT_POST, 'cmb_page_content', FILTER_SANITIZE_SPECIAL_CHARS );
		$page_content   = htmlspecialchars_decode( $page_content );
		$post_type      = isset( $_POST['cmb_post_type'] ) ? sanitize_text_field( $_POST['cmb_post_type'] ) : 'page';
		$page_status    = isset( $_POST['cmb_page_status'] ) ? sanitize_text_field( $_POST['cmb_page_status'] ) : 'draft';
		$comment_status = isset( $_POST['cmb_comment_status'] ) ? sanitize_text_field( $_POST['cmb_comment_status'] ) : 'closed';
		$post_parent    = isset( $_POST['page_id'] ) ? sanitize_text_field( $_POST['page_id'] ) : '';

		// if input is not in our list
		if ( ! in_array( $post_type , $post_types_arr) ) {
			wp_die( 'Request Unauthorized' );
		}

		// if input is not in our list
		if ( ! in_array( $page_status , $status_arr) ) {
			wp_die( 'Request Unauthorized' );
		}

		// if input is not in our list
		if ( ! in_array( $comment_status , $comment_status_arr) ) {
			wp_die( 'Request Unauthorized' );
		}

		if( ! empty( $this->errors ) ) {
			return;
		}

		for( $i = 0; $i < count( $pages_arr ); $i++ ) {
			$postarr = [
				'post_title'     => $pages_arr[ $i ],
				'post_status'    => $page_status,
				'post_type'      => $post_type,
				'comment_status' => $comment_status,
				'ping_status'    => $comment_status,
				'post_parent'    => $post_parent,
				'post_content'   => $page_content,
		    ];

			$insert_id = wp_insert_post( $postarr );
			$second_insert_id = bpmaker_insert_pages_info(
				[
					'page_id'    => $insert_id,
				]
			);

			if( is_wp_error( $insert_id ) ) {
				wp_die( $insert_id->get_error_message() );
			}

			if( is_wp_error( $second_insert_id ) ) {
				wp_die( $insert_id->get_error_message() );
			}
		}

		$redirected_to = admin_url( 'admin.php?page=bulk-page-maker&inserted=true', 'admin' );
		
		wp_redirect( $redirected_to );
		exit;
	}

	public function delete_page() {
        if( ! wp_verify_nonce( $_GET['_wpnonce'], 'bpm-delete-action' ) ) {
			wp_die( 'Request Unauthorized' );
		}

		if( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Access Denied' );
		}

        $id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;

        if ( bpmaker_delete_page( $id ) ) {
            $redirected_to = admin_url( 'admin.php?page=bulk-page-maker&page-deleted=true' );
        } else {
            $redirected_to = admin_url( 'admin.php?page=bulk-page-maker&page-deleted=false' );
        }

        wp_redirect( $redirected_to );
        
        exit;
    }
}