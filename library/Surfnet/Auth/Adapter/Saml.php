<?php

class Surfnet_Auth_Adapter_Saml implements Zend_Auth_Adapter_Interface
{
    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        require_once(LIBRARY_PATH . 'simplesamlphp-1.6.3/lib/_autoload.php');
        $as = new SimpleSAML_Auth_Simple('default-sp');
        $as->requireAuth();
        $attributes = $as->getAttributes();
    }
}
