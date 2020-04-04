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
		
		$action = isset($_GET['action']) ? sanitize_key( $_GET['action'] ) : 'list';

		switch ($action) {
			case 'new':
				$template = __DIR__ . '/views/bulk-page-new.php';
				break;
			
			default:
				$template = __DIR__ . '/views/bulk-page-list.php';
				break;
		}

		if( file_exists($template) ) {
			include $template;
		}
	}

	/**
	 * handle add new pages form
	 * @return void
	 */
	public function form_handler() {
		$pages = '';
		if( !isset($_POST['btn_add_pages']) ) {
			return;
		}

		if( !wp_verify_nonce($_POST['_wpnonce'], 'bpm') ) {
			wp_die('Are you cheating?');
		}

		if( !current_user_can('manage_options') ) {
			wp_die('Are you cheating?');
		}

		if( empty($_POST['txt_pages_list']) ) {
			$this->errors['page_names'] = __('Please provide at least one page/post name', 'bpm');
		} else {
			$pages = sanitize_textarea_field($_POST['txt_pages_list']);
		}

		if( !empty($this->errors) ) {
			return;
		}

		$pages_arr = array_map('trim', explode(',', $pages));
		$post_type = isset($_POST['cmb_post_type']) ? sanitize_text_field($_POST['cmb_post_type']) : 'page';
		$page_status = isset($_POST['cmb_page_status']) ? sanitize_text_field($_POST['cmb_page_status']) : 'draft';
		$cmb_comment_status = isset($_POST['cmb_comment_status']) ? sanitize_text_field($_POST['cmb_comment_status']) : 'closed';
		$post_parent = isset($_POST['page_id']) ? sanitize_text_field($_POST['page_id']) : '';


		for($i = 0; $i < count($pages_arr); $i++) {
			$postarr = array(
		        'post_title'            => $pages_arr[$i],
		        'post_status'           => $page_status,
		        'post_type'             => $post_type,
		        'comment_status'        => $cmb_comment_status,
		        'ping_status'           => $cmb_comment_status,
		        'post_parent'           => $post_parent,
		    );

			$insert_id = wp_insert_post( $postarr );
			$second_insert_id = bpm_insert_pages_info([
				'page_id'    => $insert_id,
			]);

			if( is_wp_error($insert_id) ) {
				wp_die( $insert_id->get_error_message() );
			}

			if( is_wp_error($second_insert_id) ) {
				wp_die( $insert_id->get_error_message() );
			}
		}

		$redirected_to = admin_url( 'admin.php?page=bulk-page-maker&inserted=true', 'admin' );
		wp_redirect( $redirected_to );
		exit;
	}
}