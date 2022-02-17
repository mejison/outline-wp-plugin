<?php

class Outline {
    const API_HOST = 'salvation.inspirationhosting.com';
	const API_PORT = 80;

    public static function init() {
		// if( is_admin() || current_user_can('editor') || current_user_can('administrator')) {
		// 	return;
		// }

		$onOff = get_option('on_off');
		if ($onOff == 'on') {
			self::trackingVisited();
		}
	}

	public static function gformSubmitHook($form) {
		$onOff = get_option('on_off');
		if ($onOff == 'on') {
			if(self::isSalvationForm($form['form_id'])) {
				self::trackingVisited("salvation");
			}
		}
	}

	public static function trackingVisited($eventType = 'visit') {
		if (self::isDiscipleshipCategories()) {
			$eventType = 'discipleship';
		}

		if (self::isDiscipleshipPosts()) {
			$eventType = 'discipleship';
		}

		if (self::isDiscipleshipPages()) {
			$eventType = 'discipleship';
		}

		$latitude = '';
		$longitude = '';

		if ( ! session_id()) {
			session_start();
		}

		if ( ! isset($_SESSION["latitude"]) ||  ! isset($_SESSION["longitude"])) {
			$ip = self::get_ip_address();
			$ip = in_array($ip, ["::1"]) ? "161.185.160.93" : $ip;

			$body = self::get_lat_lng($ip);
			
			if ( ! empty($body)) {
				$latitude = $body->geoplugin_latitude;
				$longitude = $body->geoplugin_longitude;
	
				$_SESSION["latitude"] = $latitude;
				$_SESSION["latitude"] = $longitude;
			}
		} else {
			$latitude = $_SESSION["latitude"];
			$longitude = $_SESSION["longitude"];
		}

		if ($eventType) {

			self::http_post([
				"vistorID" => time(),
				"eventType" => $eventType,  // visit | salvation | discipleship
				"ip" => self::get_ip_address(),
				"url" => self::get_url(),
				"userAgent" => self::get_user_agent(),
				"vendorId" => '',
				"campaignId" => '',
				"adId" => '',
				"serverName" => self::get_server_name(),
				"language" => self::get_language(),
				"referrer" => self::get_referer(),
				"socialSource" => '',
	
				"latitude" => $latitude,
				"longitude" => $longitude,
			], "add");
		}

	}

	private static function isDiscipleshipPosts() {
		$trackingPosts = get_option('discipleship_posts') ? array_keys(get_option('discipleship_posts')) : [];
		$postID = get_post() ? get_post()->ID : null;

		return in_array($postID, $trackingPosts);
	}

	private static function isSalvationForm($formID) {
		$trackingSalvation = get_option('salvation') ? array_keys(get_option('salvation')) : [];
		return in_array($formID, $trackingSalvation);
	}

	private static function isDiscipleshipPages() {
		$trackingPages = get_option('discipleship_pages') ? array_keys(get_option('discipleship_pages')) : [];
		$pageID = get_post() ? get_post()->ID : null;

		return in_array($pageID, $trackingPages);
	}

	private static function isDiscipleshipCategories() {
		$trackingCategories = get_option('discipleship_categories') ? array_keys(get_option('discipleship_categories')) : [];
		$currentCategories = array_map(function($item) {
			return $item->cat_ID;
		}, get_the_category() ? get_the_category() : []);

		return count(array_intersect($trackingCategories, $currentCategories));
	}

    public static function http_post( $body, $path) {
		$api_key   = self::get_api_key();
		$host      = self::API_HOST;

		if ( !empty( $api_key ) )
			$host = $api_key.'.'.$host;

		$http_host = $host;

		$http_args = array(
			'body' => json_encode($body),
			'headers' => array(
				'Content-Type' => 'application/json',
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

	private static function get_server_name() {
		return isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : null;
	}

	private static function get_url() {
		return isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : null;
	}

	private static function get_language() {
		return isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'en';
	}

	private static function get_lat_lng($ip) {
		try {
			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			$simplified_response = file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip);
			return json_decode($simplified_response);
		} catch (Exception $exeption) {
			return false;
		}
	}
}