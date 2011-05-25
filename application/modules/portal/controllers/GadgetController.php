<?php

class Portal_GadgetController extends Zend_Controller_Action
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

        $searchParams = $this->_getParam('search');
        if (!empty($searchParams)) {
            foreach ($searchParams as $searchKey=>$searchParam) {
                $params->addSearchParam($searchKey, $searchParam);
            }
        }

        $service = new Portal_Service_Gadget();
        $results = $service->search($params);

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $results->getResults();
        $this->view->startIndex         = $results->getParameters()->getOffset();
        $this->view->recordsReturned    = $results->getResultCount();
        $this->view->totalRecords       = $results->getTotalCount();
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $service = new Portal_Service_Gadget();
        return $service->delete((int)$this->_getParam('id'));
    }
}