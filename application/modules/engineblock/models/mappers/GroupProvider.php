<?php
/**
 *
 */

class EngineBlock_Model_Mapper_GroupProvider
{

    /**
     * @var EngineBlock_ModelDbTable_Abstract
     */
    protected $dao;

    public function __construct(EngineBlock_Model_DbTable_Abstract $dao)
    {
        $this->_dao = $dao;
    }

    public function fetchById($id)
    {
        $rowsFound = $this->_dao->find($id);
        if ($rowsFound->count() < 1) {
            throw new Exception("Group Provider with id '$id' not found");
        }
        if ($rowsFound->count() > 1) {
            throw new Exception("Multiple Group Providers with id '$id' found?");
        }

        $row = $rowsFound->current();

        // get corresponding Group Provider Options
        $decorators = $row->findDependentRowset('EngineBlock_Model_DbTable_GroupProviderDecorator')->toArray();
        $groupFilters = $row->findDependentRowset('EngineBlock_Model_DbTable_GroupProviderGroupFilter')->toArray();
        $groupMemberFilters = $row->findDependentRowset('EngineBlock_Model_DbTable_GroupProviderGroupMemberFilter')->toArray();
        $preconditions = $row->findDependentRowset('EngineBlock_Model_DbTable_GroupProviderPrecondition')->toArray();

        $groupProvider = new EngineBlock_Model_GroupProvider();
        $this->_mapRowToGroupProvider($row, $decorators, $groupFilters, $groupMemberFilters, $preconditions, $groupProvider);

        return $groupProvider;
    }

    protected function _mapRowToGroupProvider(Zend_Db_Table_Row_Abstract $row, array $decorators, array $groupFilters, array $groupMemberFilters, array $preconditions, EngineBlock_Model_GroupProvider $groupProvider)
    {
        $groupProvider->group_provider_id = $row['group_provider_id'];
        $groupProvider->group_provider_type = $row['group_provider_type'];
        $groupProvider->adapter = $row['adapter'];
        $groupProvider->class_name = $row['class_name'];
        $groupProvider->endpoint = $row['endpoint'];
        $groupProvider->timeout = $row['timeout'];

        $groupProvider->preconditions = $preconditions;
        $groupProvider->decorators = $decorators;
        $groupProvider->group_filters = $groupFilters;
        $groupProvider->group_member_filters = $groupMemberFilters;

        return $groupProvider;
    }
    
    /**
     *
     * @param EngineBlock_Model_GroupProvider $groupProvider
     */
    public function save(EngineBlock_Model_GroupProvider $groupProvider, $isNewRecord = false)
    {
        // get existing record or create a new one
        if (!$isNewRecord) {
            $row = $this->_dao->find($groupProvider->group_provider_id)->current();
        } else {
            $row = $this->_dao->createRow();
        }

        // check the PK
        $uniqueSelect = $this->_dao->select()->where('group_provider_id = ?', $groupProvider->group_provider_id);
        $duplicates = $this->_dao->fetchAll($uniqueSelect)->toArray();
        if (!$isNewRecord || count($duplicates) == 0) {
            $row = $this->_mapGroupProviderToRow($groupProvider, $row);
            $row->save();
        }
        else {
            $groupProvider->errors['group_provider_id'][] = "A Group Provider with id '{$duplicates[0]['group_provider_id']}' already exists";
        }

        return $groupProvider;
    }
    
    protected function _mapGroupProviderToRow(EngineBlock_Model_GroupProvider $groupProvider, Zend_Db_Table_Row_Abstract $row)
    {
        $row['group_provider_id'] = $groupProvider->group_provider_id;
        $row['group_provider_type'] = $groupProvider->group_provider_type;
        $row['adapter'] = $groupProvider->adapter;
        $row['class_name'] = $groupProvider->class_name;
        $row['endpoint'] = $groupProvider->endpoint;
        $row['timeout'] = $groupProvider->timeout;
        return $row;
    }
}
