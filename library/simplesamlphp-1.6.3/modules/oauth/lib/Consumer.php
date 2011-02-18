<?php

require_once(dirname(dirname(__FILE__)) . '/libextinc/OAuth.php');

/**
 * OAuth Consumer
 *
 * @author Andreas Åkre Solberg, <andreas.solberg@uninett.no>, UNINETT AS.
 * @package simpleSAMLphp
 * @version $Id: Consumer.php 2099 2010-01-12 07:04:29Z andreassolberg $
 */
class sspmod_oauth_Consumer {
	
	private $consumer;
	private $signer;
	
	public function __construct($key, $secret) {
		$this->consumer = new OAuthConsumer($key, $secret, NULL);
		$this->signer = new OAuthSignatureMethod_HMAC_SHA1();
	}
	
	// Used only to load the libextinc library early.
	public static function dummy() {}
	
	public function getRequestToken($url) {
		$req_req = OAuthRequest::from_consumer_and_token($this->consumer, NULL, "GET", $url, NULL);
		$req_req->sign_request($this->signer, $this->consumer, NULL);

		$response_req = file_get_contents($req_req->to_url());
		if ($response_req === FALSE) {
			throw new Exception('Error contacting request_token endpoint on the OAuth Provider');
		}

		parse_str($response_req, $responseParsed);
		
		if(array_key_exists('error', $responseParsed))
			throw new Exception('Error getting request token: ') . $responseParsed['error'];
			
		$requestToken = $responseParsed['oauth_token'];
		$requestTokenSecret = $responseParsed['oauth_token_secret'];
		
		return new OAuthToken($requestToken, $requestTokenSecret);
	}
	
	public function getAuthorizeRequest($url, $requestToken, $redirect = TRUE, $callback = NULL) {
		$authorizeURL = $url . '?oauth_token=' . $requestToken->key;
		if ($callback) {
			$authorizeURL .= '&oauth_callback=' . urlencode($callback);
		}
		if ($redirect) {
			SimpleSAML_Utilities::redirect($authorizeURL);
			exit;
		}	
		return $authorizeURL;
	}
	
	public function getAccessToken($url, $requestToken) {

		$acc_req = OAuthRequest::from_consumer_and_token($this->consumer, $requestToken, "GET", $url, NULL);
		$acc_req->sign_request($this->signer, $this->consumer, $requestToken);
		
		$response_acc = file_get_contents($acc_req->to_url());
		if ($response_acc === FALSE) {
			throw new Exception('Error contacting request_token endpoint on the OAuth Provider');
		}

		SimpleSAML_Logger::debug('oauth: Reponse to get access token: '. $response_acc);
		
		parse_str($response_acc, $accessResponseParsed);
		
		if(array_key_exists('error', $accessResponseParsed))
			throw new Exception('Error getting request token: ') . $accessResponseParsed['error'];
		
		$accessToken = $accessResponseParsed['oauth_token'];
		$accessTokenSecret = $accessResponseParsed['oauth_token_secret'];

		return new OAuthToken($accessToken, $accessTokenSecret);
	}
	
	public function postRequest($url, $accessToken, $parameters) {
		$data_req = OAuthRequest::from_consumer_and_token($this->consumer, $accessToken, "POST", $url, $parameters);
		$data_req->sign_request($this->signer, $this->consumer, $accessToken);
		$postdata = $data_req->to_postdata();

		$opts = array(
			'ssl' => array(
				'verify_peer' => FALSE,
				// 'cafile' => $file,
				// 'local_cert' => $spKeyCertFile,
				'capture_peer_cert' => TRUE,
				'capture_peer_chain' => TRUE,
			),
			'http' => array(
				'method' => 'POST',
				'content' => $postdata,
				'header' => 'Content-Type: application/x-www-form-urlencoded',
			),
		);
		$context = stream_context_create($opts);
		$response = file_get_contents($url, FALSE, $context);
		if ($response === FALSE) {
			throw new SimpleSAML_Error_Exception('Failed to push definition file to ' . $pushURL);
		}
		return $response;
	}
	
	public function getUserInfo($url, $accessToken) {
		
		$data_req = OAuthRequest::from_consumer_and_token($this->consumer, $accessToken, "GET", $url, NULL);
		$data_req->sign_request($this->signer, $this->consumer, $accessToken);

		$data = file_get_contents($data_req->to_url());
		#print_r($data);

		$dataDecoded = json_decode($data, TRUE);
		return $dataDecoded;
	}
	
}

