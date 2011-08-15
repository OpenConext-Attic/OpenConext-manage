<?php

class EngineBlock_GroupProviderController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->identity = $this->_helper->Authenticate();

        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                ->addActionContext('list', 'json')
                ->addActionContext('edit', 'json')
                ->addActionContext('save', 'json')
                ->initContext();
    }

    public function listAction()
    {
        if ($this->getRequest()->getParam('download', false)) {
            $this->getResponse()->setHeader('Content-disposition', 'attachment; filename=json.txt');
        }

        $inputFilter = $this->_helper->FilterLoader();
        $params = Surfnet_Search_Parameters::create()
                ->setLimit($inputFilter->results)
                ->setOffset($inputFilter->startIndex)
                ->setSortByField($inputFilter->sort)
                ->setSortDirection($inputFilter->dir);

        $service = new EngineBlock_Service_GroupProvider();
        $results = $service->listSearch($params);

        $this->view->gridConfig = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet = $results->getResults();
        $this->view->startIndex = $results->getParameters()->getOffset();
        $this->view->recordsReturned = $results->getResultCount();
        $this->view->totalRecords = $results->getTotalCount();
        $this->view->addUrl = $this->view->url(array('action' => 'add'));
        $this->view->editUrl = $this->view->url(array('action' => 'edit'));
    }

    public function addAction()
    {
        $groupProvider = new EngineBlock_Model_GroupProvider();
        $this->view->groupProvider = $groupProvider;
        $this->view->saveUrl = $this->view->url(array('action' => 'save'));
        $this->view->listUrl = $this->view->url(array('action' => 'list'));
        $this->render('edit');
    }

    public function editAction()
    {
        $this->view->group_provider_id = htmlentities($this->_getParam('group_provider_id'));
        $service = new EngineBlock_Service_GroupProvider();
        $this->view->groupProvider = $service->fetchById($this->view->group_provider_id);
        // rebuild clean urls to prevent "/group_provider_id/..." in the urls when returning from editing:
        $this->view->saveUrl = $this->view->url(array('module' => 'engineblock', 'controller' => 'group-provider', 'action' => 'save'), null, true);
        $this->view->listUrl = $this->view->url(array('module' => 'engineblock', 'controller' => 'group-provider', 'action' => 'list'), null, true);
        $this->view->gridData = array();

        switch ($this->view->groupProvider->group_provider_type) {
            case 'GROUPER' :
                // do something
                break;
            case 'OAUTH' :
                // do something
                break;
            default :
                break;
        }

        // preconditions grid
        $inputFilter = $this->_helper->FilterLoader('preconditions');
        $params = Surfnet_Search_Parameters::create()
                ->setLimit($inputFilter->results)
                ->setOffset($inputFilter->startIndex)
                ->setSortByField($inputFilter->sort)
                ->setSortDirection($inputFilter->dir);
        $service = new EngineBlock_Service_GroupProviderPrecondition();
        $idpRecords = $service->listSearch($params, $this->view->group_provider_id);
        $this->view->gridData['preconditions'] = array(
            'gridConfig' => $this->_helper->gridSetup($inputFilter, 'preconditions'),
        );

        // json context dependent variables
        if ($this->_getParam('format') == 'json') {
            $this->view->gridid = $this->_getParam('gridid');
            switch ($this->view->gridid) {
                case 'groups' :
                    $this->view->ResultSet = $this->view->virtualOrganisation->groups; //$results->getResults();
                    $this->view->startIndex = $groupRecords->getParameters()->getOffset();
                    $this->view->recordsReturned = $groupRecords->getResultCount();
                    $this->view->totalRecords = $groupRecords->getTotalCount();
                    $this->view->addUrl = $this->view->url(array('action' => 'groupadd'));
                    $this->view->editUrl = $this->view->url(array('action' => 'groupedit'));
                    break;
                case 'idps' :
                    $this->view->ResultSet = $this->view->virtualOrganisation->idps; //$results->getResults();
                    $this->view->startIndex = $idpRecords->getParameters()->getOffset();
                    $this->view->recordsReturned = $idpRecords->getResultCount();
                    $this->view->totalRecords = $idpRecords->getTotalCount();
                    $this->view->addUrl = $this->view->url(array('action' => 'idpadd'));
                    $this->view->editUrl = $this->view->url(array('action' => 'idpedit'));
                    break;
                default :
                    break;
            }
        } else {
            $this->view->ResultSet = array();
        }
    }
}
