<?php # -*- coding: utf-8 -*-

/**
 * Plugin Name: Failed Login Notifier
 * Author: David Naber
 * Author URI: http://dnaber.de/
 * Version: 2014.11.14
 */
namespace FailedLoginNotifier;

require_once 'inc/functions.php';

add_action( 'init', __NAMESPACE__ . '\init' );