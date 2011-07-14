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

class Default_ExportController extends Zend_Controller_Action
{
    public function init ()
    {
        /* Running from the CLI, no authentication needed/available. */
        if (php_sapi_name() != 'cli') {
            //Get the identity
            $this->view->identity = $this->_helper->Authenticate('portal');
        }

        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                                     ->addActionContext('availableidps', 'json')
                                     ->addActionContext('availablesps', 'json')
                                     ->addActionContext('idpandspcount', 'json')
                                     ->addActionContext('logins', 'json')
                                     ->addActionContext('idplogins', 'json')
                                     ->addActionContext('splogins', 'json')
                                     ->initContext();
    }

    /**
     * Show Available ID Providers
     *
     */
    public function availableidpsAction()
    {
        $janusEntity = new Model_JanusEntity();

        $result = $janusEntity->getAvailableIdps();

        $this->view->ResultSet = $result;
    }

    /**
     * Export to VERS
     *
     */
    public function versAction()
    {
        $this->_helper->layout->disableLayout();
        $this->view->result = "Export done";
        $this->_helper->viewRenderer->setNoRender(true);
        
        $kpi = new Default_Service_Kpi();

        
        $data = array();
        $timestamp = strtotime("LAST MONTH");
        $period = date('Y-m', $timestamp);
        
        $connectedProviderTypes          = $kpi->getConnectedProviderTypes($timestamp);
        $data['Totaal aantal logins']    = $kpi->getLogins($timestamp);
        $data['Aantal aangesloten IdPs'] = $connectedProviderTypes['idp'];
        $data['Aantal aangesloten SPs']  = $connectedProviderTypes['sp'];
        $data['Beschikbare tabs']        = $kpi->getTeamTabs($timestamp);

        $vers = new Default_Service_Vers();
        $result = $vers->insertReport($data, $period);
        if ($result!==true) {
            throw new Exception($vers->getResultMessage(), -1);
        }
    }

}
