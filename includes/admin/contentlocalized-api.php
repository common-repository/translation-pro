<?php

	namespace Contentlocalized {

		class ContentlocalizedAPI {

			private static $endpoint = "https://www.contentlocalized.com/api/v2/";
			

			/**
			 * Get current balance
			 *
			 * @return array|mixed|null|object
			 * @throws Requests_Exception
			 */
			public static function GetBalance() {
				return self::_CallAPI( 'balance/', \Requests::GET, true );
			}

			/**
			 * Check new login details
			 *
			 * @param $username
			 * @param $password
			 *
			 * @return array|mixed|null|object
			 */
			public static function CheckLoginDetails( $username, $password ) {
				$header                  = array();
				$header['Authorization'] = 'Basic ' . base64_encode( $username . ':' . $password );
				$result                  = [];

				try {
					$requests_response = \Requests::request( self::$endpoint . 'balance/', $header, array(), \Requests::GET, array() );
					$result            = json_decode( $requests_response->body, true );
				} catch ( Exception $e ) {
					$result = null;
				}

				return $result;
			}

			/**
			 * Get delivery by product id
			 *
			 * @param $product_id
			 *
			 * @return array|mixed|object|string
			 * @throws Requests_Exception
			 */
			public static function GetDeliveryByProductId( $product_id ) {
				return self::_CallAPI( "product/$product_id/delivery", \Requests::GET, true );
			}

			/**
			 * Get language list
			 *
			 * @param      $product_id
			 * @param bool $lng_code
			 *
			 * @return array|mixed|object|string
			 * @throws Requests_Exception
			 */
			public static function GetLanguageList( $product_id, $lng_code = false ) {
				$params = array();

				if ( $lng_code ) {
					$params["lng_code"] = $lng_code;
				}

				return self::_CallAPI( 'product/' . $product_id . '/language', \Requests::GET, true, $params );
			}

			/**
			 * Calculate order
			 *
			 * @param $params
			 *
			 * @return array|mixed|object|string
			 * @throws Requests_Exception
			 */
			public static function CalculateOrder( $params ) {
				return self::_CallAPI( 'order/calculate', \Requests::POST, true, $params );
			}

			/**
			 * Create order
			 *
			 * @param $product_id
			 * @param $data
			 *
			 * @return array|mixed|object|string
			 * @throws Requests_Exception
			 */
			public static function CreateOrder( $product_id, $data ) {
				return self::_CallAPI( "product/$product_id/create", \Requests::POST, true, $data );
			}

			/**
			 * Article list
			 *
			 * @param $params
			 *
			 * @return array|mixed|object|string
			 * @throws Requests_Exception
			 */
			public static function ArticleList( $params ) {
				return self::_CallAPI( 'article/list', \Requests::GET, true, $params );
			}

			/**
			 * Get order by id
			 *
			 * @param $id
			 *
			 * @return array|mixed|object|string
			 * @throws Requests_Exception
			 */
			public static function ArticleView( $id ) {
				return self::_CallAPI( 'order/' . $id, \Requests::GET, true );
			}

			/**
			 * Sing up new user
			 *
			 * @param $data
			 *
			 * @return array|mixed|null|object
			 * @throws Requests_Exception
			 */
			public static function SignUp( $data ) {
				return self::_CallAPI( 'singup', \Requests::POST, false, $data );
			}

			/**
			 * Get autologin endpoint
			 *
			 * @return string
			 */
			public static function GetAutologinEndPoint() {
				return self::$endpoint . 'balance/autologin';
			}

			/**
			 * Call CL API
			 *
			 * @param       $endpoint
			 * @param       $type
			 * @param array $params
			 * @param array $header
			 * @param array $options
			 *
			 * @throws Requests_Exception
			 */
			private static function _CallAPI( $endpoint, $type = \Requests::GET, $basic_auth = false, $params = array(), $options = array() ) {
				$header = array();

				if ( $basic_auth ) {
					self::_BasicAuth( $header );
				}

				$result = [];

				try {
					$requests_response = \Requests::request( self::$endpoint . $endpoint, $header, $params, $type, $options );

					if ( $requests_response->body == "Invalid credentials." ) {
						return array(
							'status' => false,
							'msg'    => 'Invalid credentials.'
						);
					}

					$result = json_decode( $requests_response->body, true );
				} catch ( Exception $e ) {
					$result = $e->getMessage();
				}

				return $result;
			}

			/**
			 * Add param to header
			 *
			 * @param $header
			 */
			private static function _BasicAuth( &$header ) {
				$username = get_option( 'cltp_api_username' );
				$password = get_option( 'cltp_api_password' );

				$header['Authorization'] = 'Basic ' . base64_encode( $username . ':' . $password );
			}
		}
	}