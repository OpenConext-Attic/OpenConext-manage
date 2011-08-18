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
        $rowsFound = $this->_dao->find($decorator_id, $gp_id);
        if ($rowsFound->count() < 1) {
            throw new Exception("Group Provider Decorator with key '$decorator_id, $gp_id' not found");
        }
        if ($rowsFound->count() > 1) {
            throw new Exception("Multiple Group Provider Decorators with key '$decorator_id, $gp_id' found?");
        }

        $row = $rowsFound->current();

        $groupProviderDecorator = new EngineBlock_Model_GroupProviderDecorator();
        $this->_mapRowToGroupProviderDecorator($row, $groupProviderDecorator);

        return $groupProviderDecorator;
    }

    /**
     *
     * @param EngineBlock_Model_GroupProviderDecorator $groupProviderDecorator
     */
    public function save(EngineBlock_Model_GroupProviderDecorator $groupProviderDecorator, $isNewRecord=false)
    {
        if (!$isNewRecord) {
            $row = $this->_dao->find($groupProviderDecorator->decorator_id, $groupProviderDecorator->group_provider_id)->current();
        }
        else {
            $row = $this->_dao->createRow();
        }

        $uniqueSelect = $this->_dao->select()->where('group_provider_id = ? AND decorator_id = ?', $groupProviderDecorator->group_provider_id, $groupProviderDecorator->decorator_id);
        $duplicates = $this->_dao->fetchAll($uniqueSelect)->toArray();
        
        if (empty($duplicates)) {
            $row = $this->_mapGroupProviderDecoratorToRow($groupProviderDecorator, $row);
            $row->save();
        }
        else {
            $groupProviderDecorator->errors['url'][] = "A Decorator with this id already exists";
        }

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
    
    protected function _mapGroupProviderDecoratorToRow(EngineBlock_Model_GroupProviderDecorator $groupProviderDecorator, Zend_Db_Table_Row_Abstract $row)
    {
        $row['group_provider_id']      = $groupProviderDecorator->group_provider_id;
        $row['decorator_id']           = $groupProviderDecorator->decorator_id;
        $row['decorator_class_name']   = $groupProviderDecorator->decorator_class_name;
        $row['decorator_replace']      = $groupProviderDecorator->decorator_replace;
        $row['decorator_search']       = $groupProviderDecorator->decorator_search;
        
        return $row;
    }
    
}
