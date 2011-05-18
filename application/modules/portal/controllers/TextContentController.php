<?php


class Portal_TextContentController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->identity = $this->_helper->Authenticate();

        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                                ->addActionContext('list', 'json')
                                ->initContext();
    }

    public function listAction()
    {
        $inputFilter = $this->_helper->FilterLoader();
        $params = Surfnet_Search_Parameters::create()
                ->setLimit($inputFilter->results)
                ->setOffset($inputFilter->startIndex)
                ->setSortByField($inputFilter->sort)
                ->setSortDirection($inputFilter->dir);

        $service = new Portal_Service_TextContent();
        $results = $service->search($params);

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $results->getResults();
        $this->view->recordsReturned    = $results->getResultCount();
        $this->view->totalRecords       = $results->getTotalCount();
        $this->view->addUrl             = $this->view->url(array('action'=>'add'));
        $this->view->editUrl            = $this->view->url(array('action'=>'edit'));
    }

    public function addAction()
    {
        $this->view->TextContent = new Portal_Model_TextContent();
        $this->render('edit');
    }

    public function editAction()
    {
        $service = new Portal_Service_TextContent();
        $this->view->TextContent = $service->findById((int)$this->_getParam('id'));
    }

    public function saveAction()
    {
        $service = new Portal_Service_TextContent();
        $textContent = $service->save($this->_getAllParams(), true);

        if (empty($textContent->errors)) {
            $this->_redirect($this->view->url(array('action'=>'list')));
        }
        else {
            $this->view->TextContent = $textContent;
            $this->render('edit');
        }
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $service = new Portal_Service_TextContent();
        return $service->delete((int)$this->_getParam('id'));
    }
}
