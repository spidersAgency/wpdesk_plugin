<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'WPDesk_Logger' ) ) {
	class WPDesk_Logger {

		private $logger = false;

		const EMERGENCY = 'emergency';
		const ALERT     = 'alert';
		const CRITICAL  = 'critical';
		const ERROR     = 'error';
		const WARNING   = 'warning';
		const NOTICE    = 'notice';
		const INFO      = 'info';
		const DEBUG     = 'debug';

		/**
		 * Level strings mapped to integer severity.
		 *
		 * @var array
		 */
		protected $level_to_severity = array(
			self::EMERGENCY => 800,
			self::ALERT     => 700,
			self::CRITICAL  => 600,
			self::ERROR     => 500,
			self::WARNING   => 400,
			self::NOTICE    => 300,
			self::INFO      => 200,
			self::DEBUG     => 100,
		);

		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
			add_filter( 'wpdesk_logger_level_options', array( $this, 'wpdesk_logger_level_options' ) );
		}

		public function plugins_loaded() {
			if ( defined( 'WC_VERSION' ) ) {
				if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
					add_action( 'wpdesk_log', array( $this, 'wpdesk_log' ), 10, 4 );
				} else {
					add_action( 'wpdesk_log', array( $this, 'wpdesk_log_30' ), 10, 4 );
				}
			}
		}

		public function wpdesk_logger_level_options( array $options ) {
			return array(
				'disabled'      => __( 'Disabled', 'wpdesk-helper' ),
				'emergency'     => __( 'Emergency', 'wpdesk-helper' ),
				'alert'         => __( 'Alert', 'wpdesk-helper' ),
				'critical'      => __( 'Critical', 'wpdesk-helper' ),
				'error'         => __( 'Error', 'wpdesk-helper' ),
				'warning'       => __( 'Warning', 'wpdesk-helper' ),
				'notice'        => __( 'Notice', 'wpdesk-helper' ),
				'info'          => __( 'Info', 'wpdesk-helper' ),
				'debug'         => __( 'Debug', 'wpdesk-helper' ),
			);
		}

		public function wpdesk_log( $level, $source, $message, $settings_level = 'debug' ) {
			if ( !isset( $this->level_to_severity[$settings_level] ) || !isset( $this->level_to_severity[$level] ) ) {
				return;
			}
			if ( $this->level_to_severity[$settings_level] >  $this->level_to_severity[$level] ) {
				return;
			}
			if ( $this->logger === false ) {
				$this->logger = new WC_Logger();
			}
			if ( is_array( $message ) || is_object( $message ) ) {
				$message = print_r( $message, true );
			}
			$this->logger->add( $source, '[' . $level . '] ' . $message );
		}

		public function wpdesk_log_30( $level, $source, $message, $settings_level = 'debug' ) {
			if ( !isset( $this->level_to_severity[$settings_level] ) || !isset( $this->level_to_severity[$level] ) ) {
				return;
			}
			if ( $this->level_to_severity[$settings_level] >  $this->level_to_severity[$level] ) {
				return;
			}
			$logger = wc_get_logger();
			if ( is_array( $message ) || is_object( $message ) ) {
				$message = print_r( $message, true );
			}
			$logger->log( $level, $message, array( 'source' => $source ) );
		}

	}

	new WPDesk_Logger();

}

