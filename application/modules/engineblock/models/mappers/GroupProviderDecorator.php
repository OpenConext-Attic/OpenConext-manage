<?php
/**
 *
 */

class EngineBlock_Model_Mapper_GroupProviderDecorator
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
     * Fetch Group Provider Decorator By ID
     *
     * @throws Exception
     * @param  $id
     *
     * @return EngineBlock_Model_GroupProviderDecorator
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

        $groupProviderDecorator = new EngineBlock_Model_GroupProviderDecorator();
        $this->_mapRowToGroupProviderDecorator($row, $groupProviderDecorator);

        return $groupProviderDecorator;
    }

    protected function _mapRowToGroupProviderDecorator(Zend_Db_Table_Row_Abstract $row, EngineBlock_Model_GroupProviderDecorator $groupProviderDecorator)
    {
        $groupProviderDecorator->group_provider_id = $row['group_provider_id'];
        $groupProviderDecorator->decorator_id = $row['decorator_id'];
        $groupProviderDecorator->decorator_class_name = $row['decorator_class_name'];
        $groupProviderDecorator->decorator_search = $row['decorator_search'];
        $groupProviderDecorator->decorator_replace = $row['decorator_replace'];

        return $groupProviderDecorator;
    }
}
