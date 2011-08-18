<?php
/**
 *
 */

class EngineBlock_Service_GroupProviderPrecondition
{
    public function listSearch(Surfnet_Search_Parameters $params, $group_provider_id = '')
    {
        return $this->_searchWhere($params, "group_provider_id = '$group_provider_id'");
    }

    protected function _searchWhere(Surfnet_Search_Parameters $params, $where = '')
    {
        // select Precondition record(s)
        $dao = new EngineBlock_Model_DbTable_GroupProviderPrecondition();

        $query = $dao->select()->from($dao);
        if (strlen(trim($where)) > 0) {
            $query->where($where);
        }
        if ($params->getLimit()) {
            $query->limit($params->getLimit(), $params->getOffset());
        }
        if ($params->getSortByField()) {
            $query->order('group_provider_precondition.' . $params->getSortByField() . ' ' . $params->getSortDirection());
        }
        $preconditionRecords = $dao->fetchAll($query);
        $totalCount = $dao->fetchRow(
            $query->reset(Zend_Db_Select::LIMIT_COUNT)
                    ->reset(Zend_Db_Select::LIMIT_OFFSET)
                    ->columns(array('count' => 'COUNT(*)'))
        )->offsetGet('count');

        return new Surfnet_Search_Results($params, $preconditionRecords, $totalCount);
    }

    public function fetchById($group_provider_id, $precondition_id)
    {
        $mapper = new EngineBlock_Model_Mapper_GroupProviderPrecondition(new EngineBlock_Model_DbTable_GroupProviderPrecondition());
        return $mapper->fetchById($group_provider_id, $precondition_id);
    }

    public function save($data, $overwrite = false)
    {
        if (isset($data['group_provider_id']) && isset($data['precondition_id']) && $data['precondition_id'] == $data['org_precondition_id'] && !$overwrite) {
            $group_provider_id = htmlentities($data['group_provider_id']);
            $precondition_id = htmlentities($data['precondition_id']);
            $preconditionService = new EngineBlock_Service_GroupProviderPrecondition();
            $precondition = $preconditionService->fetchById($group_provider_id, $precondition_id);
        }
        else {
            $precondition = new EngineBlock_Model_GroupProviderPrecondition();
        }
        $precondition->populate($data);

        $form = new EngineBlock_Form_GroupProviderPrecondition();
        if (!$form->isValid($precondition->toArray())) {
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
            $precondition->errors = $modelErrors;
        } else {
            $mapper = new EngineBlock_Model_Mapper_GroupProviderPrecondition(new EngineBlock_Model_DbTable_GroupProviderPrecondition());
            $isNewRecord = (isset($data['org_precondition_id']) && $precondition->precondition_id != $data['org_precondition_id']);
            $mapper->save($precondition, $isNewRecord);
        }
        return $precondition;
    }

    public function delete($group_provider_id, $precondition_id)
    {
        $dao = new EngineBlock_Model_DbTable_GroupProviderPrecondition();
        if (strlen(trim($group_provider_id)) > 0 && strlen(trim($precondition_id)) > 0) {
            return $dao->delete("group_provider_id='$group_provider_id' AND precondition_id='$precondition_id'");
        } else {
            return false;
        }
    }

    public function updateGroupProviderId($oldId, $newId)
    {
        if (strlen($oldId) > 0 && strlen($newId) > 0 && $oldId != $newId) {
            $dao = new EngineBlock_Model_DbTable_GroupProviderPrecondition();
            return $dao->update(array('group_provider_id' => $newId), "group_provider_id = '$oldId'");
        } else {
            // ignore
            return true;
        }

    }
}
