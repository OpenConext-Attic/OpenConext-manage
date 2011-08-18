<?php
/**
 *
 */

class EngineBlock_Service_GroupProviderDecorator
{
    public function listSearch(Surfnet_Search_Parameters $params, $group_provider_id = '')
    {
        return $this->_searchWhere($params, "group_provider_id = '$group_provider_id'");
    }

    protected function _searchWhere(Surfnet_Search_Parameters $params, $where = '')
    {
        // select Decorator record(s)
        $dao = new EngineBlock_Model_DbTable_GroupProviderDecorator();

        $query = $dao->select()->from($dao);
        if (strlen(trim($where)) > 0) {
            $query->where($where);
        }
        if ($params->getLimit()) {
            $query->limit($params->getLimit(), $params->getOffset());
        }
        if ($params->getSortByField()) {
            $query->order('group_provider_decorator.' . $params->getSortByField() . ' ' . $params->getSortDirection());
        }
        $decoratorRecords = $dao->fetchAll($query);
        $totalCount = $dao->fetchRow(
            $query->reset(Zend_Db_Select::LIMIT_COUNT)
                    ->reset(Zend_Db_Select::LIMIT_OFFSET)
                    ->columns(array('count' => 'COUNT(*)'))
        )->offsetGet('count');

        return new Surfnet_Search_Results($params, $decoratorRecords, $totalCount);
    }

    public function fetchById($group_provider_id, $decorator_id)
    {
        $mapper = new EngineBlock_Model_Mapper_GroupProviderDecorator(new EngineBlock_Model_DbTable_GroupProviderDecorator());
        return $mapper->fetchById($group_provider_id, $decorator_id);
    }

    public function save($data, $overwrite = false)
    {
        if (isset($data['group_provider_id']) && isset($data['decorator_id']) && $data['decorator_id'] == $data['org_decorator_id'] && !$overwrite) {
            $group_provider_id = htmlentities($data['group_provider_id']);
            $decorator_id = htmlentities($data['decorator_id']);
            $decoratorService = new EngineBlock_Service_GroupProviderDecorator();
            $decorator = $decoratorService->fetchById($group_provider_id, $decorator_id);
        }
        else {
            $decorator = new EngineBlock_Model_GroupProviderDecorator();
        }
        $decorator->populate($data);

        $form = new EngineBlock_Form_GroupProviderDecorator();
        if (!$form->isValid($decorator->toArray())) {
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
            $decorator->errors = $modelErrors;
        } else {
            $mapper = new EngineBlock_Model_Mapper_GroupProviderDecorator(new EngineBlock_Model_DbTable_GroupProviderDecorator());
            $isNewRecord = (isset($data['org_decorator_id']) && $decorator->decorator_id != $data['org_decorator_id']);
            $mapper->save($decorator, $isNewRecord);
        }
        return $decorator;
    }

    public function delete($group_provider_id, $decorator_id)
    {
        $dao = new EngineBlock_Model_DbTable_GroupProviderDecorator();
        if (strlen(trim($group_provider_id)) > 0 && strlen(trim($decorator_id)) > 0) {
            return $dao->delete("group_provider_id='$group_provider_id' AND decorator_id='$decorator_id'");
        } else {
            return false;
        }
    }

    public function updateGroupProviderId($oldId, $newId)
    {
        if (strlen($oldId) > 0 && strlen($newId) > 0 && $oldId != $newId) {
            $dao = new EngineBlock_Model_DbTable_GroupProviderDecorator();
            return $dao->update(array('group_provider_id' => $newId), "group_provider_id = '$oldId'");
        } else {
            // ignore
            return true;
        }

    }
}
