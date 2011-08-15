<?php
/**
 *
 */

class EngineBlock_Model_Mapper_GroupProviderGroupMemberFilter
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
     * Fetch Group Provider Member Group Filter By ID
     *
     * @throws Exception
     * @param  $id
     *
     * @return EngineBlock_Model_GroupProviderGroupMemberFilter
     */
    public function fetchById($gp_id, $decorator_id)
    {
        $rowsFound = $this->_dao->find($gp_id, $decorator_id);
        if ($rowsFound->count() < 1) {
            throw new Exception("Group Provider Group Member Filter with key '$gp_id, $decorator_id' not found");
        }
        if ($rowsFound->count() > 1) {
            throw new Exception("Multiple Group Provider Member Group Filters with key '$gp_id, $decorator_id' found?");
        }

        $row = $rowsFound->current();

        $groupProviderGroupMemberFilter = new EngineBlock_Model_GroupProviderGroupMemberFilter();
        $this->_mapRowToGroupProviderDecorator($row, $groupProviderGroupMemberFilter);

        return $groupProviderGroupMemberFilter;
    }

    protected function _mapRowToGroupProviderDecorator(Zend_Db_Table_Row_Abstract $row, EngineBlock_Model_GroupProviderGroupMemberFilter $groupProviderGroupMemberFilter)
    {
        $groupProviderGroupMemberFilter->group_provider_id = $row['group_provider_id'];
        $groupProviderGroupMemberFilter->group_member_filter_id = $row['group_member_filter_id'];
        $groupProviderGroupMemberFilter->group_member_filter_class_name = $row['group_member_filter_class_name'];
        $groupProviderGroupMemberFilter->group_member_filter_property = $row['group_member_filter_property'];
        $groupProviderGroupMemberFilter->group_member_filter_search = $row['group_member_filter_search'];
        $groupProviderGroupMemberFilter->group_member_filter_replace = $row['group_member_filter_replace'];

        return $groupProviderGroupMemberFilter;
    }
}
