<?php


class EngineBlock_ServiceProviderGroupAclController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->identity = $this->_helper->Authenticate();

                $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                                        ->addActionContext('save', 'json')
                                        ->initContext();
    }

    public function showSpByGroupproviderAction()
    {
        $service = new EngineBlock_Service_ServiceProviderGroupAcl();

        $groupProviderId = (int)$this->_getParam('id');
        $groupProviderAbr = $this->_getParam('abr');
        $groupProviderName = $this->_getParam('name');
        $spsAclFromDb = $service->findByGroupProviderId($groupProviderId);

        $janus = new ServiceRegistry_Service_JanusEntity();
        /*
         * An array of arrays where we want the key 'entityid' of the value array
         */
        $spsFromJanus = $janus->searchSps(Surfnet_Search_Parameters::create())->getResults();
        $results = array();
        /*
         * now delete those spAcl's that have no corresponding Janus SP entry
         */
        foreach ($spsAclFromDb as $spAcl) {
            if (!$this->_hasExistingJanusSP($spsFromJanus, $spAcl)) {
                $service->delete($spAcl->id);
            } else {
                $results[] = $spAcl;
            }
        }
        /*
         * now create an Acl if there is a Janus entry but no corresponding ServiceProviderGroupAcl
         */
        $mapper = new EngineBlock_Model_Mapper_ServiceProviderGroupAcl(new EngineBlock_Model_DbTable_ServiceProviderGroupAcl());
        foreach ($spsFromJanus as $spJanus) {
            $spEntityId = $this->_getMissingSpAclEntityId($spsAclFromDb, $spJanus);
            if ($spEntityId) {
                $model = new EngineBlock_Model_ServiceProviderGroupAcl();
                $model->allow_groups = false;
                $model->allow_members = false;
                $model->groupProviderId = $groupProviderId;
                $model->spentityid = $spEntityId;
                $model->id = $mapper->save($model);
                $results[] = $model;
            }
        }
        $this->view->serviceProviderAcls = $results;
        $this->view->groupProvider = array(
            'id'   => $groupProviderId,
            'abr'  => $groupProviderAbr,
            'name' => $groupProviderName,
        );
        $this->render('edit');
        
    }

    protected function _hasExistingJanusSP($spsFromJanus, $spAclModel)
    {
        foreach ($spsFromJanus as $sp) {
            if ($sp['entityid'] === $spAclModel->spentityid) {
                return true;
            }
        }
        return false;
    }

    protected function _getMissingSpAclEntityId($spsAclFromDb, $spJanus)
        {
            $entityId = $spJanus['entityid'];
            foreach ($spsAclFromDb as $spAcl) {
                if ($spAcl->spentityid === $entityId) {
                    return null;
                }
            }
            return $entityId;
        }

    public function saveAction()
    {
        $service = new EngineBlock_Service_ServiceProviderGroupAcl();
        $spAcls = $this->_getParam('spacl');
        foreach ($spAcls as $spAcl) {
            $service->save($spAcl, true);
        }
        $this->_forward('list','group-provider','engineblock');
    }


}
