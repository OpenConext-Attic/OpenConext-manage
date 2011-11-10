<?php

require_once 'Zend/Cache.php';
require_once 'Zend/Soap/Client.php';

class SurfDashboard_Insert_Client
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

    /**
     * Insert a report value into VERS
     *
     * @param array $parameters The report value parameters
     * NOTE: The parameters must comply with the WSDL file shown at the bottom of the file
     *
     * @return string insertReportResponse in xml format
     */
    public function insertReport(array $parameters)
    {
        return $this->_client->er_InsertReport($this->_login, $this->_password, $parameters);
    }

    /**
     * Update a report value into VERS
     *
     * @param array $parameters The report value parameters
     * NOTE: The parameters must comply with the WSDL file shown at the bottom of the file
     *
     * @return string updateReportResponse in xml format
     */
    public function updateReport(array $parameters)
    {
        return $this->_client->er_UpdateReport($this->_login, $this->_password, $parameters);
    }
}

/* WSDL file (august 20th, 2010)

<definitions xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="urn:SURFnet-er" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns="http://schemas.xmlsoap.org/wsdl/" targetNamespace="urn:SURFnet-er">
<types>
<xsd:schema targetNamespace="urn:SURFnet-er">
 <xsd:import namespace="http://schemas.xmlsoap.org/soap/encoding/"></xsd:import>
 <xsd:import namespace="http://schemas.xmlsoap.org/wsdl/"></xsd:import>
 <xsd:complextype name="InsertReportInput">
  <xsd:all>
   <xsd:element name="Value" type="xsd:string"></xsd:element>
   <xsd:element name="Unit" type="xsd:string" minOccurs="0"></xsd:element>
   <xsd:element name="NormComp" type="xsd:string" minOccurs="0"></xsd:element>
   <xsd:element name="NormValue" type="xsd:string" minOccurs="0"></xsd:element>
   <xsd:element name="Type" type="xsd:string"></xsd:element>
   <xsd:element name="Instance" type="xsd:string" minOccurs="0"></xsd:element>
   <xsd:element name="DepartmentList" type="xsd:string" minOccurs="0"></xsd:element>
   <xsd:element name="Period" type="xsd:string"></xsd:element>
   <xsd:element name="Organisation" type="xsd:string" minOccurs="0"></xsd:element>
   <xsd:element name="IsKPI" type="xsd:boolean"></xsd:element>
   <xsd:element name="Remark" type="xsd:string" minOccurs="0"></xsd:element>
  </xsd:all>
 </xsd:complextype>
</xsd:schema>
</types>
<message name="er_InsertReportRequest">
  <part name="Username" type="xsd:string"></part>
  <part name="Password" type="xsd:string"></part>
  <part name="Parameters" type="tns:InsertReportInput"></part></message>
<message name="er_InsertReportResponse">
  <part name="ReturnCode" type="xsd:int"></part>
  <part name="ReturnText" type="xsd:string"></part></message>
<porttype name="SURFnet-erPortType">
  <operation name="er_InsertReport">
    <documentation>Add reporting value.</documentation>
    <input message="tns:er_InsertReportRequest"/>
    <output message="tns:er_InsertReportResponse"></output>
  </operation>
</porttype>
<binding name="SURFnet-erBinding" type="tns:SURFnet-erPortType">
  <soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"></soap:binding>
  <operation name="er_InsertReport">
    <soap:operation soapAction="urn:SURFnet-er#er_InsertReport" style="rpc"></soap:operation>
    <input><soap:body use="encoded" namespace="urn:SURFnet-er" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"></soap:body></input>
    <output><soap:body use="encoded" namespace="urn:SURFnet-er" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"></soap:body></output>
  </operation>
</binding>
<service name="SURFnet-er">
  <port name="SURFnet-erPort" binding="tns:SURFnet-erBinding">
    <soap:address location="https://rapportage-test.surfnet.nl:443/soap-insertonly/interface.php"></soap:address>
  </port>
</service>
</definitions>

 */