<?php
/**
 *
 */

class EngineBlock_Model_GroupProvider extends Default_Model_Abstract
{

    public static $allowedOptions = array(
        'adapter'               => 'adapter',
        'url'                   => 'url',
        'protocol'              => 'protocol',
        'host'                  => 'host',
        'version'               => 'version',
        'path'                  => 'path',
        'username'              => 'user',
        'password'              => 'password',
        'consumer_key'          => 'auth.consumerKey',
        'consumer_secret'       => 'auth.consumerSecret',
        'signature_method'      => 'auth.signatureMethod',
        'callback_url'          => 'auth.callbackUrl',
        'site_url'              => 'auth.siteUrl',
        'request_token_url'     => 'auth.requestTokenUrl',
        'access_token_url'      => 'auth.accessTokenUrl',
        'authorize_url'         => 'auth.authorizeUrl',
        'user_authorization_url' => 'auth.userAuthorizationUrl',
        'request_method'        => 'auth.requestMethod',
        'rsa_public_key'        => 'auth.rsaPublicKey',
        'rsa_private_key'       => 'auth.rsaPrivateKey',
        'request_scheme'        => 'auth.requestScheme',
        'timeout'               => 'timeout',
        'ssl_verifyhost'        => 'ssl_verifyhost',
        'ssl_verifypeer'        => 'ssl_verifypeer',
    );
    
    public static $classnames = array(
        'GROUPER'               => 'EngineBlock_Group_Provider_Grouper',
        'OPENSOCIAL_BASIC'      => 'EngineBlock_Group_Provider_OpenSocial_HttpBasic',
        'OPENSOCIAL_OAUTH'      => 'EngineBlock_Group_Provider_OpenSocial_Oauth_ThreeLegged',
    );

/********************/
/* BASIC PROPERTIES */
/********************/
    
    public $id;
    public $identifier;
    public $name;
    public $classname;
    public $logoUrl;
    
/************************/
/* AUXILIARY PROPERTIES */
/************************/

    public $fullClassname;
    public $authentication;
    
/**************************************/
/* AUTHENTICATION SPECIFIC PROPERTIES */
/**************************************/
    
    public $url;
    public $username;
    public $password;
    public $consumer_key;
    public $consumer_secret;
    public $signature_method;
    public $version;
    public $callback_url;
    public $site_url;
    public $request_token_url;
    public $access_token_url;
    public $authorize_url;
    public $user_authorization_url;
    public $request_method;
    public $rsa_public_key;
    public $rsa_private_key;
    public $request_scheme;
    public $timeout;
    
/*****************/
/* PRECONDITIONS */
/*****************/
    
    public $user_id_match;
    public $user_id_match_search;
    
/**************/
/* DECORATORS */
/**************/
    
    public $modify_group_id;
    public $modify_group_id_search;
    public $modify_group_id_replace;
    public $modify_user_id;
    public $modify_user_id_search;
    public $modify_user_id_replace;
    
/***********/
/* FILTERS */
/***********/
    
    public $modify_group;
    public $modify_user;
    public $modify_group_rule;
    public $modify_user_rule;
    
    /**
     *
     * @param string $classname
     * @return string 
     */
    public static function getClassnameDisplayValue($classname) {
        return array_search($classname, self::$classnames);
    }
    
    /**
     *
     * @param string $displayValue
     * @return string 
     */
    public static function getClassname($displayValue) {
        return self::$classnames[$displayValue];
    }
    
    public static function getOptionName($column) {
        return self::$allowedOptions[$column];
    }
    
    public static function getColumnName($option) {
        return array_search($option, self::$allowedOptions);
    }
    
}
