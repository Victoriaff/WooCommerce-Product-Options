<?php

namespace cyberint\controller;

/**
 * Front side controller
 **/
class front {
	
	/**
	 * Constructor
	 **/
	function __construct() {
		
		// add site icon
		add_action( 'wp_head', array( $this, 'add_site_icon' ) );
		
		// load assets
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );
		// remove default styles for Unyson Breadcrummbs
		add_action( 'wp_enqueue_scripts', array( $this, 'remove_assets' ), 99, 1 );
		add_action( 'wp_footer', array( $this, 'remove_assets' ) );
		
		// Change excerpt dots
		add_filter( 'excerpt_more', array( $this, 'change_excerpt_more' ) );
		
		// remove jquery migrate for optimization reasons
		add_filter( 'wp_default_scripts', array( $this, 'dequeue_jquery_migrate' ) );
		
		// WooCommerce customization
		add_filter( 'gettext', array( $this, 'wc_billing_field_strings' ), 20, 3 );
		
		// Rewrite rule for news
		add_filter( 'rewrite_rules_array', array( $this, 'rewrite_rules' ) );
		
		// WooCommerce
		add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'wc_cart_validation' ), 1, 5 );
		
		add_action( 'wp_head', array( $this, 'cart_empty_redirect_to_home' ));
	}
	
	/**
	 * Add site icon from customizer
	 **/
	function add_site_icon() {
		
		if ( function_exists( 'has_site_icon' ) && has_site_icon() ) {
			wp_site_icon();
		}
		
	}
	
	/**
	 * Load JavaScript and CSS files in a front-end
	 **/
	function load_assets() {
		
		// add support for visual composer animations, row stretching, parallax etc
		if ( function_exists( 'vc_asset_url' ) ) {
			wp_enqueue_script( 'waypoints', vc_asset_url( 'lib/waypoints/waypoints.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
			wp_enqueue_script( 'wpb_composer_front_js', vc_asset_url( 'js/dist/js_composer_front.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
		}
		
		// JS scripts
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'popper', get_template_directory_uri() . '/assets/libs/popper/popper.min.js', array( 'jquery' ), CYBERINT()->config['cache_time'], true );
		wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/libs/bootstrap/bootstrap.min.js', array( 'jquery' ), CYBERINT()->config['cache_time'], true );
		
		wp_register_script( 'google-fonts', '//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js', false, CYBERINT()->config['cache_time'], true );
		wp_register_script( 'fruitfulblankprefix-front', get_template_directory_uri() . '/assets/js/front.js', array(
			'jquery',
			'google-fonts'
		), CYBERINT()->config['cache_time'], true );
		
		$js_vars = array(
			'ajaxurl'    => admin_url( 'admin-ajax.php' ),
			'assetsPath' => get_template_directory_uri() . '/assets',
		);
		
		wp_enqueue_script( 'fruitfulblankprefix-front' );
		wp_localize_script( 'fruitfulblankprefix-front', 'themeJsVars', $js_vars );
		
		// CSS styles
		wp_enqueue_style( 'cyberint-fonts', get_template_directory_uri() . '/assets/css/front/fonts.css', false, CYBERINT()->config['cache_time'] );
		
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/libs/font-awesome/css/font-awesome.min.css', false, CYBERINT()->config['cache_time'] );
		wp_enqueue_style( 'animate', get_template_directory_uri() . '/assets/libs/animatecss/animate.min.css', true, CYBERINT()->config['cache_time'] );
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/libs/bootstrap/bootstrap.min.css', false, CYBERINT()->config['cache_time'] );

		
		wp_enqueue_style( 'cyberint-style', get_template_directory_uri() . '/assets/css/front/front.css', false, CYBERINT()->config['cache_time'] );
		
		if (is_checkout()) {
			wp_enqueue_style( 'cyberint-checkout', get_template_directory_uri() . '/assets/css/front/checkout.css', false, CYBERINT()->config['cache_time']);
		}
	}
	
	function remove_assets() {
		
		// disable huge default JS composer styles
		wp_dequeue_style( 'js_composer_front' );
		wp_dequeue_style( 'animate-css' );
		//wp_dequeue_style( 'fw-ext-breadcrumbs-add-css' );
		
	}
	
	/**
	 * Change excerpt More text
	 **/
	function change_excerpt_more( $more ) {
		return '…';
	}
	
	/**
	 * Remove jquery migrate for optimization reasons
	 **/
	function dequeue_jquery_migrate( $scripts ) {
		if ( ! is_admin() ) {
			$scripts->remove( 'jquery' );
			$scripts->add( 'jquery', false, array( 'jquery-core' ), '1.10.2' );
		}
	}
	
	/**
	 * Change WooCommerce default texts
	 *
	 * @param $translated_text
	 * @param $text
	 * @param $domain
	 *
	 * @return string|void
	 */
	function wc_billing_field_strings( $translated_text, $text, $domain ) {
		switch ( $translated_text ) {
			case 'Billing details' :
				$translated_text = __( 'Cyber exposure report', 'cyberintellige' );
				break;
			case 'Place order' :
				$translated_text = __( 'Purchase report', 'cyberintellige' );
				break;
		}
		return $translated_text;
	}
	
	/**
	 * Limit product quantity = 1
	 *
	 * @param $passed
	 * @param $product_id
	 * @param $quantity
	 * @param string $variation_id
	 * @param string $variations
	 */
	function wc_cart_validation( $passed, $product_id, $quantity, $variation_id = '', $variations = '' ) {
		
		WC()->cart->empty_cart();
		
		return $passed;
	}
	
	/**
	 * Modidy rewrite rules
	 */
	function rewrite_rules( $rules ) {
		$newrules = array();
		$newrules['news/([^/]+)/?$'] = 'index.php?pagename=news&category_name=$matches[1]';
		$newrules['news/([^/]+)/page/([0-9]+)/?$'] = 'index.php?pagename=news&category_name=$matches[1]&paged=$matches[2]';
		return $newrules + $rules;
	}
	
		function cart_empty_redirect_to_home() {
			global $woocommerce;
			
			if ( (is_page('cart') or is_checkout()) and !sizeof($woocommerce->cart->cart_contents) ) {
				wp_redirect(home_url());
				exit;
			}
		}

}
