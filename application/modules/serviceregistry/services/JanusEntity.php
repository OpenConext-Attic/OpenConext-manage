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

class ServiceRegistry_Service_JanusEntity
{
    public function searchCountTypes(Surfnet_Search_Parameters $params)
    {
        $dao = new ServiceRegistry_Model_DbTable_JanusEntity();
        $searchParams = $params->getSearchParams();
        /*
         $qry = "SELECT COUNT(DISTINCT(entityid)) AS num, 'Identity Provider' as type FROM `service_registry`.`janus__entity` where type = 'saml20-idp'
         UNION SELECT COUNT(DISTINCT(entityid)) AS num, 'Service Provider' as type FROM `service_registry`.`janus__entity` where type = 'saml20-sp'";
         */
        $selectIdp = $dao->select();
        $selectIdp->from('janus__entity AS je1',
                         array("num" => "COUNT(*)",
                              'type' => new Zend_Db_Expr("'Identity Provider'"))
                        )
                  ->where('type = ?', 'saml20-idp')
                  ->where('revisionid = (SELECT MAX(je.revisionid) FROM janus__entity je WHERE je.eid = je1.eid)');

        if ($params->searchByDate()) {
            $selectIdp->where(
                $this->_getCountTypesWhereForDate(
                    'je1',
                    $searchParams['year'],
                    $searchParams['month']
                )
            );
        }

        $selectSp = $dao->select();
        $selectSp->from('janus__entity AS je1',
                        array("num" => "COUNT(*)",
                              'type' => new Zend_Db_Expr("'Service Provider'"))
                       )
                 ->where('type = ?', 'saml20-sp')
                 ->where('revisionid = (SELECT MAX(je.revisionid) FROM janus__entity je WHERE je.eid = je1.eid)');


        if ($params->searchByDate()) {
            $selectSp->where(
                $this->_getCountTypesWhereForDate(
                   'je1',
                    $searchParams['year'],
                    $searchParams['month']
                )
            );
        }

        $select = $dao->select()
                ->union(array(
                    $selectIdp,
                    $selectSp
                ));
        $rows = $dao->fetchAll($select)->toArray();

        return new Surfnet_Search_Results($params, $rows, 2);
    }

    /**
     * Get 'where' condition for janus__entities bases on given filter
     * strings
     *
     * @param Zend_Db Adapter
     * @param String  Table
     * @param array Field definitions
     * @param array Filter strings
     * @return array of sql where clauses
     */
    protected function _getSearchFiltersWhere($adapter, $table, $fields, $filters) {
        $queries = array();

        // search by filter
        foreach ($filters as $key => $filter) {
            if (!array_key_exists($key, $fields)) {
                continue; // unknown field
            }

            $field = $adapter->quoteIdentifier($key);

            $query = $adapter->quoteInto(
                $field . ' LIKE ?', '%' . $filter . '%'
            );

            $queries[$key] = $query;
        }

        return $queries;
    }

    /**
     * Get 'where' condition for janus__entities in a given month
     * in a given year.
     * The created/expiration fields are varchar() columns and
     * not dates, which is why we need the LEFT() hack.
     *
     * @param String  Table
     * @param Integer Year
     * @param Integer Month
     * @return String
     */
    protected function _getCountTypesWhereForDate($table, $year, $month) {
        $year = intval($year);
        $month = intval($month);

        return sprintf(
                    "((`%s`.`expiration` IS NULL) OR (LEFT(`%s`.`expiration`,7) <= '%04u-%02u'))",
                    $table,
                    $table,
                    $year,
                    $month
               )
               . sprintf(
                    " AND (LEFT(`%s`.`created`,7) <= '%04u-%02u')",
                    $table,
                    $year,
                    $month
               );
    }

    public function searchIdps(Surfnet_Search_Parameters $params)
    {
        return $this->_searchType('saml20-idp', $params);
    }

    public function searchSps(Surfnet_Search_Parameters $params)
    {
        return $this->_searchType('saml20-sp', $params);
    }

    protected function _searchType($type, Surfnet_Search_Parameters $params)
    {
        $searchParams = $params->getSearchParams();
        $dao = new ServiceRegistry_Model_DbTable_JanusEntity();

        $rev_fields = array(
            'eid' => 'eid',
            'maxrev' => 'max(revisionid)',
        );

        $rev_select = $dao->select()->from(
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
            'display_name' => "IFNULL(
    (SELECT `value` FROM `janus__metadata` `jm` WHERE `key`='name:en' AND jm.eid = ent.eid AND jm.revisionid = maxrev AND jm.value <> ''),
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
                        array('userid'=>'userid')
                      )
                ->where('ent.type= ?',$type);

        // apply search filters
        $whereQueries = $this->_getSearchFiltersWhere(
            $dao->getAdapter(), 'ent', $fields, $params->getSearchParams()
        );

        foreach ($whereQueries as $query) {
            $select->having($query);
        }

        // search by date
        if ($params->searchByDate()) {
            $select->where(
                $this->_getCountTypesWhereForDate(
                    'ent',
                    $searchParams['year'],
                    $searchParams['month']
                )
            );
        }

        if ($params->getLimit()) {
            $select->limit($params->getLimit(), $params->getOffset());
        }

        if ($params->getSortByField() != '') {
            $select->order($params->getSortByField() . ' ' . $params->getSortDirection());
        }
        else {
            $select->order(array('ent.state', 'display_name'));
        }
        $rows = $dao->fetchAll($select)->toArray();

        $row = $dao->fetchRow(
            $select->reset(Zend_Db_Select::LIMIT_COUNT)
                    ->reset(Zend_Db_Select::LIMIT_OFFSET)
                    ->columns(array('count'=>'COUNT(*)'))
        );

        if ($row instanceof Zend_Db_Table_Row) {
            $totalCount = $row->offsetGet('count');
        } else { // null, no rows
            $totalCount = 0;
        }

        return new Surfnet_Search_Results($params, $rows, $totalCount);
    }

    public function fetchByEntityId($entityId)
    {
        $mapper = new ServiceRegistry_Model_Mapper_JanusEntityMapper(new ServiceRegistry_Service_JanusEntity());
        return $mapper->fetchByEntityId($entityId);
    }

    public function getAllowedConnections($entityId)
    {
        $service = new ServiceRegistry_Service_JanusEntity();
        $fromEntity = $service->fetchByEntityId($entityId);

        $entities = array();
        // get all entities from other type
        if ($fromEntity['type'] === "saml20-idp") {
            $results = $service->searchSps(Surfnet_Search_Parameters::create());
            $entities = $results->getResults();
        } else {
            $results = $service->searchIdps(Surfnet_Search_Parameters::create());
            $entities = $results->getResults();
        }

        $entitiesResult = array();
        foreach ($entities as $entity) {
            if ($service->isConnectionAllowed($fromEntity, $entity) && $service->isConnectionAllowed($entity, $fromEntity)) {
                $entitiesResult[] = $entity;
            }
        }

        return $entitiesResult;
    }

    public function isConnectionAllowed($fromEntity, $toEntity)
    {
        $mapper = new ServiceRegistry_Model_Mapper_JanusEntityMapper(new ServiceRegistry_Service_JanusEntity());
        return $mapper->isConnectionAllowed($fromEntity, $toEntity);
    }
}