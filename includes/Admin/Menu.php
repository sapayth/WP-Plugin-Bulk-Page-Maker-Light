<?php

namespace Bulk\Page\Maker\Admin;

/**
 * The Menu holder class
 */
class Menu {
	
	public $bulkpage;

	function __construct($bulkpage) {
		$this->bulkpage = $bulkpage;
		add_action( 'admin_menu', [$this, 'admin_menu'] );
	}

	/**
	 * register admin menu
	 * @return void
	 */
	public function admin_menu() {
		$parent_slug = 'bulk-page-maker';
		$capability = 'manage_options';
		add_menu_page( __('Bulk Page Maker', 'bulk-page-maker'), __('Bulk Page Maker', 'bulk-page-maker'), $capability, $parent_slug, [$this->bulkpage, 'plugin_page'], 'dashicons-images-alt' );
		add_submenu_page( $parent_slug, __('All Pages', 'bulk-page-maker'), __('Add New Pages', 'bulk-page-maker'), $capability, $parent_slug, [$this->bulkpage, 'plugin_page'] );
	}

	/**
	 * [plugin_settings_page description]
	 * @return [type] [description]
	 */
	public function plugin_settings_page() {
		echo 'hello settings page';
	}
}