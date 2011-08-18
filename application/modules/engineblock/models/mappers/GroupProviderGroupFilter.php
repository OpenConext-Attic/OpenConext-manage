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
     * Fetch Group Provider GroupFilter By ID
     *
     * @throws Exception
     * @param  $id
     *
     * @return EngineBlock_Model_GroupProviderGroupFilter
     */
    public function fetchById($gp_id, $group_filter_id)
    {
        $rowsFound = $this->_dao->find($group_filter_id, $gp_id);
        if ($rowsFound->count() < 1) {
            throw new Exception("Group Provider Group Filter with key '$group_filter_id, $gp_id' not found");
        }
        if ($rowsFound->count() > 1) {
            throw new Exception("Multiple Group Provider Group Filters with key '$group_filter_id, $gp_id' found?");
        }

        $row = $rowsFound->current();

        $groupProviderGroupFilter = new EngineBlock_Model_GroupProviderGroupFilter();
        $this->_mapRowToGroupProviderGroupFilter($row, $groupProviderGroupFilter);

        return $groupProviderGroupFilter;
    }

    /**
     *
     * @param EngineBlock_Model_GroupProviderGroupFilter $groupProviderGroupFilter
     */
    public function save(EngineBlock_Model_GroupProviderGroupFilter $groupProviderGroupFilter, $isNewRecord=false)
    {
        if (!$isNewRecord) {
            $row = $this->_dao->find($groupProviderGroupFilter->group_filter_id, $groupProviderGroupFilter->group_provider_id)->current();
        }
        else {
            $row = $this->_dao->createRow();
        }

        $uniqueSelect = $this->_dao->select()->where('group_provider_id = ? AND group_filter_id = ?', $groupProviderGroupFilter->group_provider_id, $groupProviderGroupFilter->group_filter_id);
        $duplicates = $this->_dao->fetchAll($uniqueSelect)->toArray();
        
        if (empty($duplicates)) {
            $row = $this->_mapGroupProviderGroupFilterToRow($groupProviderGroupFilter, $row);
            $row->save();
        }
        else {
            $groupProviderGroupFilter->errors['url'][] = "A Group Filter with this id already exists";
        }

        return $groupProviderGroupFilter;
    }    
    
    protected function _mapRowToGroupProviderGroupFilter(Zend_Db_Table_Row_Abstract $row, EngineBlock_Model_GroupProviderGroupFilter $groupProviderGroupFilter)
    {
        $groupProviderGroupFilter->group_provider_id = $row['group_provider_id'];
        $groupProviderGroupFilter->group_filter_id = $row['group_filter_id'];
        $groupProviderGroupFilter->group_filter_class_name = $row['group_filter_class_name'];
        $groupProviderGroupFilter->group_filter_property = $row['group_filter_property'];
        $groupProviderGroupFilter->group_filter_search = $row['group_filter_search'];
        $groupProviderGroupFilter->group_filter_replace = $row['group_filter_replace'];

        return $groupProviderGroupFilter;
    }
    
    protected function _mapGroupProviderGroupFilterToRow(EngineBlock_Model_GroupProviderGroupFilter $groupProviderGroupFilter, Zend_Db_Table_Row_Abstract $row)
    {
        $row['group_provider_id']        = $groupProviderGroupFilter->group_provider_id;
        $row['group_filter_id']           = $groupProviderGroupFilter->group_filter_id;
        $row['group_filter_class_name']   = $groupProviderGroupFilter->group_filter_class_name;
        $row['group_filter_property']     = $groupProviderGroupFilter->group_filter_property;
        $row['group_filter_replace']      = $groupProviderGroupFilter->group_filter_replace;
        $row['group_filter_search']       = $groupProviderGroupFilter->group_filter_search;
        
        return $row;
    }
    
}
