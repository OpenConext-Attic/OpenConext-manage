<?php

class ExportController extends Zend_Controller_Action
{
    public function init ()
    {
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

        $Result = $janusEntity->getAvailableSps();

        $this->view->ResultSet = $Result;
    }
}
