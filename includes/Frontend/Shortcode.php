<?php

namespace Bulk\Page\Maker\Frontend;

/**
 * shortcode handler class
 */
class Shortcode {
	
	function __construct() {
		add_shortcode( 'bulk-page-maker', [$this, 'render_shortcode'] );
	}

	/**
	 * [render_shortcode description]
	 * @param  [type] $atts    [description]
	 * @param  string $content [description]
	 * @return string          [description]
	 */
	public function render_shortcode($atts, $content='') {
		return 'hello shortcode';
	}
}