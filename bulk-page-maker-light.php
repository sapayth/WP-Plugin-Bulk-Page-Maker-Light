<?php
/*
Plugin Name: Bulk Page Maker Light
Description: A Light plugin to generate bulk WordPress Pages or Posts
Version: 1.0.0
Author: Sapayth H.
Author URI: http://sapayth.com
License: GPLv2 or later
Text Domain: bulk-page-maker-light
*/

if( !defined('ABSPATH') ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
* The main plugin class
*/
final class Bulk_Page_Maker {
    
    const VERSION = '1.0';
    
    /*
    * class constructor
    */
    private function __construct() {
        $this->define_constants();
        
        register_activation_hook( __FILE__, [ $this, 'activate'] );

        add_action( 'plugins_loaded', [$this, 'init_plugin'] );
    }
    
    /*
    * initializes a singleton instance
    */
    
    public static function init() {
        static $instance = false;
        
        if( !$instance ) {
            $instance = new self();
        }
        
        return $instance;        
    }

    /**
     * constants
     */    
    public function define_constants() {
        define( 'BPM_VERSION', self::VERSION );
        define( 'BPM_FILE', __FILE__ );
        define( 'BPM_PATH', __DIR__ );
        define( 'BPM_URL', plugins_url('', BPM_FILE) );
        define( 'BPM_ASSETS', BPM_URL . '/assets' );        
    }

    /**
     * initialize the plugin
     * @return void
     */
    public function init_plugin() {
        new Bulk\Page\Maker\Assets();
        if( is_admin() ) {
            new Bulk\Page\Maker\Admin();
        } else {
            new Bulk\Page\Maker\Frontend();
        }
    }

    /**
     * Do stuff upon plugin activation
     */
    
    public function activate() {
        $installer = new Bulk\Page\Maker\Installer();
        $installer->run();
    }
}

/**
* initialize the main plugin
*/
function make_page() {
    return Bulk_Page_Maker::init();
}

make_page();