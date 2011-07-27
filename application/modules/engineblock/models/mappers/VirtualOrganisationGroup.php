<?php
/**
 *
 */

class EngineBlock_Model_Mapper_VirtualOrganisationGroup
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
     * @throws Exception
     * @param  $id
     * @return EngineBlock_Model_VirtualOrganisationGroup
     */
    public function fetchById($vo_id, $group_id)
    {
        $rowsFound = $this->_dao->find($vo_id, $group_id);
        if ($rowsFound->count() < 1) {
            throw new Exception("Virtual Organisation Group with key '$vo_id, $group_id' not found");
        }
        if ($rowsFound->count() > 1) {
            throw new Exception("Multiple Virtual Organisation Groups with key '$vo_id, $group_id' found?");
        }

        $row = $rowsFound->current();

        $virtualOrganisationGroup = new EngineBlock_Model_VirtualOrganisationGroup();
        $this->_mapRowToVirtualOrganisationGroup($row, $virtualOrganisationGroup);
        
        return $virtualOrganisationGroup;
    }

    /**
     *
     * @param EngineBlock_Model_VirtualOrganisationGroup $virtualOrganisationGroup
     */
    public function save(EngineBlock_Model_VirtualOrganisationGroup $virtualOrganisationGroup, $isNewRecord=false)
    {
        if (!$isNewRecord) {
            $row = $this->_dao->find($virtualOrganisationGroup->vo_id, $virtualOrganisationGroup->group_id)->current();
        }
        else {
            $row = $this->_dao->createRow();
        }

        $uniqueSelect = $this->_dao->select()->where('vo_id = ? AND group_id = ?', $virtualOrganisationGroup->vo_id, $virtualOrganisationGroup->group_id);
        $duplicates = $this->_dao->fetchAll($uniqueSelect)->toArray();
        
        if (empty($duplicates)) {
            $row = $this->_mapVirtualOrganisationGroupToRow($virtualOrganisationGroup, $row);
            $row->save();
        }
        else {
            $virtualOrganisationGroup->errors['url'][] = "A Virtual OrganisationGroup with this id already exists";
        }

        return $virtualOrganisationGroup;
    }

    protected function _mapRowToVirtualOrganisationGroup(Zend_Db_Table_Row_Abstract $row, EngineBlock_Model_VirtualOrganisationGroup $virtualOrganisationGroup)
    {
        $virtualOrganisationGroup->vo_id         = $row['vo_id'];
        $virtualOrganisationGroup->group_id      = $row['group_id'];
        return $virtualOrganisationGroup;
    }

    protected function _mapVirtualOrganisationGroupToRow(EngineBlock_Model_VirtualOrganisationGroup $virtualOrganisationGroup, Zend_Db_Table_Row_Abstract $row)
    {
        $row['vo_id']                  = $virtualOrganisationGroup->vo_id;
        $row['group_id']               = $virtualOrganisationGroup->group_id;
        return $row;
    }
}
