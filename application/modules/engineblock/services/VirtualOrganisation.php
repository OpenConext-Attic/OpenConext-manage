<?php
/**
 *
 */

class EngineBlock_Service_VirtualOrganisation
{

    public function listSearch(Surfnet_Search_Parameters $params)
    {
        return $this->_searchWhere($params);
    }

    protected function _searchWhere(Surfnet_Search_Parameters $params, $where = '')
    {
        // select VO record(s)
        $dao = new EngineBlock_Model_DbTable_VirtualOrganisation();

        $query = $dao->select()->from($dao);
        if (strlen(trim($where)) > 0) {
            $query->where($where);
        }
        if ($params->getLimit()) {
            $query->limit($params->getLimit(), $params->getOffset());
        }
        if ($params->getSortByField()) {
            $query->order('virtual_organisation.' . $params->getSortByField() . ' ' . $params->getSortDirection());
        }
        $voRecords = $dao->fetchAll($query);

        // get corresponding groups
        $groupRecords = array();
        foreach ($voRecords as $row) {
            /* @var $row Zend_Db_Table_Row */
            $groupRecords[$row->vo_id] = $row->findDependentRowset('EngineBlock_Model_DbTable_VirtualOrganisationGroup')->toArray();
            // remove FK's
            foreach ($groupRecords[$row['vo_id']] as &$groupRow) {
                unset($groupRow['vo_id']);
            }
        }
        unset($row);

        // get corresponding idps
        $idpRecords = array();
        foreach ($voRecords as $row) {
            /* @var $row Zend_Db_Table_Row */
            $idpRecords[$row->vo_id] = $row->findDependentRowset('EngineBlock_Model_DbTable_VirtualOrganisationIdp')->toArray();
            // remove FK's
            foreach ($idpRecords[$row['vo_id']] as &$idpRow) {
                unset($idpRow['vo_id']);
            }
        }
        unset($row);

        // get corresponding stem
        $stemRecords = array();
        foreach ($voRecords as $row) {
            if ($row->vo_type === 'STEM') {
                /* @var $config Zend_Config */
                $config = Zend_Registry::get('config');
                $stemPrefix = $config->engineBlock->vo->stemPrefix;
                $stemRecords[$row['vo_id']] = $stemPrefix . $row->vo_id;
            }
        }
        unset($row);

        // merge groups into VOs
        $voRecords = $voRecords->toArray();
        foreach ($voRecords as &$row) {
            $row['groups'] = $groupRecords[$row['vo_id']];
        }
        unset($row);

        // merge idps into VOs
        foreach ($voRecords as &$row) {
            $row['idps'] = $idpRecords[$row['vo_id']];
        }
        unset($row);

        // merge stems into VOs
        foreach ($voRecords as &$row) {
            if ($row['vo_type'] === 'STEM') {
                $row['stem'] = $stemRecords[$row['vo_id']];
            }
        }
        unset($row);

        $totalCount = $dao->fetchRow(
            $query->reset(Zend_Db_Select::LIMIT_COUNT)
                    ->reset(Zend_Db_Select::LIMIT_OFFSET)
                    ->columns(array('count' => 'COUNT(*)'))
        )->offsetGet('count');
        return new Surfnet_Search_Results($params, $voRecords, $totalCount);
    }

    public function fetchById($id)
    {
        $mapper = new EngineBlock_Model_Mapper_VirtualOrganisation(new EngineBlock_Model_DbTable_VirtualOrganisation());
        return $mapper->fetchById($id);
    }

    public function save($data, $overwrite = false)
    {
        if (isset($data['vo_id']) && !$overwrite) {
            $id = $data['vo_id'];
            $voService = new EngineBlock_Service_VirtualOrganisation();
            $vo = $voService->fetchById($id);
        }
        else {
            $vo = new EngineBlock_Model_VirtualOrganisation();
        }
        $vo->populate($data);

        $form = new EngineBlock_Form_VirtualOrganisation();
        if (!$form->isValid($vo->toArray())) {
            $formErrors = $form->getErrors();
            $modelErrors = array();
            foreach ($formErrors as $fieldName => $fieldErrors) {
                foreach ($fieldErrors as $fieldError) {
                    switch ($fieldError) {
                        case 'isEmpty':
                            $error = 'Field is obligatory, but no input given';
                            break;
                        default:
                            $error = $fieldError;
                    }

                    if (!isset($modelErrors[$fieldName])) {
                        $modelErrors[$fieldName] = array();
                    }
                    $modelErrors[$fieldName][] = $error;
                }
            }
            $vo->errors = $modelErrors;
            return $vo;
        }

        $mapper = new EngineBlock_Model_Mapper_VirtualOrganisation(new EngineBlock_Model_DbTable_VirtualOrganisation());
        $vo = $mapper->save($vo, $data['vo_id'] != $data['org_vo_id']);

        // if the PK changes (vo_id), it is saved as a new record, so the groups and idps must be updated and the original record should be deleted
        if (empty($vo->errors) && isset($data['org_vo_id']) && $vo->vo_id != $data['org_vo_id']) {
            // update group records
            $groupService = new EngineBlock_Service_VirtualOrganisationGroup();
            $groupService->updateVOId($data['org_vo_id'], $data['vo_id']);
            // update idp records
            $idpService = new EngineBlock_Service_VirtualOrganisationIdp();
            $idpService->updateVOId($data['org_vo_id'], $data['vo_id']);
            // delete old vo
            $this->delete($data['org_vo_id']);
        }

        return $vo;
    }

    public function delete($id)
    {
        if (strlen(trim($id)) > 0) {
            $dao = new EngineBlock_Model_DbTable_VirtualOrganisation();
            $result = $dao->delete("vo_id='$id'");
            // cascade delete groups
            if ($result) {
                $dao = new EngineBlock_Model_DbTable_VirtualOrganisationGroup();
                return $dao->delete("vo_id='$id'");
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}