<?php

namespace Bulk\Page\Maker;

/**
 * Admin class
 */
class Admin {

    // Holds various class instances
    private $container = [];

	/**
	 * The class constructor
     *
	 * @return  void
	 */
	public function __construct() {
		$this->container['bulk_page'] = new Admin\Bulk_Page();

		$this->dispatch_actions( $this->bulk_page );
        $this->container['menu'] = new Admin\Menu( $this->bulk_page );
	}

	public function dispatch_actions( $bulk_page ) {
		add_action( 'admin_init', [ $bulk_page, 'form_handler' ] );
		add_action( 'admin_post_bpm-delete-action', [ $bulk_page, 'delete_page' ] );
	}

    /**
     * Magic getter to bypass referencing objects
     *
     * @since 1.4.0
     *
     * @param string $prop
     *
     * @return Class Instance
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }
    }
}
