<?php

/**
 * Product Options Module
 *
 *
 */

if ( ! class_exists( 'AVA_WC_Product_Options' ) ) {
	class AVA_WC_Product_Options extends AVA_WCPO_Module {

		public $module = 'product-options';

		/**
		 * Constructor
		 **/
		function __construct( $app ) {

			parent::__construct( $app );

			$this->settings = array(
				'admin_menu' => array(
					'parent_slug' => 'ava-woocommerce',
					'slug'        => 'ava-wc-customizer',
					'page_title'  => 'Customizer',
					'menu_title'  => 'Customizer Options'
				)
			);

			//return $this;
		}
	}
}

