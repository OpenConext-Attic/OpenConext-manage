<?php

class EngineBlock_VirtualOrganisationController extends Zend_Controller_Action
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

        $service = new EngineBlock_Service_VirtualOrganisation();
        $results = $service->listSearch($params);

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
        $virtualOrganisation = new EngineBlock_Model_VirtualOrganisation();
        $this->view->virtualOrganisation = $virtualOrganisation;
        $this->view->saveUrl             = $this->view->url(array('action'=>'save'));
        $this->view->listUrl             = $this->view->url(array('action'=>'list'));
        $this->render('edit');
    }

    public function editAction()
    {
        $this->view->vo_id = htmlentities($this->_getParam('vo_id'));
        $service = new EngineBlock_Service_VirtualOrganisation();
        $this->view->virtualOrganisation = $service->fetchById($this->view->vo_id);
        // rebuild clean urls to prevent "/vo_id/..." in the urls when returning from group editing:
        $this->view->saveUrl             = $this->view->url(array('module'=>'engineblock', 'controller' => 'virtual-organisation', 'action'=>'save'), null, true);
        $this->view->listUrl             = $this->view->url(array('module'=>'engineblock', 'controller' => 'virtual-organisation', 'action'=>'list'), null, true);
        $this->view->gridData = array();

        // groups grid
        $inputFilter = $this->_helper->FilterLoader('groups');
        $params = Surfnet_Search_Parameters::create()
                ->setLimit($inputFilter->results)
                ->setOffset($inputFilter->startIndex)
                ->setSortByField($inputFilter->sort)
                ->setSortDirection($inputFilter->dir);
        $service = new EngineBlock_Service_VirtualOrganisationGroup();
        $groupRecords = $service->listSearch($params, $this->view->vo_id);
        $this->view->gridData['groups'] = array(
            'gridConfig'        => $this->_helper->gridSetup($inputFilter, 'groups'),
        );
        
        // idps grid
        $inputFilter = $this->_helper->FilterLoader('idps');
        $params = Surfnet_Search_Parameters::create()
                ->setLimit($inputFilter->results)
                ->setOffset($inputFilter->startIndex)
                ->setSortByField($inputFilter->sort)
                ->setSortDirection($inputFilter->dir);
        $service = new EngineBlock_Service_VirtualOrganisationIdp();
        $idpRecords = $service->listSearch($params, $this->view->vo_id);
        $this->view->gridData['idps'] = array(
            'gridConfig'        => $this->_helper->gridSetup($inputFilter, 'idps'),
        );

        // json context dependent variables
        if ($this->_getParam('format') == 'json') {
            $this->view->gridid = $this->_getParam('gridid');
            switch ($this->view->gridid) {
                case 'groups' :
                    $this->view->ResultSet          = $this->view->virtualOrganisation->groups; //$results->getResults();
                    $this->view->startIndex         = $groupRecords->getParameters()->getOffset();
                    $this->view->recordsReturned    = $groupRecords->getResultCount();
                    $this->view->totalRecords       = $groupRecords->getTotalCount();
                    $this->view->addUrl             = $this->view->url(array('action'=>'groupadd'));
                    $this->view->editUrl            = $this->view->url(array('action'=>'groupedit'));
                    break;
                case 'idps' :
                    $this->view->ResultSet          = $this->view->virtualOrganisation->idps; //$results->getResults();
                    $this->view->startIndex         = $idpRecords->getParameters()->getOffset();
                    $this->view->recordsReturned    = $idpRecords->getResultCount();
                    $this->view->totalRecords       = $idpRecords->getTotalCount();
                    $this->view->addUrl             = $this->view->url(array('action'=>'idpadd'));
                    $this->view->editUrl            = $this->view->url(array('action'=>'idpedit'));
                    break;
                default :
                    break;
            }
        } else {
            $this->view->ResultSet = array();
        }
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $service = new EngineBlock_Service_VirtualOrganisation();
        return $service->delete($this->_getParam('vo_id'));
    }

    public function saveAction()
    {
        $this->view->listUrl = $this->view->url(array('action'=>'list'));

        $service = new EngineBlock_Service_VirtualOrganisation();
        $virtualOrganisation = $service->save($this->_getAllParams(), true);
        
        if (empty($virtualOrganisation->errors)) {
            $this->_redirect($this->view->url(array('module'=>'engineblock', 'controller' => 'virtual-organisation', 'action'=>'list'), null, true));
        }
        else {
            $virtualOrganisation->vo_id = $this->_getParam('org_vo_id');
            $this->view->virtualOrganisation = $virtualOrganisation;
            $this->render('edit');
        }
    }

    public function groupaddAction()
    {
        $this->view->vo_id = htmlentities($this->_getParam('vo_id'));
        if (strlen($this->view->vo_id) > 0) {
            $virtualOrganisationGroup = new EngineBlock_Model_VirtualOrganisationGroup();
            $virtualOrganisationGroup->populate(array('vo_id'=>$this->view->vo_id));
            $this->view->virtualOrganisationGroup = $virtualOrganisationGroup;
            $this->view->saveUrl             = $this->view->url(array('action'=>'groupsave'));
            $this->view->listUrl             = $this->view->url(array('action'=>'edit'));
            $this->render('groupedit');        
        } else {
            $this->_forward('edit');
       }
    }
    
    public function groupeditAction()
    {
        $this->view->vo_id = htmlentities($this->_getParam('vo_id'));
        $this->view->group_id = htmlentities($this->_getParam('group_id'));
        $service = new EngineBlock_Service_VirtualOrganisationGroup();
        $this->view->virtualOrganisationGroup = $service->fetchById($this->view->vo_id, $this->view->group_id);
        $this->view->saveUrl                  = $this->view->url(array('action'=>'groupsave'));
        $this->view->listUrl                  = $this->view->url(array('action'=>'edit'));        
    }

    public function groupsaveAction()
    {
        $this->view->listUrl = $this->view->url(array('action'=>'edit'));

        $service = new EngineBlock_Service_VirtualOrganisationGroup();
        $virtualOrganisationGroup = $service->save($this->_getAllParams(), true);
        
        if (empty($virtualOrganisationGroup->errors)) {
            $this->_redirect($this->view->url(array('action'=>'edit','vo_id'=>$this->_getParam('vo_id'))));
        }
        else {
            $this->view->virtualOrganisationGroup = $virtualOrganisationGroup;
            $this->render('groupedit');
        }        
    }
    
    public function groupdeleteAction() 
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $service = new EngineBlock_Service_VirtualOrganisationGroup();
        return $service->delete(htmlentities($this->_getParam('vo_id')), htmlentities($this->_getParam('group_id')));        
    }
    
    public function idpaddAction()
    {
        $this->view->vo_id = htmlentities($this->_getParam('vo_id'));
        if (strlen($this->view->vo_id) > 0) {
            $virtualOrganisationIdp = new EngineBlock_Model_VirtualOrganisationIdp();
            $virtualOrganisationIdp->populate(array('vo_id'=>$this->view->vo_id));
            $this->view->virtualOrganisationIdp = $virtualOrganisationIdp;
            $this->view->saveUrl             = $this->view->url(array('action'=>'idpsave'));
            $this->view->listUrl             = $this->view->url(array('action'=>'edit'));
            $this->render('idpedit');        
        } else {
            $this->_forward('edit');
       }
    }
    
    public function idpeditAction()
    {
        $this->view->vo_id = htmlentities($this->_getParam('vo_id'));
        $this->view->idp_id = htmlentities($this->_getParam('idp_id'));
        $service = new EngineBlock_Service_VirtualOrganisationIdp();
        $this->view->virtualOrganisationIdp = $service->fetchById($this->view->vo_id, $this->view->idp_id);
        $this->view->saveUrl                  = $this->view->url(array('action'=>'idpsave'));
        $this->view->listUrl                  = $this->view->url(array('action'=>'edit'));        
    }

    public function idpsaveAction()
    {
        $this->view->listUrl = $this->view->url(array('action'=>'edit'));

        $service = new EngineBlock_Service_VirtualOrganisationIdp();
        $virtualOrganisationIdp = $service->save($this->_getAllParams(), true);
        
        if (empty($virtualOrganisationIdp->errors)) {
            $this->_redirect($this->view->url(array('action'=>'edit','vo_id'=>$this->_getParam('vo_id'))));
        }
        else {
            $this->view->virtualOrganisationIdp = $virtualOrganisationIdp;
            $this->render('idpedit');
        }        
    }
    
    public function idpdeleteAction() 
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $service = new EngineBlock_Service_VirtualOrganisationIdp();
        return $service->delete(htmlentities($this->_getParam('vo_id')), htmlentities($this->_getParam('idp_id')));        
    }
    
}
