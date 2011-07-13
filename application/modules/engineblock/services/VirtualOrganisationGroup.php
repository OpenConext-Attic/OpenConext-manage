<?php
/**
 *
 */

class EngineBlock_Service_VirtualOrganisationGroup
{
    
    public function listSearch(Surfnet_Search_Parameters $params, $vo_id='')
    {
        return $this->_searchWhere($params, "vo_id = '$vo_id'");
    }

    protected function _searchWhere(Surfnet_Search_Parameters $params, $where='')
    {
        // select VO record(s)
        $dao = new EngineBlock_Model_DbTable_VirtualOrganisationGroup();

        $query = $dao->select()->from($dao);
        if (strlen(trim($where)) > 0) {   
            $query->where($where);
        }
        if ($params->getLimit()) {
            $query->limit($params->getLimit(), $params->getOffset());
        }
        if ($params->getSortByField()) {
            $query->order('virtual_organisation_group.' . $params->getSortByField() . ' ' . $params->getSortDirection());
        }
        $groupRecords = $dao->fetchAll($query);
        $totalCount = $dao->fetchRow(
            $query->reset(Zend_Db_Select::LIMIT_COUNT)
                    ->reset(Zend_Db_Select::LIMIT_OFFSET)
                    ->columns(array('count'=>'COUNT(*)'))
        )->offsetGet('count');

        return new Surfnet_Search_Results($params, $groupRecords, $totalCount);
    }

    public function fetchById($vo_id, $group_id)
    {
        $mapper = new EngineBlock_Model_Mapper_VirtualOrganisationGroup(new EngineBlock_Model_DbTable_VirtualOrganisationGroup());
        return $mapper->fetchById($vo_id, $group_id);
    }

    public function save($data, $overwrite = false)
    {
        if (isset($data['vo_id']) && isset($data['group_id']) && $data['group_id'] == $data['org_group_id'] && !$overwrite) {
            $vo_id    = htmlentities($data['vo_id']);
            $group_id = htmlentities($data['group_id']);
            $groupService = new EngineBlock_Service_VirtualOrganisationGroup();
            $group = $groupService->fetchById($vo_id, $group_id);
        }
        else {
            $group = new EngineBlock_Model_VirtualOrganisationGroup();
        }
        $group->populate($data);

        $form = new EngineBlock_Form_VirtualOrganisationGroup();
        if (!$form->isValid($group->toArray())) {
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
            $group->errors = $modelErrors;
            return $group;
        }

        $mapper = new EngineBlock_Model_Mapper_VirtualOrganisationGroup(new EngineBlock_Model_DbTable_VirtualOrganisationGroup());
        $isNewRecord = (isset($data['org_group_id']) && $group->group_id != $data['org_group_id']);
        $mapper->save($group, $isNewRecord);
        
        // if the PK changes, it is saved as a new record, so the original record should be deleted
        if ($isNewRecord) {
            $this->delete($data['vo_id'], $data['org_group_id']);
        }
        
        return $group;
    }

    public function delete($vo_id, $group_id)
    {
        $dao = new EngineBlock_Model_DbTable_VirtualOrganisationGroup();
        if (strlen(trim($vo_id)) > 0 && strlen(trim($group_id)) > 0) {
            return $dao->delete("vo_id='$vo_id' AND group_id='$group_id'");        
        } else {
            return false;
        }
    }
    
    public function updateVOId($oldId, $newId)
    {
        if (strlen($oldId) > 0 && strlen($newId) > 0 && $oldId != $newId) {
            $dao = new EngineBlock_Model_DbTable_VirtualOrganisationGroup();
            return $dao->update(array('vo_id' => $newId), "vo_id = '$oldId'");
        } else {
            // ignore
            return true;
        }
        
    }
    
}