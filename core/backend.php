<?php

namespace cyberint\controller;

/**
 * Backend controller
 **/
class backend {
	
	/**
	 * Constructor
	 **/
	function __construct() {
		
		// load admin assets
		add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
		
		// install required plugins
		require_once get_template_directory() . '/vendor/tgm/class-tgm-plugin-activation.php';
		add_action( 'tgmpa_register', array( $this, 'tgmpa_register' ) );
		
		// Change theme options default menu position
		add_action( 'fw_backend_add_custom_settings_menu', array( $this, 'add_theme_options_menu' ) );

		// Allow additional mime types
		add_filter( 'upload_mimes', array( $this, 'add_upload_types' ) );
		add_filter( 'wp_check_filetype_and_ext', array( $this, 'ignore_upload_ext' ), 10, 4);
		
	}
	
	/**
	 * Load admin assets
	 **/
	function load_assets() {
		wp_enqueue_style( 'fruitfulblankprefix-backend', get_template_directory_uri() . '/assets/css/admin/admin.css', false, CYBERINT()->config['cache_time'] );
	}
	
	/**
	 * Install required plugins
	 **/
	function tgmpa_register() {
		
		$plugins = array(
			
			array(
				'name'     => 'Unyson',
				'slug'     => 'unyson',
				'required' => false
			),
			
			array(
				'name'         => 'WPBakery Page Builder',
				'slug'         => 'js_composer',
				'source'       => 'https://fruitfulcode.com/themeforest/js_composer.zip',
				'required'     => false,
				'version'      => '',
				'external_url' => '',
			),
		
		);
		
		// it is not necessairy to provide custom language config for TGM, so just leave it default
		tgmpa( $plugins );
		
	}
	
	/**
	 * Add Website Options Menu
	 **/
	function add_theme_options_menu( $data ) {
		
		add_menu_page(
			esc_html__( 'Website Settings', 'cyberintellige' ),
			esc_html__( 'Website Settings', 'cyberintellige' ),
			$data['capability'],
			$data['slug'],
			$data['content_callback']
		);
		
	}

	/**
	 * Allow additional mime types to upload
	 **/
	function add_upload_types( $existing_mimes ) {
		$existing_mimes['ico'] = 'image/vnd.microsoft.icon';
		$existing_mimes['eot'] = 'application/vnd.ms-fontobject';
		$existing_mimes['woff2'] = 'application/x-woff';
		$existing_mimes['woff'] = 'application/x-woff';
		$existing_mimes['ttf'] = 'application/octet-stream';
		$existing_mimes['svg'] = 'image/svg+xml';
		$existing_mimes['mp4'] = 'video/mp4';
		$existing_mimes['ogv'] = 'video/ogg';
		$existing_mimes['webm'] = 'video/webm';
		return $existing_mimes;
	}

	function ignore_upload_ext( $checked, $file, $filename, $mimes ) {

		//we only need to worry if WP failed the first pass
		if(!$checked['type']){
			//rebuild the type info
			$wp_filetype = wp_check_filetype( $filename, $mimes );
			$ext = $wp_filetype['ext'];
			$type = $wp_filetype['type'];
			$proper_filename = $filename;

			//preserve failure for non-svg images
			if($type && 0 === strpos($type, 'image/') && $ext !== 'svg'){
				$ext = $type = false;
			}

			//everything else gets an OK, so e.g. we've disabled the error-prone finfo-related checks WP just went through. whether or not the upload will be allowed depends on the <code>upload_mimes</code>, etc.

			$checked = compact('ext','type','proper_filename');
		}

		return $checked;

	}
	
}
