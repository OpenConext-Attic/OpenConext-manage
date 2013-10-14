<?php
/**
 * SURFconext Manage
 *
 * LICENSE
 *
 * Copyright 2011 SURFnet bv, The Netherlands
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 *
 * @category  SURFconext Manage
 * @package
 * @copyright Copyright © 2010-2011 SURFnet bv, The Netherlands (http://www.surfnet.nl)
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

/**
 * Janus Entity datamapper
 *
 * @author marc
 */
class ServiceRegistry_Model_Mapper_JanusEntityMapper
{
    /**
     *
     * @todo Make this actually do something.
     * @param Model_Abstract $model
     */
    public function save($model)
    {

    }

    /**
     * @todo Make this actually do something.
     */
    public function find($id, $model = null)
    {

    }

    /**
     * @todo Make this actually do something.
     */
    protected function createObjectArray(Zend_Db_Table_Rowset_Abstract $rowSet)
    {
    }

    /**
     * Gets the available Idps
     *
     * @param String $order     Column to order on.
     * @param String $dir
     * @param Integer $limit
     * @param Integer $offset
     * @param Boolean $countOnly Return only the number of rows instead of the
     *                           full dataset.
     *
     * @return Array|Integer Array with gagdet usage data or row count.
     */
    public function fetchAvailableType($type = 'saml20-idp', $order = 'title', $dir = 'asc', $limit = null, $offset = 0, $countOnly = false)
    {
        $db = $this->_dao->getAdapter();

        $rev_select = $db->select();

        $rev_fields = array(
            'eid' => 'eid',
            'maxrev' => 'max(revisionid)',
        );

        $rev_select->from(
            array('janus__entity'),
            $rev_fields
        )
            ->group('eid');

        $fields = array(
            'entityid' => 'entityid',
            'state' => 'state',
            'metadataurl' => 'metadataurl',
            'created' => 'created',
            'user' => 'user'
        );

        $entityType = 'idp';
        if ($type === 'saml20-sp') {
            $entityType = 'sp';
        }

        $select = $db->select();
        $select->from(
            array('ent' => 'janus__entity'),
            $fields
        )
            ->joinInner(
                array('entgrp' => $rev_select),
                'ent.eid = entgrp.eid and ent.revisionid = entgrp.maxrev'
            )
            ->join(
                array('jm' => 'janus__metadata'),
                '(ent.eid=jm.eid AND jm.revisionid=entgrp.maxrev)',
                array($entityType => 'value', 'key' => 'key')
            )
            ->join(
                array('ju' => 'janus__user'),
                '(ent.user=ju.uid)',
                array('userid' => 'userid')
            )
            ->where('ent.type= ?', $type)
            ->where('jm.key=?', 'name:nl');

        if (isset($limit)) {
            $select->limit($limit, $offset);
        }

        if ($order != '' && !$countOnly) {
            $select->order($order . ' ' . $dir);
        }

        $rows = $db->fetchAll($select);

        if ($countOnly) {
            return count($rows);
        }
        $result = array();
        foreach ($rows as $row) {
            $result[] = array(
                'valid' => $row['entityid'],
                'allowedConnections' => $row['entityid'],
                $entityType => $row[$entityType],
                'state' => $row['state'],
                'metadataurl' => $row['metadataurl'],
                'created' => $row['created'],
                'userid' => $row['userid'],
            );
        }
        return $result;
    }

    /**
     * Gets the available Idps
     *
     * @param String $order     Column to order on.
     * @param String $dir
     * @param Integer $limit
     * @param Integer $offset
     * @param Boolean $countOnly Return only the number of rows instead of the
     *                           full dataset.
     *
     * @return Array|Integer Array with gagdet usage data or row count.
     */
    public function fetchIdpAndSpCount($order = 'title', $dir = 'asc', $limit = null, $offset = 0, $countOnly = false)
    {
        /*
         $qry = "SELECT COUNT(DISTINCT(entityid)) AS num, 'Identity Provider' as type FROM `service_registry`.`janus__entity` where type = 'saml20-idp'
         UNION SELECT COUNT(DISTINCT(entityid)) AS num, 'Service Provider' as type FROM `service_registry`.`janus__entity` where type = 'saml20-sp'";
         */
        $selectIdp = $this->_dao->select();
        $selectIdp->from($this->_dao,
            array("num" => "COUNT(DISTINCT(entityid))",
                'type' => new Zend_Db_Expr("'Identity Provider'"))
        )
            ->where('type = ?', 'saml20-idp');
        $selectSp = $this->_dao->select();
        $selectSp->from($this->_dao,
            array("num" => "COUNT(DISTINCT(entityid))",
                'type' => new Zend_Db_Expr("'Service Provider'"))
        )
            ->where('type = ?', 'saml20-sp');

        $select = $this->_dao->select()
            ->union(array(
                $selectIdp,
                $selectSp
            ));

        $rows = $this->_dao->fetchAll(
            $select
        );

        $result = array();
        foreach ($rows as $row) {
            $result[] = array(
                'num' => $row['num'],
                'type' => $row['type']
            );
        }
        return $result;
    }

