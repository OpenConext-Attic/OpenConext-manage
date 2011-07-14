<?php
/**
 *
 */

class EngineBlock_Service_VirtualOrganisationIdp
{
    
    public function listSearch(Surfnet_Search_Parameters $params, $vo_id='')
    {
        return $this->_searchWhere($params, "vo_id = '$vo_id'");
    }

    protected function _searchWhere(Surfnet_Search_Parameters $params, $where='')
    {
        // select VO record(s)
        $dao = new EngineBlock_Model_DbTable_VirtualOrganisationIdp();

        $query = $dao->select()->from($dao);
        if (strlen(trim($where)) > 0) {   
            $query->where($where);
        }
        if ($params->getLimit()) {
            $query->limit($params->getLimit(), $params->getOffset());
        }
        if ($params->getSortByField()) {
            $query->order('virtual_organisation_idp.' . $params->getSortByField() . ' ' . $params->getSortDirection());
        }
        $idpRecords = $dao->fetchAll($query);
        $totalCount = $dao->fetchRow(
            $query->reset(Zend_Db_Select::LIMIT_COUNT)
                    ->reset(Zend_Db_Select::LIMIT_OFFSET)
                    ->columns(array('count'=>'COUNT(*)'))
        )->offsetGet('count');

        return new Surfnet_Search_Results($params, $idpRecords, $totalCount);
    }

    public function fetchById($vo_id, $idp_id)
    {
        $mapper = new EngineBlock_Model_Mapper_VirtualOrganisationIdp(new EngineBlock_Model_DbTable_VirtualOrganisationIdp());
        return $mapper->fetchById($vo_id, $idp_id);
    }

    public function save($data, $overwrite = false)
    {
        if (isset($data['vo_id']) && isset($data['idp_id']) && $data['idp_id'] == $data['org_idp_id'] && !$overwrite) {
            $vo_id    = htmlentities($data['vo_id']);
            $idp_id = htmlentities($data['idp_id']);
            $idpService = new EngineBlock_Service_VirtualOrganisationIdp();
            $idp = $idpService->fetchById($vo_id, $idp_id);
        }
        else {
            $idp = new EngineBlock_Model_VirtualOrganisationIdp();
        }
        $idp->populate($data);

        $form = new EngineBlock_Form_VirtualOrganisationIdp();
        if (!$form->isValid($idp->toArray())) {
            $formErrors = $form->getErrors();
            $modelErrors = array();
            foreach ($formErrors as $fieldName => $fieldErrors) {
                foreach ($fieldErrors as $fieldError) {
                    switch ($fieldError) {
                        case 'isEmpty':
                            $error = 'Field is obligatory, but no input given';
                            break;
                        default:
                            $error = $fieldError;
                    }

                    if (!isset($modelErrors[$fieldName])) {
                        $modelErrors[$fieldName] = array();
                    }
                    $modelErrors[$fieldName][] = $error;
                }
            }
            $idp->errors = $modelErrors;
            return $idp;
        }

        $mapper = new EngineBlock_Model_Mapper_VirtualOrganisationIdp(new EngineBlock_Model_DbTable_VirtualOrganisationIdp());
        $isNewRecord = (isset($data['org_idp_id']) && $idp->idp_id != $data['org_idp_id']);
        $mapper->save($idp, $isNewRecord);
        
        // if the PK changes, it is saved as a new record, so the original record should be deleted
        if ($isNewRecord) {
            $this->delete($data['vo_id'], $data['org_idp_id']);
        }
        
        return $idp;
    }

    public function delete($vo_id, $idp_id)
    {
        $dao = new EngineBlock_Model_DbTable_VirtualOrganisationIdp();
        if (strlen(trim($vo_id)) > 0 && strlen(trim($idp_id)) > 0) {
            return $dao->delete("vo_id='$vo_id' AND idp_id='$idp_id'");        
        } else {
            return false;
        }
    }
    
    public function updateVOId($oldId, $newId)
    {
        if (strlen($oldId) > 0 && strlen($newId) > 0 && $oldId != $newId) {
            $dao = new EngineBlock_Model_DbTable_VirtualOrganisationIdp();
            return $dao->update(array('vo_id' => $newId), "vo_id = '$oldId'");
        } else {
            // ignore
            return true;
        }
        
    }
    
}