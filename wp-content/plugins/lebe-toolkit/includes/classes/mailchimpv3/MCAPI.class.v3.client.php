<?php
if ( !class_exists( 'MCAPI_Client' ) ) {
	class MCAPI_Client
	{
		/**
		 * @var string
		 */
		private $api_key;
		/**
		 * @var string
		 */
		private $api_url = 'https://api.mailchimp.com/3.0/';
		/**
		 * @var array
		 */
		private $last_response;
		/**
		 * @var array
		 */
		private $last_request;

		/**
		 * Constructor
		 *
		 * @param string $api_key
		 */
		public function __construct( $api_key )
		{
			$this->api_key = $api_key;
			$dash_position = strpos( $api_key, '-' );
			if ( $dash_position !== false ) {
				$this->api_url = str_replace( '//api.', '//' . substr( $api_key, $dash_position + 1 ) . ".api.", $this->api_url );
			}
		}

		/**
		 * @param string $resource
		 * @param array $args
		 *
		 * @return mixed
		 * @throws MCAPI_API_Exception
		 */
		public function get( $resource, array $args = array() )
		{
			return $this->request( 'GET', $resource, $args );
		}

		/**
		 * @param string $resource
		 * @param array $data
		 *
		 * @return mixed
		 * @throws MCAPI_API_Exception
		 */
		public function post( $resource, array $data )
		{
			return $this->request( 'POST', $resource, $data );
		}

		/**
		 * @param string $resource
		 * @param array $data
		 * @return mixed
		 * @throws MCAPI_API_Exception
		 */
		public function put( $resource, array $data )
		{
			return $this->request( 'PUT', $resource, $data );
		}

		/**
		 * @param string $resource
		 * @param array $data
		 * @return mixed
		 * @throws MCAPI_API_Exception
		 */
		public function patch( $resource, array $data )
		{
			return $this->request( 'PATCH', $resource, $data );
		}

		/**
		 * @param string $resource
		 * @return mixed
		 * @throws MCAPI_API_Exception
		 */
		public function delete( $resource )
		{
			return $this->request( 'DELETE', $resource );
		}

		/**
		 * @param string $method
		 * @param string $resource
		 * @param array $data
		 *
		 * @return mixed
		 *
		 * @throws MCAPI_API_Exception
		 */
		private function request( $method, $resource, array $data = array() )
		{
			$this->reset();
			// don't bother if no API key was given.
			if ( empty( $this->api_key ) ) {
				throw new MCAPI_API_Exception( "Missing API key", 001 );
			}
			$url  = $this->api_url . ltrim( $resource, '/' );
			$args = array(
				'url'       => $url,
				'method'    => $method,
				'headers'   => $this->get_headers(),
				'timeout'   => 10,
				'sslverify' => apply_filters( 'MCAPI_use_sslverify', true ),
			);
			if ( !empty( $data ) ) {
				if ( in_array( $method, array( 'GET', 'DELETE' ) ) ) {
					$url = add_query_arg( $data, $url );
				} else {
					$args['body'] = json_encode( $data );
				}
			}
			/**
			 * Filter the request arguments for all requests generated by this class
			 *
			 * @param array $args
			 */
			$args = apply_filters( 'MCAPI_http_request_args', $args, $url );
			// perform request
			$response = wp_remote_request( $url, $args );
			// store request & response
			$this->last_request  = $args;
			$this->last_response = $response;
			// parse response
			$data = $this->parse_response( $response );

			return $data;
		}

		/**
		 * @return array
		 */
		private function get_headers()
		{
			global $wp_version;
			$headers                  = array();
			$headers['Authorization'] = 'Basic ' . base64_encode( 'MCAPI:' . $this->api_key );
			$headers['Accept']        = 'application/json';
			$headers['Content-Type']  = 'application/json';
			$headers['User-Agent']    = 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' );
			// Copy Accept-Language from browser headers
			if ( !empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
				$headers['Accept-Language'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
			}

			return $headers;
		}

		/**
		 * @param array|WP_Error $response
		 *
		 * @return mixed
		 *
		 * @throws MCAPI_API_Exception
		 */
		private function parse_response( $response )
		{
			if ( $response instanceof WP_Error ) {
				throw new MCAPI_API_Connection_Exception( $response->get_error_message(), (int)$response->get_error_code() );
			}
			// decode response body
			$code    = (int)wp_remote_retrieve_response_code( $response );
			$message = wp_remote_retrieve_response_message( $response );
			$body    = wp_remote_retrieve_body( $response );
			// set body to "true" in case MailChimp returned No Content
			if ( $code < 300 && empty( $body ) ) {
				$body = "true";
			}
			$data = json_decode( $body );
			if ( $code >= 400 ) {
				// check for akamai errors
				// {"type":"akamai_error_message","title":"akamai_503","status":503,"ref_no":"Reference Number: 00.950e16c3.1498559813.1450dbe2"}
				if ( is_object( $data ) && isset( $data->type ) && $data->type === 'akamai_error_message' ) {
					throw new MCAPI_API_Connection_Exception( $message, $code, $this->last_request, $this->last_response, $data );
				}
				if ( $code === 404 ) {
					throw new MCAPI_API_Resource_Not_Found_Exception( $message, $code, $this->last_request, $this->last_response, $data );
				}
				// mailchimp returned an error..
				throw new MCAPI_API_Exception( $message, $code, $this->last_request, $this->last_response, $data );
			}
			if ( !is_null( $data ) ) {
				return $data;
			}
			// unable to decode response
			throw new MCAPI_API_Exception( $message, $code, $this->last_request, $this->last_response );
		}

		/**
		 * Empties all data from previous response
		 */
		private function reset()
		{
			$this->last_response = null;
			$this->last_request  = null;
		}

		/**
		 * @return string
		 */
		public function get_last_response_body()
		{
			return wp_remote_retrieve_body( $this->last_response );
		}

		/**
		 * @return array
		 */
		public function get_last_response_headers()
		{
			return wp_remote_retrieve_headers( $this->last_response );
		}

		/**
		 * @return array|WP_Error
		 */
		public function get_last_response()
		{
			return $this->last_response;
		}
	}
}
