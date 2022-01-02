<?php

namespace Bulk\Page\Maker\Admin;

/**
 * The Bulk_Page Class
 */
class Bulk_Page {

	public $errors = [];

	/**
	 * The Plugin page
     *
	 * @return void
	 */
	public function plugin_page() {
		$action = isset( $_GET['action'] ) ? sanitize_key( $_GET['action'] ) : 'list';

		switch ( $action ) {
			case 'new':
				wp_enqueue_script( 'bpm-script' );
				$template = BPM_ADMIN_PATH . '/views/bulk-page-new.php';
				break;

			default:
				$template = BPM_ADMIN_PATH . '/views/bulk-page-list.php';
				break;
		}

		if ( file_exists( $template ) ) {
            ob_start();
            include $template;
            ob_get_contents();
			ob_flush();
            ob_end_clean();
		}
	}

	/**
	 * Handle add new pages form
     *
	 * @return void
	 */
	public function form_handler() {
		$pages              = '';
		$page_content       = '';
		$post_types_arr     = [ 'page', 'post' ];
		$status_arr         = [ 'publish', 'pending', 'draft' ];
		$comment_status_arr = [ 'closed', 'open' ];

		if ( ! isset( $_POST['btn_add_pages'] ) ) {
			return;
		}

		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), 'bpm-nonce' ) ) {
			wp_die( __( 'Request Unauthorized', 'sh-bpm-light' ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Access Denied', 'sh-bpm-light' ) );
		}

		if ( empty( $_POST['txt_pages_list'] ) ) {
			$this->errors['page_names'] = __( 'Please provide at least one page/post name', 'sh-bpm-light' );
		} else {
			$pages = sanitize_textarea_field( wp_unslash( $_POST['txt_pages_list'] ) );
		}

		$pages_arr      = array_map( 'trim', explode( ',', $pages ) );
		$page_content   = filter_input( INPUT_POST, 'cmb_page_content', FILTER_SANITIZE_SPECIAL_CHARS );
		$page_content   = htmlspecialchars_decode( $page_content );
		$post_type      = ! empty( $_POST['cmb_post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['cmb_post_type'] ) ) : 'page';
		$page_status    = ! empty( $_POST['cmb_page_status'] ) ? sanitize_text_field( wp_unslash( $_POST['cmb_page_status'] ) ) : 'draft';
		$comment_status = ! empty( $_POST['cmb_comment_status'] ) ? sanitize_text_field( wp_unslash( $_POST['cmb_comment_status'] ) ) : 'closed';
		$post_parent    = ! empty( $_POST['page_id'] ) ? sanitize_key( wp_unslash( $_POST['page_id'] ) ) : 0;

		// if input is not in our list
		if ( ! in_array( $post_type, $post_types_arr, true ) ) {
            wp_die( __( 'Request Unauthorized', 'sh-bpm-light' ) );
		}

		// if input is not in our list
		if ( ! in_array( $page_status, $status_arr, true ) ) {
            wp_die( __( 'Request Unauthorized', 'sh-bpm-light' ) );
		}

		// if input is not in our list
		if ( ! in_array( $comment_status, $comment_status_arr, true ) ) {
            wp_die( __( 'Request Unauthorized', 'sh-bpm-light' ) );
		}

		if ( ! empty( $this->errors ) ) {
			return;
		}
        $pages_count = count( $pages_arr );

        foreach ( $pages_arr as $page ) {
            $postarr = [
                'post_title'     => $page,
                'post_status'    => $page_status,
                'post_type'      => $post_type,
                'comment_status' => $comment_status,
                'ping_status'    => $comment_status,
                'post_parent'    => $post_parent,
                'post_content'   => $page_content,
            ];

            $insert_id = wp_insert_post( $postarr );
            if ( is_wp_error( $insert_id ) ) {
                wp_die( $insert_id->get_error_message() );
            }

            $second_insert_id = bpmaker_insert_pages_info(
                [
                    'page_id'    => $insert_id,
                ]
            );

            if ( is_wp_error( $second_insert_id ) ) {
                wp_die( $second_insert_id->get_error_message() );
            }
        }

		$redirected_to = admin_url( 'admin.php?page=bulk-page-maker&inserted=true', 'admin' );

		wp_safe_redirect( $redirected_to );
		exit;
	}

    /**
     * Delete a page
     *
     * @return void
     */
	public function delete_page() {
        if ( ! empty( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['_wpnonce'] ), 'bpm-delete-action' ) ) {
            wp_die( __( 'Request Unauthorized', 'sh-bpm-light' ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Access Denied', 'sh-bpm-light' ) );
		}

        $id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;

        if ( bpmaker_delete_page( $id ) ) {
            $redirected_to = admin_url( 'admin.php?page=bulk-page-maker&page-deleted=true' );
        } else {
            $redirected_to = admin_url( 'admin.php?page=bulk-page-maker&page-deleted=false' );
        }

        wp_safe_redirect( $redirected_to );

        exit;
    }
}