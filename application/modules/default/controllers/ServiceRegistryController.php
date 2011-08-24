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
 *
 */ 
class Default_ServiceRegistryController extends Zend_Controller_Action
{
    public function init()
    {
        //Get the identity
        $this->view->identity = $this->_helper->Authenticate('portal');
    }

    public function indexAction()
    {
    }

    public function validateEntityAction()
    {
        $this->getHelper('viewRenderer')->setNoRender();

        if (!$this->_getParam('eid')) {
            throw new Exception('Entity ID required');
        }

        /* @var $config Zend_Config */
        $config = Zend_Registry::get('config')->toArray();
        $serviceRegistryConfig = $config['serviceRegistry'];

        $baseUrl = $serviceRegistryConfig['scheme'] . '://' . $serviceRegistryConfig['host'];
        $url = $baseUrl . $serviceRegistryConfig['url']['validate']['entityCertificate'];
        $url .= urlencode($this->_getParam('eid'));

        $urlResponse = file_get_contents($url);
        $data = json_decode($urlResponse);
        if (!$data) {
            throw new Exception("Unable to decode data from '$url', response: " . htmlentities($urlResponse));
        }

        $warnings = array();
        if (isset($data->Warnings)) {
            $warnings = array_merge($warnings, $data->Warnings);
        }
        $errors = array();
        if (isset($data->Errors)) {
            $errors = array_merge($errors, $data->Errors);
        }

        $url = $baseUrl . $serviceRegistryConfig['url']['validate']['entityEndpoints'];
        $url .= urlencode($this->_getParam('eid'));

        $urlResponse = file_get_contents($url);
        $data = json_decode($urlResponse);
        if (!$data) {
            throw new Exception("Unable to decode data from '$url', response: " . htmlentities($urlResponse));
        }
        if (isset($data->Warnings)) {
            $warnings = array_merge($warnings, $data->Warnings);
        }
        if (isset($data->Errors)) {
            $errors = array_merge($errors, $data->Errors);
        }

        header('Content-Type: application/json');
        echo json_encode(array(
            'Response'=>array(
                'Link' => $baseUrl . $serviceRegistryConfig['url']['validate']['link'],
                'Results'=>array(
                    array(
                        'Warnings'=>$warnings,
                        'Errors'=>$errors
                    )
                )
            )
        ));
        exit;
    }
}