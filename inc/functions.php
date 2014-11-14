<?php # -*- coding: utf-8 -*-

namespace FailedLoginNotifier;

/**
 * instantiate the front controller
 * and runs the plugin
 *
 * @wp-hook wp_loaded
 * @return void
 */
function init() {

	$data = (object) array(
		'plugin_dir' => dirname( __DIR__ ),
		'options'    => (object) array(
			'mail_to'      => get_option( 'admin_email' ),
			'mail_from'    => get_option( 'admin_email' ),
			'mail_subject' => 'Failed Login [' . get_option( 'name' ) . ']',
		)
	);
	$autoloader = init_autoloader( $data->plugin_dir . '/lib' );
	setup_autoloading( $autoloader, $data->plugin_dir );

	$plugin = new FailedLoginNotifier( $data );
	$plugin->run();
}

/**
 * setup autoloading rules
 *
 * @param \Requisite\SPLAutoLoader $autoloader
 * @param string $plugin_dir
 * @return \Requisite\SPLAutoLoader
 */
function setup_autoloading( \Requisite\SPLAutoloader $autoloader, $plugin_dir ) {

	$autoloader->addRule(
		new \Requisite\Rule\NamespaceDirectoryMapper(
			$plugin_dir . '/inc',
			__NAMESPACE__
		)
	);
	// PSR
	$autoloader->addRule(
		new \Requisite\Rule\NamespaceDirectoryMapper(
			$plugin_dir . '/lib/Psr',
			'Psr'
		)
	);
	// Monolog
	$autoloader->addRule(
		new \Requisite\Rule\NamespaceDirectoryMapper(
			$plugin_dir . '/lib/Monolog',
			'Monolog'
		)
	);

	return $autoloader;
}

/**
 * inits the autoloader
 *
 * @param string $dir (The path to the lib/ directory)
 * @return FALSE|\Requisite\SPLAutoLoader
 */
function init_autoloader( $dir ) {

	if ( class_exists( '\Requisite\SPLAutoLoader' ) )
		return new \Requisite\SPLAutoLoader;

	$requisite = $dir . '/Requisite/Requisite.php';
	if ( ! is_readable( $requisite ) )
		return FALSE;

	require_once $requisite;
	\Requisite\Requisite::init();

	return new \Requisite\SPLAutoLoader;
}