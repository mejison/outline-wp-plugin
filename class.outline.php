<?php

class Outline {
    const API_HOST = 'rest.outline.com';
	const API_PORT = 80;

    private static $initiated = false;

    public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	/**
	 * Initializes WordPress hooks
	 */
	private static function init_hooks() {
		self::$initiated = true; 
    }

    public static function http_post( $request, $path) {
		$content_length = strlen( $request );

		$api_key   = self::get_api_key();
		$host      = self::API_HOST;

		if ( !empty( $api_key ) )
			$host = $api_key.'.'.$host;

		$http_host = $host;

		$http_args = array(
			'body' => $request,
			'headers' => array(
				'Content-Type' => 'application/x-www-form-urlencoded; charset=' . get_option( 'blog_charset' ),
				'Host' => $host,
			),
			'httpversion' => '1.0',
			'timeout' => 15
		);

		$outline_url = $http_outline_url = "http://{$http_host}/api/v1/{$path}";
		$response = wp_remote_post( $outline_url, $http_args );
		$simplified_response = array( $response['headers'], $response['body'] );
        
		return $simplified_response;
	}

    public static function plugin_activation() {
    }

    public static function plugin_deactivation() {
    }

    public static function get_api_key() {
		return apply_filters( 'outline_get_api_key', defined('WPCOM_API_KEY') ? constant('WPCOM_API_KEY') : get_option('wordpress_api_key') );
	}
	
    public static function get_ip_address() {
		return isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : null;
	}

    private static function get_user_agent() {
		return isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : null;
	}

    private static function get_referer() {
		return isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : null;
	}
}