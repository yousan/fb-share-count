<?php
/**
 * Plugin Name:     Fb Share Count
 * Plugin URI:      https://www.github.com/yousan/fb-share-count
 * Description:     Facebookのシェア数を取得するタグを追加します。
 * Author:          Yousan_O
 * Author URI:      http://www.l2tp.org
 * Text Domain:     fb-share-count
 * Domain Path:     /languages
 * Version:         0.1.5
 *
 * @package         Fb_Share_Count
 *
 */

// Thanks @link https://github.com/inpsyde/backwpup
if ( ! class_exists( 'FB_Share_Count' ) ) {
	//Start Plugin
	if ( function_exists( 'add_filter' ) ) {
		add_action( 'plugins_loaded', array( 'FB_Share_Count', 'get_instance' ), 11 );
	}

	final class FB_Share_Count {

		private static $instance = null;
		private static $plugin_data = array();
		private static $autoload = array();


		private function __construct() {

			// Nothing else matters if we're not on the main site
			if ( ! is_main_site() ) {
				return;
			}
			//auto loader
			spl_autoload_register( array( $this, 'autoloader' ) );
			require_once( 'includes/functions.php' ); // こちらは手動で読み込み

			//register_deactivation_hook( __FILE__, array( 'BackWPup_Install', 'deactivate' ) );
			//Admin bar
			//if ( get_site_option( 'backwpup_cfg_showadminbar' ) ) {
			//add_action( 'init', array( 'BackWPup_Adminbar', 'get_instance' ) );
			//}
			//only in backend
			//var_dump( class_exists('FSC_Option')); exit;
			if ( is_admin() ) {
				$FSC_Option = new FSC_Option();
				//BackWPup_Admin::get_instance();
			}

		}


		/**
		 * @static
		 *
		 * @return self
		 */
		public static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Load Plugin Translation
		 *
		 * @return bool Text domain loaded
		 */
		public static function load_text_domain() {

			if ( is_textdomain_loaded( 'fsc' ) ) {
				return true;
			}

			return load_plugin_textdomain( 'fsc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}


		private function __clone() {
		}

		/**
		 * include not existing classes automatically
		 *
		 * @param string $class Class to load from file
		 */
		private function autoloader( $class ) {
			//BackWPup classes auto load
			if ( strstr( strtolower( $class ), 'fsc_' ) ) {
				$dir = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
				$class_file_name = 'class-' . str_replace( array( 'fsc_', '_' ), array(
						'',
						'-'
					), strtolower( $class ) ) . '.php';
				if ( file_exists( $dir . $class_file_name ) ) {
					require $dir . $class_file_name;
				}
			}

			// namespaced PSR-0
			if ( ! empty( self::$autoload ) ) {
				$pos = strrpos( $class, '\\' );
				if ( $pos !== false ) {
					$class_path = str_replace( '\\', DIRECTORY_SEPARATOR, substr( $class, 0, $pos ) ) . DIRECTORY_SEPARATOR . str_replace( '_', DIRECTORY_SEPARATOR, substr( $class, $pos + 1 ) ) . '.php';
					foreach ( self::$autoload as $prefix => $dir ) {
						if ( $class === strstr( $class, $prefix ) ) {
							if ( file_exists( $dir . DIRECTORY_SEPARATOR . $class_path ) ) {
								require $dir . DIRECTORY_SEPARATOR . $class_path;
							}
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
