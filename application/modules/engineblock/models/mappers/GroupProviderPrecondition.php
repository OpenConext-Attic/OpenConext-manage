<?php
/**
 *
 */

class EngineBlock_Model_Mapper_GroupProviderPrecondition
{
    /**
     * @var EngineBlock_Model_DbTable_Abstract
     */
    protected $dao;

    public function __construct(EngineBlock_Model_DbTable_Abstract $dao)
    {
        $this->_dao = $dao;
    }

    /**
     * Fetch Group Provider Precondition By ID
     *
     * @throws Exception
     * @param  $id
     *
     * @return EngineBlock_Model_GroupProviderPrecondition
     */
    public function fetchById($gp_id, $precondition_id)
    {
        $rowsFound = $this->_dao->find($precondition_id, $gp_id);
        if ($rowsFound->count() < 1) {
            throw new Exception("Group Provider Precondition with key '$precondition_id, $gp_id' not found");
        }
        if ($rowsFound->count() > 1) {
            throw new Exception("Multiple Group Provider Preconditions with key '$precondition_id, $gp_id' found?");
        }

        $row = $rowsFound->current();

        $groupProviderPrecondition = new EngineBlock_Model_GroupProviderPrecondition();
        $this->_mapRowToGroupProviderPrecondition($row, $groupProviderPrecondition);

        return $groupProviderPrecondition;
    }

    /**
     *
     * @param EngineBlock_Model_GroupProviderPrecondition $groupProviderPrecondition
     */
    public function save(EngineBlock_Model_GroupProviderPrecondition $groupProviderPrecondition, $isNewRecord=false)
    {
        if (!$isNewRecord) {
            $row = $this->_dao->find($groupProviderPrecondition->precondition_id, $groupProviderPrecondition->group_provider_id)->current();
        }
        else {
            $row = $this->_dao->createRow();
        }

        $uniqueSelect = $this->_dao->select()->where('group_provider_id = ? AND precondition_id = ?', $groupProviderPrecondition->group_provider_id, $groupProviderPrecondition->precondition_id);
        $duplicates = $this->_dao->fetchAll($uniqueSelect)->toArray();
        
        if (empty($duplicates)) {
            $row = $this->_mapGroupProviderPreconditionToRow($groupProviderPrecondition, $row);
            $row->save();
        }
        else {
            $groupProviderPrecondition->errors['url'][] = "A Precondition with this id already exists";
        }

        return $groupProviderPrecondition;
    }    
    
    protected function _mapRowToGroupProviderPrecondition(Zend_Db_Table_Row_Abstract $row, EngineBlock_Model_GroupProviderPrecondition $groupProviderPrecondition)
    {
        $groupProviderPrecondition->group_provider_id = $row['group_provider_id'];
        $groupProviderPrecondition->precondition_id = $row['precondition_id'];
        $groupProviderPrecondition->precondition_class_name = $row['precondition_class_name'];
        $groupProviderPrecondition->precondition_search = $row['precondition_search'];

        return $groupProviderPrecondition;
    }
    
    protected function _mapGroupProviderPreconditionToRow(EngineBlock_Model_GroupProviderPrecondition $groupProviderPrecondition, Zend_Db_Table_Row_Abstract $row)
    {
        $row['group_provider_id']      = $groupProviderPrecondition->group_provider_id;
        $row['precondition_id']           = $groupProviderPrecondition->precondition_id;
        $row['precondition_class_name']   = $groupProviderPrecondition->precondition_class_name;
        $row['precondition_search']       = $groupProviderPrecondition->precondition_search;
        
        return $row;
    }
    
}
