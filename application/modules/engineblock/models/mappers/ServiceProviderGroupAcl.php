<?php
/**
 *
 */

class EngineBlock_Model_Mapper_ServiceProviderGroupAcl
{
    /**
     * @var EngineBlock_Model_DbTable_Abstract
     */
    protected $dao;

    public function __construct(EngineBlock_Model_DbTable_Abstract $dao)
    {
        $this->_dao = $dao;
    }

    public function fetchByGroupProviderId($id)
    {
        $select = $this->_dao->select();
        $rowset = $this->_dao->fetchAll($select->where('group_provider_id = ?',$id));
        $result = array();
        foreach ($rowset as $row) {
            $result[] = $this->_mapRowToModel($row,new EngineBlock_Model_ServiceProviderGroupAcl());
        }
        return $result;
    }

    /**
     * @throws Exception
     * @param  $id
     * @return EmailConfiguration
     */
    public function fetchById($id)
    {
        $rowsFound = $this->_dao->find($id);
        if ($rowsFound->count() < 1) {
            throw new Exception("ServiceProviderGroupAcl with id '$id' not found");
        }
        if ($rowsFound->count() > 1) {
            throw new Exception("Multiple ServiceProviderGroupAcl with id '$id' found?");
        }

        $row = $rowsFound->current();
        return $this->_mapRowToModel($row, new EngineBlock_Model_ServiceProviderGroupAcl());
    }
    
    public function save(EngineBlock_Model_ServiceProviderGroupAcl $model)
    {
        if (isset($model->id) && $model->id) {
            $row = $this->_dao->find($model->id)->current();
        }
        else {
            $row = $this->_dao->createRow();
        }
        $row = $this->_mapModelToRow($model, $row);
        return $row->save();
    }

    protected function _mapRowToModel($row, $model)
    {
        $model->id = $row['id'];
        $model->groupProviderId = $row['group_provider_id'];
        $model->spentityid  = $row['spentityid'];
        $model->allow_groups  = $row['allow_groups'];
        $model->allow_members  = $row['allow_members'];
        return $model;
    }

    protected function _mapModelToRow($model, $row)
    {
        $row['id'] = $model->id;
        $row['group_provider_id'] = $model->groupProviderId;
        $row['spentityid'] = $model->spentityid;
        $row['allow_groups'] = $model->allow_groups;
        $row['allow_members'] = $model->allow_members;
        return $row;
    }
}