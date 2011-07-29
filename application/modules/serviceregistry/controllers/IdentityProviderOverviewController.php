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

class ServiceRegistry_IdentityProviderOverviewController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->identity = $this->_helper->Authenticate();

        $this->_helper->ContextSwitch()->addActionContext('show-by-type', 'json')
                                        ->addActionContext('show-by-type', 'json-export')
                                        ->addActionContext('show-by-type', 'csv-export')
                                        ->initContext();
    }

    public function showByTypeAction()
    {
        $inputFilter = $this->_helper->FilterLoader();
        $params = Surfnet_Search_Parameters::create();
        if ($inputFilter->results) {
            $params->setLimit($inputFilter->results);
        }
        if ($inputFilter->startIndex) {
            $params->setOffset($inputFilter->startIndex);
        }
        if ($inputFilter->sort) {
            $params->setSortByField($inputFilter->sort);
        }
        if ($inputFilter->dir) {
            $params->setSortDirection($inputFilter->dir);
        }

        $service = new ServiceRegistry_Service_JanusEntity();
        $results = $service->searchIdps($params);

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $results->getResults();
        $this->view->recordsReturned    = $results->getResultCount();
        $this->view->totalRecords       = $results->getTotalCount();
        
    }
}