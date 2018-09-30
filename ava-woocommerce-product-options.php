<?php
/*
Plugin Name: AVA WooCommerce Product Options
Plugin URI: http://woocommerce-product-options.ava-team.com
Description: WooCommerce Product Options
Version: 1.0.0
Author: AVA-Team.com
Author URI: http://ava-team.com
*/

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}




if ( ! class_exists( 'AVA_WCPO' ) ) {
	class AVA_WCPO {

		/**
		 * Modules
		 *
		 * @var array
		 */
		private $modules = array();

		/**
		 * Core singleton class
		 *
		 * @var self - pattern realization
		 */
		private static $instance;


		/**
		 * Class constructor
		 *
		 */
		private function __construct() {
		}

		/**
		 * Get the instance
		 *
		 * @return self
		 */
		public static function instance() {
			if ( ! ( self::$instance instanceof self ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function init() {

			/**
			 * Public hooks
			 *
			 */
			add_action( 'init', array( $this, 'public_init' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'public_assets' ) );

			/**
			 * Admin hooks
			 *
			 */
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );


			/** Ajax hooks */
			//add_action( 'wp_ajax_avaf-save', array( 'AVA_Fields_Options', 'save' ) );
			//add_action( 'wp_ajax_nopriv_avaf-save', array( 'AVA_Fields_Options', 'save' ) );

			// Load core functionality
			$this->load();

			do_action( 'ava-wcpo/init' );

			/*
			$response = wp_safe_remote_get( 'http://woocommerce.lc/wp-content/uploads/2018/08/redmi_5plus_64gb_black.jpg', array(
				'timeout' => 10,
			) );
			dd($response);
			*/
		}


		public function load() {

			require_once $this->dir( 'modules' ) . 'module.php';

			// Load helpers
			//foreach (glob( plugin_dir_path( __FILE__ ) . 'helpers/*.php' ) as $file) {
			//	include_once $file;
			//}

			// Load modules
			foreach ( glob( $this->dir( 'modules' ) . '*', GLOB_ONLYDIR ) as $dir ) {
				$module = basename( $dir );

				$fname = $dir . '/' . $module . '.php';
				if ( file_exists( $fname ) ) {
					require_once $fname;

					$className = 'AVA_WC_' . str_replace( '-', '_', $module );
					if ( class_exists( $className ) ) {
						$this->modules[ $module ] = new $className( $this );
					}
				}
			}
		}

		/**
		 * Plugin public init
		 *
		 */
		public function public_init() {
		}

		/**
		 * Plugin public assets
		 *
		 */
		public function public_assets() {
		}

		/**
		 * Plugin admin init
		 *
		 */
		public function admin_init() {
		}

		/**
		 * Plugin admin assets
		 *
		 */
		public function admin_assets() {
		}

		/**
		 * Plugin admin init
		 *
		 */
		public function admin_menu() {
			add_menu_page( 'AVA Product Options', 'AVA Product Options', 'manage_options', 'ava-wc-product-options' );
		}


		public function dir( $type = null ) {
			switch ( $type ) {
				case 'assets':
					return $this->dir() . 'assets/';
					break;
				case 'modules':
					return $this->dir() . 'modules/';
					break;
				default:
					return trailingslashit(__DIR__);
			}
		}


		public function url( $type = null ) {
			switch ( $type ) {
				case 'assets':
					return plugin_dir_url( __FILE__ ) . 'assets/';
					break;
				case 'modules':
					return plugin_dir_url( __FILE__ ) . 'modules/';
					break;
				default:
					return plugin_dir_url( __FILE__ );
			}
		}

		public function version() {
			return '1.0.0';
		}

		/**
		 * Cloning disabled
		 */
		private function __clone() {
		}

		/**
		 * Serialization disabled
		 */
		private function __sleep() {
		}

		/**
		 * De-serialization disabled
		 */
		private function __wakeup() {
		}


	}
}

if ( ! function_exists( 'ava_wcpo' ) ) {
	function ava_wcpo() {
		return AVA_WCPO::instance();
	}
}
ava_wcpo()->init();







