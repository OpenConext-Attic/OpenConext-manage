<?php

class Portal_GadgetOverviewController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->identity = $this->_helper->Authenticate();
        
        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                             ->addActionContext('show-by-usage', 'json')
                             ->initContext();
    }

    public function showByUsageAction()
    {
        if ($this->getRequest()->getParam('download', false)) {
            $this->getResponse()->setHeader('Content-disposition', 'attachment; filename=json.txt');
        }

        $gadgetList = new Model_GadgetList();
        $inputFilter = $this->_helper->FilterLoader();

        $results = $gadgetList->getUsage(
            $inputFilter->sort,
            $inputFilter->dir,
            $inputFilter->results,
            $inputFilter->startIndex
        );
        $resultTotal = $gadgetList->getUsage(
            null,
            null,
            null,
            0,
            true
        );

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $results;
        $this->view->recordsReturned    = count($results);
        $this->view->totalRecords       = $resultTotal;
    }
}