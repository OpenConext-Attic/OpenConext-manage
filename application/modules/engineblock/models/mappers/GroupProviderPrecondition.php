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
    public function fetchById($gp_id, $decorator_id)
    {
        $rowsFound = $this->_dao->find($gp_id, $decorator_id);
        if ($rowsFound->count() < 1) {
            throw new Exception("Group Provider Decorator with key '$gp_id, $decorator_id' not found");
        }
        if ($rowsFound->count() > 1) {
            throw new Exception("Multiple Group Provider Decorators with key '$gp_id, $decorator_id' found?");
        }

        $row = $rowsFound->current();

        $groupProviderPrecondition = new EngineBlock_Model_GroupProviderPrecondition();
        $this->_mapRowToGroupProviderPrecondition($row, $groupProviderPrecondition);

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
}
