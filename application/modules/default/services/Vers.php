<?php
/**
 * SURFconext Manage
 *
 * LICENSE
 *
 * Copyright 2011 SURFnet bv, The Netherlands
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 *
 * @category  SURFconext Manage
 * @package
 * @copyright Copyright © 2010-2011 SURFnet bv, The Netherlands (http://www.surfnet.nl)
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */
/**
 * SURFconext External reporting Service
 */
class Default_Service_Vers
{
    /**
     *
     * @var ExternalReporting_Insert_Client
     */
    protected $_client;

    /**
     * List of insert results
     *
     * @var Array
     */
    protected $_results;

    public function __construct() {
        $this->setClient();
    }

    public function setClient($client=null)
    {
        if (is_null($this->_client)) {
            // Create SOAP client
            $config = Zend_Registry::get('config')->toArray();
            $env = $config->vers->env;
            $versConfig = $config->vers->full->{$env};
            /*
             * The reporting client is an external, so the
             * Location/ naming convention is not exactly the
             * same. For this reason we need to include the client
             * explicitly.
             */
            require_once('Surfnet/ExternalReporting/Insert/Client.php');
            $this->_client = new ExternalReporting_Insert_Client(
                $versConfig->wsdl_url,
                $versConfig->user_name,
                $versConfig->password
            );
        } else {
            $this->_client = $client;
        }
    }

    /**
     * Get the results.
     *
     * @return Array
     */
    public function getResults() {
        return $this->_results;
    }

    /**
     * Format the results into a human-readable message.
     * 
     * @return String
     */
    public function getResultMessage() {
        $message = '';
        foreach ($this->_results as $report => $return) {
            $message .= "{$report} | {$return['ReturnText']}\n";
        }
        return $message;
    }
    
    /**
     * Clear the result list.
     * 
     */
    protected function _clearResults() {
        $this->_results = array();
    }

    /**
     * Add a result to the list
     *
     * @param $key    Index
     * @param $result
     */
    protected function _addResult($key, $result) {
        $this->_results[$key] = $result;
    }

    /**
     * Send a list of data to VERS.
     * The data is a hash with as key the thing being measured.
     * e.g. 'Number of users'
     *
     * @param Array  $data
     * @param String $period
     *
     * @return boolean Succes or not
     */
    public function insertReport($data, $period)
    {
        /**
         * Build 
         */
        $success = true;
        $this->_clearResults();
        $parametersArray = array();
        foreach ($data as $key => $value) {
            // add total
            $parametersArray[$key] = array(
                    "Value"             => $value,
                    "Type"              => $key,
                    "Instance"          => "",
                    "DepartmentList"    => "AS",
                    "Period"            => $period,
                    "IsHidden"          => false,
                    "IsKPI"             => false
            );
        }

        /*
         * Send reports to VERS
         */
        $insertResults = array();
        $i = 0;
        foreach ($parametersArray as $key => $parameters)
        {
            $result = $this->_client->insertReport($parameters);
            if ($result['ReturnCode'] == -1) {
                $result = $this->_client->updateReport($parameters);
            } else if ($result['ReturnCode'] < 1) {
                $success = false;
            }
            $this->_addResult($key,$result);
        }
        return $success;
    }
}
