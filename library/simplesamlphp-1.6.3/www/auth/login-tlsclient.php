<?php


require_once('../../www/_include.php');

$config = SimpleSAML_Configuration::getInstance();
$metadata = SimpleSAML_Metadata_MetaDataStorageHandler::getMetadataHandler();
$session = SimpleSAML_Session::getInstance();

SimpleSAML_Logger::info('AUTH  - ldap: Accessing auth endpoint login');

$ldapconfig = SimpleSAML_Configuration::getConfig('ldap.php');


$error = null;
$attributes = array();
$username = null;

if (empty($session))
	SimpleSAML_Utilities::fatalError($session->getTrackID(), 'NOSESSION');

/* Load the RelayState argument. The RelayState argument contains the address
 * we should redirect the user to after a successful authentication.
 */
if (!array_key_exists('RelayState', $_REQUEST)) {
	SimpleSAML_Utilities::fatalError($session->getTrackID(), 'NORELAYSTATE');
}

$relaystate = $_REQUEST['RelayState'];


try {

	$attributes = array();
	$userid = null;

	if (!array_key_exists('SSL_CLIENT_VERIFY', $_SERVER))
		throw new Exception('Apache header variable SSL_CLIENT_VERIFY was not available. Recheck your apache configuration.');
	
	if (strcmp($_SERVER['SSL_CLIENT_VERIFY'], "SUCCESS") != 0) {
		SimpleSAML_Utilities::fatalError($session->getTrackID(), 'NOTVALIDCERT', $e);
	}
	
	$userid = $_SERVER['SSL_CLIENT_S_DN'];
	
	$attributes['CertificateDN']   = array($userid);
	$attributes['CertificateDNCN'] = array($_SERVER['SSL_CLIENT_S_DN_CN']);
	
	$session->doLogin('tlsclient');
	$session->setAttributes($attributes);
	
	#echo '<pre>';
	#print_r($_SERVER);
	#echo '</pre>'; exit;

	SimpleSAML_Logger::info('AUTH - tlsclient: '. $userid . ' successfully authenticated');
	
	
	$session->setNameID(array(
		'value' => SimpleSAML_Utilities::generateID(),
		'Format' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient'));
		
	/**
	 * Create a statistics log entry for every successfull login attempt.
	 * Also log a specific attribute as set in the config: statistics.authlogattr
	 */
	$authlogattr = $config->getValue('statistics.authlogattr', null);
	if ($authlogattr && array_key_exists($authlogattr, $attributes)) 
		SimpleSAML_Logger::stats('AUTH-tlsclient OK ' . $attributes[$authlogattr][0]);
	else 
		SimpleSAML_Logger::stats('AUTH-tlsclient OK');
		

	$returnto = $_REQUEST['RelayState'];
	SimpleSAML_Utilities::redirect($returnto);	
	
	
} catch (Exception $e) {
	SimpleSAML_Utilities::fatalError($session->getTrackID(), 'CONFIG', $e);

}



?>