<?php # -*- coding: utf-8 -*-


namespace FailedLoginNotifier;


class FailedLoginNotifier {

	/**
	 * @type \stdClass
	 */
	private $data;

	/**
	 * @param \stdClass $data
	 */
	function __construct( \stdClass $data ) {

		apply_filters( 'fln_options', $data->options );
		$this->data = $data;
	}

	/**
	 * @wp-hook init
	 */
	public function run() {

		$handler = new \Monolog\Handler\NativeMailerHandler(
			$this->data->options->mail_to,
			$this->data->options->mail_subject,
			$this->data->options->mail_from,
			\Monolog\Logger::WARNING
		);
		$handler->setFormatter( new Formatter\WPAdminMailFormatter );
		$handlers = apply_filters( 'fln_log_handler', array( $handler ) );
		$logger   = new \Monolog\Logger( 'FLN', $handlers );

		$notifier = new Model\LogFailedLogins( $logger );

		add_action( 'wp_login_failed', array( $notifier, 'logFailedLogin') );
	}
} 