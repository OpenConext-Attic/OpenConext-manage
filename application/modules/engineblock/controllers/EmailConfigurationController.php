<?php


class EngineBlock_EmailConfigurationController extends Zend_Controller_Action
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
               // ->setSortByField($inputFilter->sort)
                ->setSortDirection($inputFilter->dir);

        $searchParams = $this->_getParam('search');
        if (!empty($searchParams)) {
            foreach ($searchParams as $searchKey=>$searchParam) {
                $params->addSearchParam($searchKey, $searchParam);
            }
        }

        $service = new EngineBlock_Service_EmailConfiguration();
        $results = $service->search($params);

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $results->getResults();
        $this->view->startIndex         = $results->getParameters()->getOffset();
        $this->view->recordsReturned    = $results->getResultCount();
        $this->view->totalRecords       = $results->getTotalCount();
        $this->view->addUrl             = $this->view->url(array('action'=>'add'));
        $this->view->editUrl            = $this->view->url(array('action'=>'edit'));
    }

    public function addAction()
    {
        $this->view->EmailConfiguration = new EngineBlock_Model_EmailConfiguration();
        $this->render('edit');
    }

    public function editAction()
    {
        $service = new EngineBlock_Service_EmailConfiguration();
        $this->view->EmailConfiguration = $service->findById((int)$this->_getParam('id'));
    }

    public function saveAction()
    {
        $service = new EngineBlock_Service_EmailConfiguration();
        $emailConfiguration = $service->save($this->_getAllParams(), true);

        if (empty($emailConfiguration->errors)) {
            $this->_redirect($this->view->url(array('action'=>'list')));
        }
        else {
            $this->view->EmailConfiguration = $emailConfiguration;
            $this->render('edit');
        }
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $service = new EngineBlock_Service_EmailConfiguration();
        return $service->delete((int)$this->_getParam('id'));
    }
}
