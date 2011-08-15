<?php
/**
 *
 */

class EngineBlock_Service_GroupProvider
{
    public function listSearch(Surfnet_Search_Parameters $params)
    {
        return $this->_searchWhere($params);
    }

    protected function _searchWhere(Surfnet_Search_Parameters $params, $where = '')
    {
        // Select Group Provider Records
        $dao = new EngineBlock_Model_DbTable_GroupProvider();

        $query = $dao->select()->from($dao);
        if (strlen(trim($where)) > 0) {
            $query->where($where);
        }
        if ($params->getLimit()) {
            $query->limit($params->getLimit(), $params->getOffset());
        }
        if ($params->getSortByField()) {
            $query->order('group_provider.' . $params->getSortByField() . ' ' . $params->getSortDirection());
        }
        $gpRecords = $dao->fetchAll($query);

        // get the corresponding decorators
        $decoratorRecords = array();
        foreach ($gpRecords as $row) {
            /* @var $row Zend_Db_Table_Row */
            $decoratorRecords[$row->group_provider_id] = $row->findDependentRowset('EngineBlock_Model_DbTable_GroupProviderDecorator')->toArray();
            // remove FK's
            foreach ($decoratorRecords[$row['group_provider_id']] as &$decoratorRow) {
                unset($decoratorRow['group_provider_id']);
            }
        }
        unset($row);

        // get corresponding group filters
        $groupFilterRecords = array();
        foreach ($gpRecords as $row) {
            /* @var $row Zend_Db_Table_Row */
            $groupFilterRecords[$row->group_provider_id] = $row->findDependentRowset('EngineBlock_Model_DbTable_GroupProviderGroupFilter')->toArray();
            // remove FK's
            foreach ($groupFilterRecords[$row['group_provider_id']] as &$groupFilterRow) {
                unset($groupFilterRow['group_provider_id']);
            }
        }
        unset($row);

        // get corresponding group member filters
        $groupMemberFilterRecords = array();
        foreach ($gpRecords as $row) {
            /* @var $row Zend_Db_Table_Row */
            $groupMemberFilterRecords[$row->group_provider_id] = $row->findDependentRowset('EngineBlock_Model_DbTable_GroupProviderGroupMemberFilter')->toArray();
            // remove FK's
            foreach ($groupMemberFilterRecords[$row['group_provider_id']] as &$groupMemberFilterRow) {
                unset($groupMemberFilterRow['group_provider_id']);
            }
        }
        unset($row);

        // get corresponding groups
        $preconditionRecords = array();
        foreach ($gpRecords as $row) {
            /* @var $row Zend_Db_Table_Row */
            $preconditionRecords[$row->group_provider_id] = $row->findDependentRowset('EngineBlock_Model_DbTable_GroupProviderPrecondition')->toArray();
            // remove FK's
            foreach ($preconditionRecords[$row['group_provider_id']] as &$preconditionRow) {
                unset($preconditionRow['group_provider_id']);
            }
        }
        unset($row);

        // merge decorators into Group Providers
        $gpRecords = $gpRecords->toArray();
        foreach ($gpRecords as &$row) {
            $row['decorators'] = $decoratorRecords[$row['group_provider_id']];
        }
        unset($row);

//        var_dump($gpRecords, true);die();
        // merge group filters into Group Providers
//        $gpRecords = $gpRecords->toArray();
        foreach ($gpRecords as &$row) {
            $row['group_filters'] = $groupFilterRecords[$row['group_provider_id']];
        }
        unset($row);

        // merge group member filters into Group Providers
        //$gpRecords = $gpRecords->toArray();
        foreach ($gpRecords as &$row) {
            $row['group_member_filter'] = $groupMemberFilterRecords[$row['group_provider_id']];
        }
        unset($row);

        // merge preconditions into Group Providers
        //$gpRecords = $gpRecords->toArray();
        foreach ($gpRecords as &$row) {
            $row['preconditions'] = $decoratorRecords[$row['group_provider_id']];
        }
        unset($row);

        $totalCount = $dao->fetchRow(
            $query->reset(Zend_Db_Select::LIMIT_COUNT)
                    ->reset(Zend_Db_Select::LIMIT_OFFSET)
                    ->columns(array('count' => 'COUNT(*)'))
        )->offsetGet('count');

        return new Surfnet_Search_Results($params, $gpRecords, $totalCount);
    }

    public function fetchById($id)
    {
        $mapper = new EngineBlock_Model_Mapper_GroupProvider(new EngineBlock_Model_DbTable_GroupProvider());
        return $mapper->fetchById($id);
    }
}
