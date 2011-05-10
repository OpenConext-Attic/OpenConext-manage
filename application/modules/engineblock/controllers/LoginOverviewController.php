<?php

class Engineblock_LoginOverviewController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->identity = $this->_helper->Authenticate();

        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                             ->addActionContext('show-by-type', 'json')
                             ->addActionContext('show-by-idp', 'json')
                             ->addActionContext('show-by-sp', 'json')
                             ->initContext();
    }

    public function showByTypeAction()
    {
        if ($this->getRequest()->getParam('download', false)) {
            $this->getResponse()->setHeader('Content-disposition', 'attachment; filename=json.txt');
        }

        $inputFilter = $this->_helper->FilterLoader();
        $gadgetList = new Model_LogLogin();

        $result = $gadgetList->getCount(
            $inputFilter->sort,
            $inputFilter->dir,
            $inputFilter->results,
            $inputFilter->startIndex
        );
        $resultTotal = $gadgetList->getCount(
            null,
            null,
            null,
            0,
            true
        );

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $result;
        $this->view->totalRecords       = $resultTotal;
        $this->view->recordsReturned    = count($result);
        $this->view->startIndex         = 0;
    }

    public function showByIdpAction()
    {
        if ($this->getRequest()->getParam('download', false)) {
            $this->getResponse()->setHeader('Content-disposition', 'attachment; filename=json.txt');
        }

        $inputFilter = $this->_helper->FilterLoader();
        $gadgetList = new Model_LogLogin();

        $result = $gadgetList->getByIdp(
            $inputFilter->sort,
            $inputFilter->dir,
            $inputFilter->results,
            $inputFilter->startIndex
        );
        $resultTotal = $gadgetList->getByIdp(
            null,
            null,
            null,
            0,
            true
        );

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $result;
        $this->view->totalRecords       = $resultTotal;
        $this->view->recordsReturned    = count($result);
        $this->view->startIndex         = 0;
    }

    public function showBySpAction()
    {
        if ($this->getRequest()->getParam('download', false)) {
            $this->getResponse()->setHeader('Content-disposition', 'attachment; filename=json.txt');
        }

        $inputFilter = $this->_helper->FilterLoader();
        $gadgetList = new Model_LogLogin();

        $result = $gadgetList->getBySP(
            $inputFilter->sort,
            $inputFilter->dir,
            $inputFilter->results,
            $inputFilter->startIndex
        );
        $resultTotal = $gadgetList->getBySP(
            null,
            null,
            null,
            0,
            true
        );

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $result;
        $this->view->totalRecords       = $resultTotal;
        $this->view->recordsReturned    = count($result);
        $this->view->startIndex         = 0;
    }
}