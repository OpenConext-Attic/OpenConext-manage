<?php
/**
 *
 */

class EngineBlock_Model_Mapper_VirtualOrganisationIdp
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
     * @return EngineBlock_Model_VirtualOrganisationIdp
     */
    public function fetchById($vo_id, $idp_id)
    {
        $rowsFound = $this->_dao->find($vo_id, $idp_id);
        if ($rowsFound->count() < 1) {
            throw new Exception("Virtual Organisation Idp with key '$vo_id, $idp_id' not found");
        }
        if ($rowsFound->count() > 1) {
            throw new Exception("Multiple Virtual Organisation Idps with key '$vo_id, $idp_id' found?");
        }

        $row = $rowsFound->current();

        $virtualOrganisationIdp = new EngineBlock_Model_VirtualOrganisationIdp();
        $this->_mapRowToVirtualOrganisationIdp($row, $virtualOrganisationIdp);
        
        return $virtualOrganisationIdp;
    }

    /**
     *
     * @param EngineBlock_Model_VirtualOrganisationIdp $virtualOrganisationIdp
     */
    public function save(EngineBlock_Model_VirtualOrganisationIdp $virtualOrganisationIdp, $isNewRecord=false)
    {
        if (!$isNewRecord) {
            $row = $this->_dao->find($virtualOrganisationIdp->vo_id, $virtualOrganisationIdp->idp_id)->current();
        }
        else {
            $row = $this->_dao->createRow();
        }

        $uniqueSelect = $this->_dao->select()->where('vo_id = ? AND idp_id = ?', $virtualOrganisationIdp->vo_id, $virtualOrganisationIdp->idp_id);
        $duplicates = $this->_dao->fetchAll($uniqueSelect)->toArray();
        
        if (empty($duplicates)) {
            $row = $this->_mapVirtualOrganisationIdpToRow($virtualOrganisationIdp, $row);
            $row->save();
        }
        else {
            $virtualOrganisationIdp->errors['url'][] = "A Virtual Organisation Idp with this id already exists";
        }

        return $virtualOrganisationIdp;
    }

    protected function _mapRowToVirtualOrganisationIdp(Zend_Db_Table_Row_Abstract $row, EngineBlock_Model_VirtualOrganisationIdp $virtualOrganisationIdp)
    {
        $virtualOrganisationIdp->vo_id         = $row['vo_id'];
        $virtualOrganisationIdp->idp_id      = $row['idp_id'];
        return $virtualOrganisationIdp;
    }

    protected function _mapVirtualOrganisationIdpToRow(EngineBlock_Model_VirtualOrganisationIdp $virtualOrganisationIdp, Zend_Db_Table_Row_Abstract $row)
    {
        $row['vo_id']                  = $virtualOrganisationIdp->vo_id;
        $row['idp_id']               = $virtualOrganisationIdp->idp_id;
        return $row;
    }
}
