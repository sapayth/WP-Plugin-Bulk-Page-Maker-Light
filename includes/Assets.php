<?php

namespace Bulk\Page\Maker;

/**
 * the class to handle all JS and CSS files
 *
 * @package default
 * @author 
 **/
class Assets {

	/**
	 * the class constructor
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_bpm_assets'] );
	}

	/**
	 * registering all assets to be added later
	 * @return void
	 */
	function enqueue_bpm_assets() {
		wp_register_script( 'bpm-script', BPM_ASSETS . '/js/bpm-main.js', false, filemtime( BPM_PATH . '/assets/js/bpm-main.js' ), true );
	}
}