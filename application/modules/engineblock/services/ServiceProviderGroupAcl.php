<?php
/**
 *
 */

class EngineBlock_Service_ServiceProviderGroupAcl
{
    public function findById($id)
    {
        $mapper = new EngineBlock_Model_Mapper_ServiceProviderGroupAcl(new EngineBlock_Model_DbTable_ServiceProviderGroupAcl());
        return $mapper->fetchById($id);
    }


    /**
     * Find all EngineBlock_Model_ServiceProviderGroupAcl for a certain GroupProvider
     *
     * @param $id the unique identifier of the GroupProvider
     * @return An array of EngineBlock_Model_ServiceProviderGroupAcl objects
     */
    public function findByGroupProviderId($groupProviderId)
    {
        $mapper = new EngineBlock_Model_Mapper_ServiceProviderGroupAcl(new EngineBlock_Model_DbTable_ServiceProviderGroupAcl());
        return $mapper->fetchByGroupProviderId($groupProviderId);
    }

    public function search(Surfnet_Search_Parameters $params)
    {
        $dao = new EngineBlock_Model_DbTable_ServiceProviderGroupAcl();

        $query = $dao->select()->from($dao)->columns();

        $searchParams = $params->getSearchParams();
        foreach ($searchParams as $key => $value) {
            if (!$value) {
                continue;
            }

            $query->where($key . ' LIKE ' . $dao->getAdapter()->quote('%' . $value . '%'));
        }

        if ($params->getLimit()) {
            $query->limit($params->getLimit(), $params->getOffset());
        }
        if ($params->getSortByField()) {
            $query->order($params->getSortByField() . ' ' . $params->getSortDirection());
        }
        $results = $dao->fetchAll($query)->toArray();

        $totalCount = $dao->fetchRow(
            $query->reset(Zend_Db_Select::LIMIT_COUNT)
                    ->reset(Zend_Db_Select::LIMIT_OFFSET)
                    ->columns(array('count'=>'COUNT(*)'))
        )->offsetGet('count');

        return new Surfnet_Search_Results($params, $results, $totalCount);
    }

    public function save($data, $overwrite = false)
    {
        if (isset($data['id']) && !$overwrite) {
            $id = (int)$data['id'];
            $model = $this->findById($id);
        }
        else {
            $model = new EngineBlock_Model_ServiceProviderGroupAcl();
        }
        $model->populate($data);

        $form = new EngineBlock_Form_ServiceProviderGroupAcl();
        if (!$form->isValid($model->toArray())) {
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
            $model->errors = $modelErrors;
            return $model;
        }

        $mapper = new EngineBlock_Model_Mapper_ServiceProviderGroupAcl(new EngineBlock_Model_DbTable_ServiceProviderGroupAcl());
        $mapper->save($model);
        return $model;
    }

    public function delete($id)
    {
        $dao = new EngineBlock_Model_DbTable_ServiceProviderGroupAcl();
        return $dao->delete(array('id=?'=>$id));
    }
}