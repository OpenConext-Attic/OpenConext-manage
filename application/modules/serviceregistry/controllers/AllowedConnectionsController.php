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

class ServiceRegistry_AllowedConnectionsController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->identity = $this->_helper->Authenticate();
    }

    public function showForIdpAction()
    {
        $entityId = $this->getRequest()->getParam('eid', false);

        if (!$entityId) {
            throw new Exception('No entity ID provided!');
        }

        $service = new ServiceRegistry_Service_JanusEntity();
        $this->view->ResultSet = $service->getAllowedConnections($entityId);
        $this->view->entity = $service->fetchByEntityId($entityId);
    }

    public function showForSpAction()
    {
        $entityId = $this->getRequest()->getParam('eid', false);

        if (!$entityId) {
            throw new Exception('No entity ID provided!');
        }

        $service = new ServiceRegistry_Service_JanusEntity();
        $this->view->ResultSet = $service->getAllowedConnections($entityId);
        $this->view->entity = $service->fetchByEntityId($entityId);
    }
}