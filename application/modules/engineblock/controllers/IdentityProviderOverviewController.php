<?php

class Engineblock_IdentityProviderOverviewController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->identity = $this->_helper->Authenticate();

        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                             ->addActionContext('show-by-type', 'json')
                             ->initContext();
    }

    public function showByTypeAction()
    {
        if ($this->getRequest()->getParam('download', false)) {
            $this->getResponse()->setHeader('Content-disposition', 'attachment; filename=json.txt');
        }

        $inputFilter = $this->_helper->FilterLoader();
        $gadgetList = new Model_JanusEntity();

        $result = $gadgetList->getAvailableIdps(
            $inputFilter->sort,
            $inputFilter->dir,
            $inputFilter->results,
            $inputFilter->startIndex
        );
        $resultTotal = $gadgetList->getAvailableIdps(
            null,
            null,
            null,
            0,
            true
        );

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->exportConfig       = $this->_helper->exportSetup($inputFilter);
        
        $this->view->ResultSet          = $result;
        $this->view->totalRecords       = $resultTotal;
        $this->view->recordsReturned    = count($result);
        $this->view->startIndex         = 0;
    }
}



