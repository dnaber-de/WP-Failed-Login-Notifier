<?php # -*- coding: utf-8 -*-

namespace FailedLoginNotifier\Formatter;
use Monolog\Formatter;

class WPAdminMailFormatter implements Formatter\FormatterInterface {

	/**
	 * @type string
	 */
	private $dateTimeFormat = \DateTime::W3C;

	/**
	 * Formats a log record.
	 *
	 * @param  array $record A record to format
	 *
	 * @return mixed The formatted record
	 */
	public function format( array $record ) {

		return $this->formatSingleRecord( $record );
	}

	/**
	 * Formats a set of log records.
	 *
	 * @param  array $records A set of records to format
	 *
	 * @return mixed The formatted set of records
	 */
	public function formatBatch( array $records ) {

		$msg = '';
		foreach ( $records as $record )
			$msg .= $this->formatSingleRecord( $record );

		return $msg;
	}

	/**
	 * format a single record
	 */
	public function formatSingleRecord( array $record ) {

		$message = <<<STR
[{$record[ 'datetime' ]->format( $this->dateTimeFormat )}] {$record[ 'channel' ]}: {$record[ 'level_name' ]}
{$record[ 'message' ]}
 -- Context --
{$this->printExtraFields( $record[ 'context' ] )}
 -- Extra --
{$this->printExtraFields( $record[ 'extra' ] )}

STR;

		return $message;
	}

	public function printExtraFields( array $record ) {

		$str = '';
		$max_key_length = 0;
		foreach ( array_keys( $record ) as $key ) {
			$key_length = strlen( $key );
			if ( $key_length > $max_key_length )
				$max_key_length = $key_length;
		}

		foreach ( $record as $key => $value ) {
			$gap_length = $max_key_length - strlen( $key );
			$str .= $key
				. ': '
				. str_repeat( ' ', $gap_length )
				. $this->convertToString( $value )
				. PHP_EOL;
		}

		return rtrim( $str );
	}

	/**
	 * @param string $format
	 */
	public function setDateTimeFormat( $format ) {

		$this->dateTimeFormat = (string) $format;
	}

	/**
	 * @param mixed $var
	 * @return string
	 */
	public function convertToString( $var ) {

		if ( is_null( $var ) || is_scalar( $var ) )
			return (string) $var;

		return str_replace( '\\/', '/', json_encode( $var ) );
	}
}