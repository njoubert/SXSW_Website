<?php

require 'oauth/oauth_helper.php'

class NOAuth {
	
	private $server;

	static public function get_for_twitter() {
		$server = array(
			'consumer_key' => 'EXDVa0kuyP4qsbawmmEzow',
			'consumer_secret' => 'mIr7nEy3RcVnu3QW2e1Hh1KA1ZgMeE2fSbsNhQsSFY',
			'server_uri' => 'https://api.twitter.com/oauth/',
			'use_hmac_sha1' => true,
			'request_token_uri' => 'https://api.twitter.com/oauth/request_token',
			'authorize_uri' => 'https://api.twitter.com/oauth/authorize',
			'access_token_uri' => 'https://api.twitter.com/oauth/access_token',
			'callback_uri' => 'http://glebden.com/djdp3000/twitter_callback.php'
		);
		return new NOAuth($server);
	}

	
	function __construct($server) {
		$this->server = $server;
	}
	
	
	function get_request_token() {
		$retarr = array();  // return value
		$response = array();

		$url = $this->server['request_token_uri'];
		$params['oauth_version'] = '1.0';
		$params['oauth_nonce'] = mt_rand();
		$params['oauth_timestamp'] = time();
		$params['oauth_consumer_key'] = $this->server['consumer_key'];
		$params['oauth_callback'] = $this->server['callback_uri'];

		// compute signature and add it to the params list
		if ($this->server['use_hmac_sha1']) {
		  $params['oauth_signature_method'] = 'HMAC-SHA1';
		  $params['oauth_signature'] =
		    oauth_compute_hmac_sig($usePost? 'POST' : 'GET', $url, $params,
		                           $this->server['consumer_secret'], null);
		} else {
		  $params['oauth_signature_method'] = 'PLAINTEXT';
		  $params['oauth_signature'] =
		    oauth_compute_plaintext_sig($this->server['consumer_secret'], null);
		}

		// Pass OAuth credentials in a separate header or in the query string
		if ($passOAuthInHeader) {
		  $query_parameter_string = oauth_http_build_query($params, true);
		  $header = build_oauth_header($params, "api.twitter.com");
		  $headers[] = $header;
		} else {
		  $query_parameter_string = oauth_http_build_query($params);
		}

		// POST or GET the request
		if ($usePost) {
		  $request_url = $url;
		  logit("getreqtok:INFO:request_url:$request_url");
		  logit("getreqtok:INFO:post_body:$query_parameter_string");
		  $headers[] = 'Content-Type: application/x-www-form-urlencoded';
		  $response = do_post($request_url, $query_parameter_string, 443, $headers);
		} else {
		  $request_url = $url . ($query_parameter_string ?
		                         ('?' . $query_parameter_string) : '' );
		  logit("getreqtok:INFO:request_url:$request_url");
		  $response = do_get($request_url, 443, $headers);
		}

		// extract successful response
		if (! empty($response)) {
		  list($info, $header, $body) = $response;
		  $body_parsed = oauth_parse_str($body);
		  if (! empty($body_parsed)) {
		    logit("getreqtok:INFO:response_body_parsed:");
		    //print_r($body_parsed);
		  }
		  $retarr = $body_parsed;
		}

		return $retarr;

	}
	
	
}


?>