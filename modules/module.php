<?php

/**
 * Module class
 *
 **/

if ( ! class_exists( 'AVA_WCPO_Module' ) ) {
	class AVA_WCPO_Module {

		public $settings;
		public $dir;
		public $url;

		/**
		 * Constructor
		 *
		 */
		function __construct( $app ) {

			$this->dir = trailingslashit($app->dir('modules') . $this->module);
			$this->url = trailingslashit($app->url('modules') . $this->module);

			/**
			 * Module public hooks
			 *
			 */
			add_action( 'init', array( $this, 'public_init' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'public_assets' ) );

			/**
			 * Module admin hooks
			 *
			 */
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );


			do_action( 'ava-wc/' . $this->module . '/init' );
		}

		/**
		 * Module public & admin assets
		 *
		 */
		public function assets() {
			// ColPick
			wp_enqueue_style( 'ava-colpick', $this->url . 'assets/libs/colpick/colpick.css', array(), $this->version() );
			wp_enqueue_script( 'ava-colpick', $this->url . 'assets/libs/colpick/colpick.js', array( 'jquery' ), $this->version(), true );

		}

		/**
		 * Module public init
		 *
		 */
		public function public_init() {
		}

		/**
		 * Module public assets
		 *
		 */
		public function public_assets() {
		}

		/**
		 * Module admin init
		 *
		 */
		public function admin_init() {
		}

		/**
		 * Module admin assets
		 *
		 */
		public function admin_assets() {
		}

		public function version() {
			return '1.0.0';
		}

	}
}
