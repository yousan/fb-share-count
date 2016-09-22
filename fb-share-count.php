<?php
/**
 * Plugin Name:     Fb Share Count
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     fb-share-count
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Fb_Share_Count
 *
 * @link https://github.com/inpsyde/backwpup
 */

if ( ! class_exists( 'FB_Share_Count' ) ) {
	//Start Plugin
	if ( function_exists( 'add_filter' ) ) {
		add_action( 'plugins_loaded', array( 'FB_Share_Count', 'get_instance' ), 11 );
	}

	final class BackWPup {

		private static $instance = NULL;
		private static $plugin_data = array();
		private static $autoload = array();


		private function __construct() {

			// Nothing else matters if we're not on the main site
			if ( ! is_main_site() ) {
				return;
			}
			//auto loader
			spl_autoload_register( array( $this, 'autoloader' ) );

			//register_deactivation_hook( __FILE__, array( 'BackWPup_Install', 'deactivate' ) );
			//Admin bar
			//if ( get_site_option( 'backwpup_cfg_showadminbar' ) ) {
			//add_action( 'init', array( 'BackWPup_Adminbar', 'get_instance' ) );
			//}
			//only in backend
			if ( is_admin() && class_exists( 'BackWPup_Admin' ) ) {
				//BackWPup_Admin::get_instance();
			}
		}


		/**
		 * @static
		 *
		 * @return self
		 */
		public static function get_instance() {

			if (NULL === self::$instance) {
				self::$instance = new self;
			}
			return self::$instance;
		}


		private function __clone() {}

		/**
		 * include not existing classes automatically
		 *
		 * @param string $class Class to load from file
		 */
		private function autoloader( $class ) {

			//BackWPup classes auto load
			if ( strstr( strtolower( $class ), 'fsc_' ) ) {
				$dir = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
				$class_file_name = 'class-' . str_replace( array( 'backwpup_', '_' ), array( '', '-' ), strtolower( $class ) ) . '.php';
				if ( strstr( strtolower( $class ), 'backwpup_pro' ) ) {
					$dir .=  'pro' . DIRECTORY_SEPARATOR;
					$class_file_name = str_replace( 'pro-','', $class_file_name );
				}
				if ( file_exists( $dir . $class_file_name ) )
					require $dir . $class_file_name;
			}

			// namespaced PSR-0
			if ( ! empty( self::$autoload ) ) {
				$pos = strrpos( $class, '\\' );
				if ( $pos !== FALSE ) {
					$class_path = str_replace( '\\', DIRECTORY_SEPARATOR, substr( $class, 0, $pos ) ) . DIRECTORY_SEPARATOR . str_replace( '_', DIRECTORY_SEPARATOR, substr( $class, $pos + 1 ) ) . '.php';
					foreach ( self::$autoload as $prefix => $dir ) {
						if ( $class === strstr( $class, $prefix ) ) {
							if ( file_exists( $dir . DIRECTORY_SEPARATOR . $class_path ) )
								require $dir . DIRECTORY_SEPARATOR . $class_path;
						}
					}
				} // Single class file
				elseif ( ! empty( self::$autoload[ $class ] ) && is_file( self::$autoload[ $class ] ) ) {
					require self::$autoload[ $class ];
				}
			}
		}
	}
}
