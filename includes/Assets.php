<?php

namespace Bulk\Page\Maker;


class Assets {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [$this, 'enqueue_bpm_assets'] );
		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_bpm_assets'] );
	}

	function enqueue_bpm_assets() {
		wp_register_script( 'bpm-script', BPM_ASSETS . '/js/bpm-main.js', false, filemtime(BPM_PATH . '/assets/js/bpm-main.js'), true );
		wp_register_style( 'bpm-style', BPM_ASSETS . '/css/style.css', false, filemtime(BPM_PATH . '/assets/css/style.css') );

		// wp_enqueue_script( 'bpm-script' );
		// wp_enqueue_style( 'bpm-style' );

	}
}