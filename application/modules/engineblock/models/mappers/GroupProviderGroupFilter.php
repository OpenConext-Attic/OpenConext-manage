<?php
/**
 *
 */

class EngineBlock_Model_Mapper_GroupProviderGroupFilter
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
     * Fetch Group Provider Group Filter By ID
     *
     * @throws Exception
     * @param  $id
     *
     * @return EngineBlock_Model_GroupProviderGroupFilter
     */
    public function fetchById($gp_id, $decorator_id)
    {
        $rowsFound = $this->_dao->find($gp_id, $decorator_id);
        if ($rowsFound->count() < 1) {
            throw new Exception("Group Provider Group Filter with key '$gp_id, $decorator_id' not found");
        }
        if ($rowsFound->count() > 1) {
            throw new Exception("Multiple Group Provider Group Filters with key '$gp_id, $decorator_id' found?");
        }

        $row = $rowsFound->current();

        $groupProviderGroupFilter = new EngineBlock_Model_GroupProviderGroupFilter();
        $this->_mapRowToGroupProviderDecorator($row, $groupProviderGroupFilter);

        return $groupProviderGroupFilter;
    }

    protected function _mapRowToGroupProviderDecorator(Zend_Db_Table_Row_Abstract $row, EngineBlock_Model_GroupProviderGroupFilter $groupProviderGroupFilter)
    {
        $groupProviderGroupFilter->group_provider_id = $row['group_provider_id'];
        $groupProviderGroupFilter->group_filter_id = $row['group_filter_id'];
        $groupProviderGroupFilter->group_filter_class_name = $row['group_filter_class_name'];
        $groupProviderGroupFilter->group_filter_property = $row['group_filter_property'];
        $groupProviderGroupFilter->group_filter_search = $row['group_filter_search'];
        $groupProviderGroupFilter->group_filter_replace = $row['group_filter_replace'];

        return $groupProviderGroupFilter;
    }
}
