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

        // decorators grid
        $inputFilter = $this->_helper->FilterLoader('decorators');
        $params = Surfnet_Search_Parameters::create()
                ->setLimit($inputFilter->results)
                ->setOffset($inputFilter->startIndex)
                ->setSortByField($inputFilter->sort)
                ->setSortDirection($inputFilter->dir);
        $service = new EngineBlock_Service_GroupProviderDecorator();
        $decoratorRecords = $service->listSearch($params, $this->view->group_provider_id);
        $this->view->gridData['decorators'] = array(
            'gridConfig' => $this->_helper->gridSetup($inputFilter, 'decorators'),
        );

        // preconditions grid
        $inputFilter = $this->_helper->FilterLoader('preconditions');
        $params = Surfnet_Search_Parameters::create()
                ->setLimit($inputFilter->results)
                ->setOffset($inputFilter->startIndex)
                ->setSortByField($inputFilter->sort)
                ->setSortDirection($inputFilter->dir);
        $service = new EngineBlock_Service_GroupProviderPrecondition();
        $preconditionRecords = $service->listSearch($params, $this->view->group_provider_id);
        $this->view->gridData['preconditions'] = array(
            'gridConfig' => $this->_helper->gridSetup($inputFilter, 'preconditions'),
        );

        // group_filters grid
        $inputFilter = $this->_helper->FilterLoader('group_filters');
        $params = Surfnet_Search_Parameters::create()
                ->setLimit($inputFilter->results)
                ->setOffset($inputFilter->startIndex)
                ->setSortByField($inputFilter->sort)
                ->setSortDirection($inputFilter->dir);
        $service = new EngineBlock_Service_GroupProviderGroupFilter();
        $group_filterRecords = $service->listSearch($params, $this->view->group_provider_id);
        $this->view->gridData['group_filters'] = array(
            'gridConfig' => $this->_helper->gridSetup($inputFilter, 'group_filters'),
        );

        // group_member_filters grid
        $inputFilter = $this->_helper->FilterLoader('group_member_filters');
        $params = Surfnet_Search_Parameters::create()
                ->setLimit($inputFilter->results)
                ->setOffset($inputFilter->startIndex)
                ->setSortByField($inputFilter->sort)
                ->setSortDirection($inputFilter->dir);
        $service = new EngineBlock_Service_GroupProviderGroupMemberFilter();
        $group_member_filterRecords = $service->listSearch($params, $this->view->group_provider_id);
        $this->view->gridData['group_member_filters'] = array(
            'gridConfig' => $this->_helper->gridSetup($inputFilter, 'group_member_filters'),
        );

        // json context dependent variables
        if ($this->_getParam('format') == 'json') {
            $this->view->gridid = $this->_getParam('gridid');
            switch ($this->view->gridid) {
                case 'decorators' :
                    $this->view->ResultSet = $this->view->groupProvider->decorators; //$results->getResults();
                    $this->view->startIndex = $decoratorRecords->getParameters()->getOffset();
                    $this->view->recordsReturned = $decoratorRecords->getResultCount();
                    $this->view->totalRecords = $decoratorRecords->getTotalCount();
                    $this->view->addUrl = $this->view->url(array('action' => 'decoratoradd'));
                    $this->view->editUrl = $this->view->url(array('action' => 'decoratoredit'));
                    break;
                case 'preconditions' :
                    $this->view->ResultSet = $this->view->groupProvider->preconditions; //$results->getResults();
                    $this->view->startIndex = $preconditionRecords->getParameters()->getOffset();
                    $this->view->recordsReturned = $preconditionRecords->getResultCount();
                    $this->view->totalRecords = $preconditionRecords->getTotalCount();
                    $this->view->addUrl = $this->view->url(array('action' => 'preconditionadd'));
                    $this->view->editUrl = $this->view->url(array('action' => 'preconditionedit'));
                    break;
                case 'group_filters' :
                    $this->view->ResultSet = $this->view->groupProvider->group_filters; //$results->getResults();
                    $this->view->startIndex = $group_filterRecords->getParameters()->getOffset();
                    $this->view->recordsReturned = $group_filterRecords->getResultCount();
                    $this->view->totalRecords = $group_filterRecords->getTotalCount();
                    $this->view->addUrl = $this->view->url(array('action' => 'groupfilteradd'));
                    $this->view->editUrl = $this->view->url(array('action' => 'groupfilteredit'));
                    break;
                case 'group_member_filters' :
                    $this->view->ResultSet = $this->view->groupProvider->group_member_filters; //$results->getResults();
                    $this->view->startIndex = $group_member_filterRecords->getParameters()->getOffset();
                    $this->view->recordsReturned = $group_member_filterRecords->getResultCount();
                    $this->view->totalRecords = $group_member_filterRecords->getTotalCount();
                    $this->view->addUrl = $this->view->url(array('action' => 'groupmemberfilteradd'));
                    $this->view->editUrl = $this->view->url(array('action' => 'groupmemberfilteredit'));
                    break;
                default :
                    break;
            }
        } else {
            $this->view->ResultSet = array();
        }
    }
    
    public function saveAction()
    {
        $this->view->listUrl = $this->view->url(array('action' => 'list'));

        $service = new EngineBlock_Service_GroupProvider();
        $groupProvider = $service->save($this->_getAllParams(), true);

        if (empty($groupProvider->errors)) {
            $this->_redirect($this->view->url(array('module' => 'engineblock', 'controller' => 'group-provider', 'action' => 'list'), null, true));
        }
        else {
            $groupProvider->gp_id = $this->_getParam('org_gp_id');
            $this->view->GroupProvider = $groupProvider;
            $this->render('edit');
        }
    }

    public function decoratoraddAction()
    {
        $this->view->group_provider_id = htmlentities($this->_getParam('group_provider_id'));
        if (strlen($this->view->group_provider_id) > 0) {
            $groupProviderDecorator = new EngineBlock_Model_GroupProviderDecorator();
            $groupProviderDecorator->populate(array('group_provider_id' => $this->view->group_provider_id));
            $this->view->groupProviderDecorator = $groupProviderDecorator;
            $this->view->saveUrl = $this->view->url(array('action' => 'decoratorsave'));
            $this->view->listUrl = $this->view->url(array('action' => 'edit'));
            $this->view->mode = 'add';
            $this->render('decoratoredit');
        } else {
            $this->_forward('edit');
        }
    }

    public function decoratoreditAction()
    {
        $this->view->group_provider_id = htmlentities($this->_getParam('group_provider_id'));
        $this->view->decorator_id = htmlentities($this->_getParam('decorator_id'));
        $service = new EngineBlock_Service_GroupProviderDecorator();
        $this->view->groupProviderDecorator = $service->fetchById($this->view->group_provider_id, $this->view->decorator_id);
        $this->view->saveUrl = $this->view->url(array('action' => 'decoratorsave'));
        $this->view->listUrl = $this->view->url(array('action' => 'edit', 'group_provider_id' => $this->_getParam('group_provider_id')));
        $this->view->mode = 'edit';
    }

    public function decoratorsaveAction()
    {
        $this->view->listUrl = $this->view->url(array('action' => 'edit'));

        $service = new EngineBlock_Service_GroupProviderDecorator();
        $groupProviderDecorator = $service->save($this->_getAllParams(), true);

        if (empty($groupProviderDecorator->errors)) {
            $this->_redirect($this->view->url(array('action' => 'edit', 'group_provider_id' => $this->_getParam('group_provider_id'))));
        }
        else {
            $this->view->groupProviderDecorator = $groupProviderDecorator;
            $this->render('decoratoredit');
        }
    }

    public function decoratordeleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $service = new EngineBlock_Service_GroupProviderDecorator();
        return $service->delete(htmlentities($this->_getParam('group_provider_id')), htmlentities($this->_getParam('decorator_id')));
    }

    public function preconditionaddAction()
    {
        $this->view->group_provider_id = htmlentities($this->_getParam('group_provider_id'));
        if (strlen($this->view->group_provider_id) > 0) {
            $groupProviderPrecondition = new EngineBlock_Model_GroupProviderPrecondition();
            $groupProviderPrecondition->populate(array('group_provider_id' => $this->view->group_provider_id));
            $this->view->groupProviderPrecondition = $groupProviderPrecondition;
            $this->view->saveUrl = $this->view->url(array('action' => 'preconditionsave'));
            $this->view->listUrl = $this->view->url(array('action' => 'edit'));
            $this->view->mode = 'add';
            $this->render('preconditionedit');
        } else {
            $this->_forward('edit');
        }
    }

    public function preconditioneditAction()
    {
        $this->view->group_provider_id = htmlentities($this->_getParam('group_provider_id'));
        $this->view->precondition_id = htmlentities($this->_getParam('precondition_id'));
        $service = new EngineBlock_Service_GroupProviderPrecondition();
        $this->view->groupProviderPrecondition = $service->fetchById($this->view->group_provider_id, $this->view->precondition_id);
        $this->view->saveUrl = $this->view->url(array('action' => 'preconditionsave'));
        $this->view->listUrl = $this->view->url(array('action' => 'edit', 'group_provider_id' => $this->_getParam('group_provider_id')));
        $this->view->mode = 'edit';
    }

    public function preconditionsaveAction()
    {
        $this->view->listUrl = $this->view->url(array('action' => 'edit'));

        $service = new EngineBlock_Service_GroupProviderPrecondition();
        $groupProviderPrecondition = $service->save($this->_getAllParams(), true);

        if (empty($groupProviderPrecondition->errors)) {
            $this->_redirect($this->view->url(array('action' => 'edit', 'group_provider_id' => $this->_getParam('group_provider_id'))));
        }
        else {
            $this->view->groupProviderPrecondition = $groupProviderPrecondition;
            $this->render('preconditionedit');
        }
    }

    public function preconditiondeleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $service = new EngineBlock_Service_GroupProviderPrecondition();
        return $service->delete(htmlentities($this->_getParam('group_provider_id')), htmlentities($this->_getParam('precondition_id')));
    }

    public function groupfilteraddAction()
    {
        $this->view->group_provider_id = htmlentities($this->_getParam('group_provider_id'));
        if (strlen($this->view->group_provider_id) > 0) {
            $groupProviderGroupFilter = new EngineBlock_Model_GroupProviderGroupFilter();
            $groupProviderGroupFilter->populate(array('group_provider_id' => $this->view->group_provider_id));
            $this->view->groupProviderGroupFilter = $groupProviderGroupFilter;
            $this->view->saveUrl = $this->view->url(array('action' => 'groupfiltersave'));
            $this->view->listUrl = $this->view->url(array('action' => 'edit'));
            $this->view->mode = 'add';
            $this->render('groupfilteredit');
        } else {
            $this->_forward('edit');
        }
    }

    public function groupfiltereditAction()
    {
        $this->view->group_provider_id = htmlentities($this->_getParam('group_provider_id'));
        $this->view->group_filter_id = htmlentities($this->_getParam('group_filter_id'));
        $service = new EngineBlock_Service_GroupProviderGroupFilter();
        $this->view->groupProviderGroupFilter = $service->fetchById($this->view->group_provider_id, $this->view->group_filter_id);
        $this->view->saveUrl = $this->view->url(array('action' => 'groupfiltersave'));
        $this->view->listUrl = $this->view->url(array('action' => 'edit', 'group_provider_id' => $this->_getParam('group_provider_id')));
        $this->view->mode = 'edit';
    }

    public function groupfiltersaveAction()
    {
        $this->view->listUrl = $this->view->url(array('action' => 'edit'));

        $service = new EngineBlock_Service_GroupProviderGroupFilter();
        $groupProviderGroupFilter = $service->save($this->_getAllParams(), true);

        if (empty($groupProviderGroupFilter->errors)) {
            $this->_redirect($this->view->url(array('action' => 'edit', 'group_provider_id' => $this->_getParam('group_provider_id'))));
        }
        else {
            $this->view->groupProviderGroupFilter = $groupProviderGroupFilter;
            $this->render('groupfilteredit');
        }
    }

    public function groupfilterdeleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $service = new EngineBlock_Service_GroupProviderGroupFilter();
        return $service->delete(htmlentities($this->_getParam('group_provider_id')), htmlentities($this->_getParam('group_filter_id')));
    }
    
    public function groupmemberfilteraddAction()
    {
        $this->view->group_provider_id = htmlentities($this->_getParam('group_provider_id'));
        if (strlen($this->view->group_provider_id) > 0) {
            $groupProviderGroupMemberFilter = new EngineBlock_Model_GroupProviderGroupMemberFilter();
            $groupProviderGroupMemberFilter->populate(array('group_provider_id' => $this->view->group_provider_id));
            $this->view->groupProviderGroupMemberFilter = $groupProviderGroupMemberFilter;
            $this->view->saveUrl = $this->view->url(array('action' => 'groupmemberfiltersave'));
            $this->view->listUrl = $this->view->url(array('action' => 'edit'));
            $this->view->mode = 'add';
            $this->render('groupmemberfilteredit');
        } else {
            $this->_forward('edit');
        }
    }

    public function groupmemberfiltereditAction()
    {
        $this->view->group_provider_id = htmlentities($this->_getParam('group_provider_id'));
        $this->view->group_member_filter_id = htmlentities($this->_getParam('group_member_filter_id'));
        $service = new EngineBlock_Service_GroupProviderGroupMemberFilter();
        $this->view->groupProviderGroupMemberFilter = $service->fetchById($this->view->group_provider_id, $this->view->group_member_filter_id);
        $this->view->saveUrl = $this->view->url(array('action' => 'groupmemberfiltersave'));
        $this->view->listUrl = $this->view->url(array('action' => 'edit', 'group_provider_id' => $this->_getParam('group_provider_id')));
        $this->view->mode = 'edit';
    }

    public function groupmemberfiltersaveAction()
    {
        $this->view->listUrl = $this->view->url(array('action' => 'edit'));

        $service = new EngineBlock_Service_GroupProviderGroupMemberFilter();
        $groupProviderGroupMemberFilter = $service->save($this->_getAllParams(), true);

        if (empty($groupProviderGroupMemberFilter->errors)) {
            $this->_redirect($this->view->url(array('action' => 'edit', 'group_provider_id' => $this->_getParam('group_provider_id'))));
        }
        else {
            $this->view->groupProviderGroupMemberFilter = $groupProviderGroupMemberFilter;
            $this->render('groupmemberfilteredit');
        }
    }

    public function groupmemberfilterdeleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $service = new EngineBlock_Service_GroupProviderGroupMemberFilter();
        return $service->delete(htmlentities($this->_getParam('group_provider_id')), htmlentities($this->_getParam('group_member_filter_id')));
    }
    
}
