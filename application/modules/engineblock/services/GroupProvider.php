<?php
/**
 *
 */

class EngineBlock_Service_GroupProvider
{
    public function listSearch(Surfnet_Search_Parameters $params)
    {
        return $this->_searchWhere($params);
    }

    protected function _searchWhere(Surfnet_Search_Parameters $params, $where = '')
    {
        // Select Group Provider Records
        $dao = new EngineBlock_Model_DbTable_GroupProvider();

        $query = $dao->select()->from($dao);
        if (strlen(trim($where)) > 0) {
            $query->where($where);
        }
        if ($params->getLimit()) {
            $query->limit($params->getLimit(), $params->getOffset());
        }
        if ($params->getSortByField()) {
            $query->order('group_provider.' . $params->getSortByField() . ' ' . $params->getSortDirection());
        }
        $gpRecords = $dao->fetchAll($query);
        $gpRecords = $gpRecords->toArray();
        // get full classnames
        foreach ($gpRecords as &$record) {
            $record['fullClassname'] = $record['classname'];
            $record['classname'] = EngineBlock_Model_GroupProvider::getClassnameDisplayValue($record['classname']);
        }

        $totalCount = $dao->fetchRow(
            $query->reset(Zend_Db_Select::LIMIT_COUNT)
                    ->reset(Zend_Db_Select::LIMIT_OFFSET)
                    ->columns(array('count' => 'COUNT(*)'))
        )->offsetGet('count');

        return new Surfnet_Search_Results($params, $gpRecords, $totalCount);
    }

    public function fetchById($id)
    {
        $mapper = new EngineBlock_Model_Mapper_GroupProvider(new EngineBlock_Model_DbTable_GroupProvider());
        return $mapper->fetchById($id);
    }

    public function save($data)
    {
        if (isset($data['id']) && intval($data['id']) > 0) {
            $gpService = new EngineBlock_Service_GroupProvider();
            $gp = $gpService->fetchById(intval($data['id']));

            // set explicitly to off, if they're enabled they will
            // be overridden by POST values
            $gp->user_id_match = 'off';
            $gp->modify_user = 'off';
            $gp->modify_user_id = 'off';
            $gp->modify_group = 'off';
            $gp->modify_group_id = 'off';
        }
        else {
            $gp = new EngineBlock_Model_GroupProvider();
        }

        $gp->populate($data);

        $data = $gp->toArray();

        $form = new EngineBlock_Form_GroupProvider();
        $customErrors = $form->validateCustomFields($data);

        if (!$form->isValid($data) || !empty($customErrors)) {
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

            // check for non-zf validates error messages
            foreach ($customErrors as $fieldName => $errors) {
                foreach ($errors as $error) {
                    $modelErrors[$fieldName][] = $error;
                }
            }

            $gp->errors = $modelErrors;
        } else {
            $mapper = new EngineBlock_Model_Mapper_GroupProvider(new EngineBlock_Model_DbTable_GroupProvider());
            $gp = $mapper->save($gp, !(intval($data['id']) > 0));
        }
        return $gp;
    }


    public function delete($id)
    {
        // Select Group Provider Records
        $dao = new EngineBlock_Model_DbTable_GroupProvider();
        $rows = $dao->find($id);
        if ($rows->count() !== 1) {
            throw new Exception("Group provider with id '$id' not found");
        }
        return $rows->current()->delete();
    }
}
