<?php

require_once 'Zend/Cache.php';
require_once 'Zend/Soap/Client.php';

class SurfDashboard_Client
{
    /**
     * SOAP Client for communication with the VERS SOAP service.
     *
     * @var Zend_Soap_Client
     */
    protected $_client = null;

    /**
     * VERS SOAP WSDL uri.
     *
     * @var String
     */
    protected $_wsdl;

    /**
     * VERS SOAP password.
     *
     * @var String
     */
    protected $_password;

    /**
     * VERS SOAP login.
     *
     * @var String
     */
    protected $_login;

    public function __construct($wsdl, $login, $password)
    {
        $this->_wsdl     = $wsdl;
        $this->_login    = $login;
        $this->_password = $password;
        $this->getClient();
    }

    /**
     * Sets the Soap Client for communication with the VERS SOAP service.
     * @param  Zend_Soap_Client
     * @return void
     */
    public function setClient(Zend_Soap_Client $client)
    {
        $this->_client = $client;
    }

    /**
     * Get the HTTP Client for communication with the VERS SOAP service.
     * @return Zend_Http_Client
     */
    public function getClient()
    {
        if (is_null($this->_client))
        {
            $this->setClient(new Zend_Soap_Client($this->_wsdl));
            $this->_client->setSoapVersion(SOAP_1_1);
        }
        return $this->_client;
    }

}