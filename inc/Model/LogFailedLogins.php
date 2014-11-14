<?php # -*- coding: utf-8 -*-


namespace FailedLoginNotifier\Model;


class LogFailedLogins {

	/**
	 * @type \Monolog\Logger
	 */
	private $logger;

	/**
	 * @param \Monolog\Logger $logger
	 */
	function __construct( \Monolog\Logger $logger ) {

		$this->logger = $logger;
	}

	/**
	 * @wp-hook wp_login_failed
	 * @param string $user_name
	 * @return string
	 */
	public function logFailedLogin( $user_name ) {

		$context = array(
			'IP'    => $_SERVER[ 'REMOTE_ADDR' ],
			'user'  => $user_name,
			'whois' => 'http://ip-lookup.net/?' . $_SERVER[ 'REMOTE_ADDR' ]
		);
		$this->logger->warning( 'Failed Login', $context );

		return $user_name;
	}
}