    public function fetchByEntityId($entityId)
    {
        $dao = new ServiceRegistry_Model_DbTable_JanusEntity();

        $rev_fields = array(
            'eid' => 'eid',
            'maxrev' => 'max(revisionid)',
        );

        $rev_select = $dao->select()
            ->from(
                array('janus__entity'),
                $rev_fields
            )
            ->group('eid');

        $fields = array(
            'entityid' => 'entityid',
            'state' => 'state',
            'metadataurl' => 'metadataurl',
            'created' => 'created',
            'user' => 'user',
            'allowedall' => 'allowedall',
            'display_name' => "IFNULL(
    (SELECT `value` FROM `janus__metadata` `jm` WHERE `key`='name:en' AND jm.eid = ent.eid AND jm.revisionid = maxrev),
    ent.entityid
    )",
        );

        $select = $dao->select()
            ->setIntegrityCheck(false)
            ->from(array('ent' => 'janus__entity'))
            ->columns($fields)
            ->joinInner(
                array('entgrp' => $rev_select),
                'ent.eid = entgrp.eid and ent.revisionid = entgrp.maxrev'
            )
            ->join(
                array('ju' => 'janus__user'),
                '(ent.user=ju.uid)',
                array('userid' => 'userid')
            )
            ->where('ent.entityid= ?', $entityId);

        $row = $dao->fetchRow($select);
        if (!$row) {
            throw new Exception("No Entity found for EntityID '$entityId'");
        }
        return $row->toArray();
    }

    public function fetchByEId($eid)
    {
        $dao = new ServiceRegistry_Model_DbTable_JanusEntity();

        $rev_fields = array(
            'eid' => 'eid',
            'maxrev' => 'max(revisionid)',
        );

        $rev_select = $dao->select()
            ->from(
                array('janus__entity'),
                $rev_fields
            )
            ->group('eid');

        $fields = array(
            'entityid' => 'entityid',
            'state' => 'state',
            'metadataurl' => 'metadataurl',
            'created' => 'created',
            'user' => 'user',
            'allowedall' => 'allowedall',
            'display_name' => "IFNULL(
                                    (SELECT `value` FROM `janus__metadata` `jm` WHERE `key`='name:en' AND jm.eid = ent.eid AND jm.revisionid = maxrev),
                                    ent.entityid
                                )",
        );

        $select = $dao->select()
            ->setIntegrityCheck(false)
            ->from(array('ent' => 'janus__entity'))
            ->columns($fields)
            ->joinInner(
                array('entgrp' => $rev_select),
                'ent.eid = entgrp.eid and ent.revisionid = entgrp.maxrev'
            )
            ->join(
                array('ju' => 'janus__user'),
                '(ent.user=ju.uid)',
                array('userid' => 'userid')
            )
            ->where('ent.eid= ?', $eid);

        $row = $dao->fetchRow($select);
        if (!$row) {
            throw new Exception("No Entity found for eid '$eid'");
        }
        return $row->toArray();
    }

    public function isConnectionAllowed(array $fromEntity, array $toEntity)
    {
        if ($fromEntity['allowedall'] === 'yes') {
            return true;
        }

        $allowedConnections = $this->_fetchAllowedEntities($fromEntity['eid']);
        if (count($allowedConnections) && array_key_exists($toEntity['eid'], $allowedConnections)) {
            return true;
        }

        $blockedConnections = $this->_fetchBlockedEntities($fromEntity['eid']);
        if (count($blockedConnections) && !array_key_exists($toEntity['eid'], $blockedConnections)) {
            return true;
        }

        return false;
    }

    protected function _fetchBlockedEntities($eid)
    {
        return $this->_fetchEntities($eid, 'janus__blockedEntity');
    }

    protected function _fetchAllowedEntities($eid)
    {
        return $this->_fetchEntities($eid, 'janus__allowedEntity');
    }

    protected function _fetchEntities($eid, $table)
    {
        $dao = new ServiceRegistry_Model_DbTable_JanusEntity();

        $rev_fields = array(
            'eid' => 'eid',
            'maxrev' => 'max(revisionid)',
        );

        $rev_select = $dao->select()->from(
            array('janus__entity'),
            $rev_fields)
            ->where('eid = ?', $eid)
            ->group('eid');

        $fields = array(
            'eid' => 'eid',
            'revisionid' => 'revisionid',
            'remoteeid' => 'remoteeid',
            'created' => 'created',
            'ip' => 'ip'
        );

        $rev_id = $dao->fetchRow($rev_select)->toArray();
        $select = $dao->select()->setIntegrityCheck(false)->from(array('ent' => $table))
            ->columns($fields)
            ->where('ent.eid = ?', $eid)
            ->where('ent.revisionid = ?', $rev_id['maxrev']);

        $rows = $dao->fetchAll($select)->toArray();

        $result = array();
        foreach ($rows as $value) {
            $result[$value['remoteeid']] = $value;
        }
        return $result;
    }

}