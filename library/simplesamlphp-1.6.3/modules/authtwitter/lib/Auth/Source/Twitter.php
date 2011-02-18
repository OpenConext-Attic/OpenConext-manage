<?php

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/oauth/libextinc/OAuth.php');

/**
 * Authenticate using Twitter.
 *
 * @author Andreas Åkre Solberg, UNINETT AS.
 * @package simpleSAMLphp
 * @version $Id: Twitter.php 1933 2009-10-29 07:53:56Z andreassolberg $
 */
class sspmod_authtwitter_Auth_Source_Twitter extends SimpleSAML_Auth_Source {

	/**
	 * The string used to identify our states.
	 */
	const STAGE_INIT = 'twitter:init';

	/**
	 * The key of the AuthId field in the state.
	 */
	const AUTHID = 'twitter:AuthId';

	private $key;
	private $secret;


	/**
	 * Constructor for this authentication source.
	 *
	 * @param array $info  Information about this authentication source.
	 * @param array $config  Configuration.
	 */
	public function __construct($info, $config) {
		assert('is_array($info)');
		assert('is_array($config)');

		/* Call the parent constructor first, as required by the interface. */
		parent::__construct($info, $config);

		if (!array_key_exists('key', $config))
			throw new Exception('Twitter authentication source is not properly configured: missing [key]');
		
		$this->key = $config['key'];
		
		if (!array_key_exists('secret', $config))
			throw new Exception('Twitter authentication source is not properly configured: missing [secret]');

		$this->secret = $config['secret'];

		// require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/extlibinc/facebook.php');

	}


	/**
	 * Log-in using Facebook platform
	 *
	 * @param array &$state  Information about the current authentication.
	 */
	public function authenticate(&$state) {
		assert('is_array($state)');

		/* We are going to need the authId in order to retrieve this authentication source later. */
		$state[self::AUTHID] = $this->authId;
		
		$stateID = SimpleSAML_Auth_State::saveState($state, self::STAGE_INIT);
		
		// SimpleSAML_Logger::debug('facebook auth state id = ' . $stateID);
		
		$consumer = new sspmod_oauth_Consumer($this->key, $this->secret);

		// Get the request token
		$requestToken = $consumer->getRequestToken('http://twitter.com/oauth/request_token');
		SimpleSAML_Logger::debug("Got a request token from the OAuth service provider [" . 
			$requestToken->key . "] with the secret [" . $requestToken->secret . "]");

		$oauthState = array(
			'requestToken' => serialize($requestToken),
			'stateid' => $stateID,
		);
		$session = SimpleSAML_Session::getInstance();
		$session->setData('oauth', 'oauth', $oauthState);

		// Authorize the request token
		$consumer->getAuthorizeRequest('http://twitter.com/oauth/authenticate', $requestToken);

	}
	
	
	
	public function finalStep(&$state) {
		
		
		
		
		$requestToken = unserialize($state['requestToken']);
		
		#echo '<pre>'; print_r($requestToken); exit;
		
		$consumer = new sspmod_oauth_Consumer($this->key, $this->secret);
		
		SimpleSAML_Logger::debug("oauth: Using this request token [" . 
			$requestToken->key . "] with the secret [" . $requestToken->secret . "]");

		// Replace the request token with an access token
		$accessToken = $consumer->getAccessToken('http://twitter.com/oauth/access_token', $requestToken);
		SimpleSAML_Logger::debug("Got an access token from the OAuth service provider [" . 
			$accessToken->key . "] with the secret [" . $accessToken->secret . "]");
			

		
		$userdata = $consumer->getUserInfo('http://twitter.com/account/verify_credentials.json', $accessToken);
		
		$attributes = array();
		foreach($userdata AS $key => $value) {
			if (is_string($value))
				$attributes[$key] = array((string)$value);
			
		}
		
		if (array_key_exists('screen_name', $userdata) )
			$attributes['eduPersonPrincipalName'] = array('@' . $userdata['screen_name']);
		if (array_key_exists('name', $userdata) )
			$attributes['displayName'] = array($userdata['name']);
		if (array_key_exists('profile_image_url', $userdata) )
			$attributes['jpegPhoto'] = array(base64_encode(file_get_contents($userdata['profile_image_url'])));
		if (array_key_exists('url', $userdata) )
			$attributes['labeledURI'] = array($userdata['url']);
			
		
		$state['Attributes'] = $attributes;
	}

}

?>