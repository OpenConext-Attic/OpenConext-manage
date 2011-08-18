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
     * Fetch Group Provider GroupMemberFilter By ID
     *
     * @throws Exception
     * @param  $id
     *
     * @return EngineBlock_Model_GroupProviderGroupMemberFilter
     */
    public function fetchById($gp_id, $group_member_filter_id)
    {
        $rowsFound = $this->_dao->find($group_member_filter_id, $gp_id);
        if ($rowsFound->count() < 1) {
            throw new Exception("Group Provider Group Filter with key '$group_member_filter_id, $gp_id' not found");
        }
        if ($rowsFound->count() > 1) {
            throw new Exception("Multiple Group Provider Group Filters with key '$group_member_filter_id, $gp_id' found?");
        }

        $row = $rowsFound->current();

        $groupProviderGroupMemberFilter = new EngineBlock_Model_GroupProviderGroupMemberFilter();
        $this->_mapRowToGroupProviderGroupMemberFilter($row, $groupProviderGroupMemberFilter);

        return $groupProviderGroupMemberFilter;
    }

    /**
     *
     * @param EngineBlock_Model_GroupProviderGroupMemberFilter $groupProviderGroupMemberFilter
     */
    public function save(EngineBlock_Model_GroupProviderGroupMemberFilter $groupProviderGroupMemberFilter, $isNewRecord=false)
    {
        if (!$isNewRecord) {
            $row = $this->_dao->find($groupProviderGroupMemberFilter->group_member_filter_id, $groupProviderGroupMemberFilter->group_provider_id)->current();
        }
        else {
            $row = $this->_dao->createRow();
        }

        $uniqueSelect = $this->_dao->select()->where('group_provider_id = ? AND group_member_filter_id = ?', $groupProviderGroupMemberFilter->group_provider_id, $groupProviderGroupMemberFilter->group_member_filter_id);
        $duplicates = $this->_dao->fetchAll($uniqueSelect)->toArray();
        
        if (empty($duplicates)) {
            $row = $this->_mapGroupProviderGroupMemberFilterToRow($groupProviderGroupMemberFilter, $row);
            $row->save();
        }
        else {
            $groupProviderGroupMemberFilter->errors['url'][] = "A Group Filter with this id already exists";
        }

        return $groupProviderGroupMemberFilter;
    }    
    
    protected function _mapRowToGroupProviderGroupMemberFilter(Zend_Db_Table_Row_Abstract $row, EngineBlock_Model_GroupProviderGroupMemberFilter $groupProviderGroupMemberFilter)
    {
        $groupProviderGroupMemberFilter->group_provider_id = $row['group_provider_id'];
        $groupProviderGroupMemberFilter->group_member_filter_id = $row['group_member_filter_id'];
        $groupProviderGroupMemberFilter->group_member_filter_class_name = $row['group_member_filter_class_name'];
        $groupProviderGroupMemberFilter->group_member_filter_property = $row['group_member_filter_property'];
        $groupProviderGroupMemberFilter->group_member_filter_search = $row['group_member_filter_search'];
        $groupProviderGroupMemberFilter->group_member_filter_replace = $row['group_member_filter_replace'];

        return $groupProviderGroupMemberFilter;
    }
    
    protected function _mapGroupProviderGroupMemberFilterToRow(EngineBlock_Model_GroupProviderGroupMemberFilter $groupProviderGroupMemberFilter, Zend_Db_Table_Row_Abstract $row)
    {
        $row['group_provider_id']        = $groupProviderGroupMemberFilter->group_provider_id;
        $row['group_member_filter_id']           = $groupProviderGroupMemberFilter->group_member_filter_id;
        $row['group_member_filter_class_name']   = $groupProviderGroupMemberFilter->group_member_filter_class_name;
        $row['group_member_filter_property']     = $groupProviderGroupMemberFilter->group_member_filter_property;
        $row['group_member_filter_replace']      = $groupProviderGroupMemberFilter->group_member_filter_replace;
        $row['group_member_filter_search']       = $groupProviderGroupMemberFilter->group_member_filter_search;
        
        return $row;
    }
    
}
