<?php
/**
 *
 */

class EngineBlock_Model_Mapper_VirtualOrganisation
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
     * @return EngineBlock_Model_VirtualOrganisation
     */
    public function fetchById($id)
    {
        $rowsFound = $this->_dao->find($id);
        if ($rowsFound->count() < 1) {
            throw new Exception("Virtual Organisation with id '$id' not found");
        }
        if ($rowsFound->count() > 1) {
            throw new Exception("Multiple Virtual Organisations with id '$id' found?");
        }

        $row = $rowsFound->current();

        // get corresponding groups, idps & stem
        $groups = $row->findDependentRowset('EngineBlock_Model_DbTable_VirtualOrganisationGroup')->toArray();
        $idps = $row->findDependentRowset('EngineBlock_Model_DbTable_VirtualOrganisationIdp')->toArray();

        $virtualOrganisation = new EngineBlock_Model_VirtualOrganisation();
        $this->_mapRowToVirtualOrganisation($row, $groups, $idps, $virtualOrganisation);

        return $virtualOrganisation;
    }

    /**
     *
     * @param EngineBlock_Model_VirtualOrganisation $virtualOrganisation
     */
    public function save(EngineBlock_Model_VirtualOrganisation $virtualOrganisation, $isNewRecord = false)
    {
        // get existing record or create a new one
        if (!$isNewRecord) {
            $row = $this->_dao->find($virtualOrganisation->vo_id)->current();
        } else {
            $row = $this->_dao->createRow();
        }

        // check the PK
        $uniqueSelect = $this->_dao->select()->where('vo_id = ?', $virtualOrganisation->vo_id);
        $duplicates = $this->_dao->fetchAll($uniqueSelect)->toArray();
        if (!$isNewRecord || count($duplicates) == 0) {
            $row = $this->_mapVirtualOrganisationToRow($virtualOrganisation, $row);
            $row->save();
        }
        else {
            $virtualOrganisation->errors['vo_id'][] = "A Virtual Organisation with id '{$duplicates[0]['vo_id']}' already exists";
        }

        return $virtualOrganisation;
    }

    protected function _mapRowToVirtualOrganisation(Zend_Db_Table_Row_Abstract $row, array $groups, array $idps, EngineBlock_Model_VirtualOrganisation $virtualOrganisation)
    {
        $virtualOrganisation->vo_id = $row['vo_id'];
        $virtualOrganisation->vo_type = $row['vo_type'];
        $virtualOrganisation->groups = $groups;
        $virtualOrganisation->idps = $idps;

        if ($virtualOrganisation->vo_type === 'STEM') {
            /* @var $config Zend_Config */
            $config = Zend_Registry::get('config');
            $stemPrefix = $config->engineBlock->vo->stemPrefix;
            $virtualOrganisation->stem = $stemPrefix . $virtualOrganisation->vo_id;
        }
        return $virtualOrganisation;
    }

    protected function _mapVirtualOrganisationToRow(EngineBlock_Model_VirtualOrganisation $virtualOrganisation, Zend_Db_Table_Row_Abstract $row)
    {
        // note: groups and idps are stored separately
        $row['vo_id'] = $virtualOrganisation->vo_id;
        $row['vo_type'] = $virtualOrganisation->vo_type;
        return $row;
    }
}
