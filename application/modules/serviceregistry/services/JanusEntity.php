<?php
/**
 *
 */

class ServiceRegistry_Service_JanusEntity
{
    public function searchCountTypes(Surfnet_Search_Parameters $params)
    {
        $dao = new ServiceRegistry_Model_DbTable_JanusEntity();
        /*
         $qry = "SELECT COUNT(DISTINCT(entityid)) AS num, 'Identity Provider' as type FROM `service_registry`.`janus__entity` where type = 'saml20-idp'
         UNION SELECT COUNT(DISTINCT(entityid)) AS num, 'Service Provider' as type FROM `service_registry`.`janus__entity` where type = 'saml20-sp'";
         */
        $selectIdp = $dao->select();
        $selectIdp->from($dao,
                         array("num" => "COUNT(DISTINCT(entityid))",
                              'type' => new Zend_Db_Expr("'Identity Provider'"))
                        )
                  ->where('type = ?', 'saml20-idp');
        $selectSp = $dao->select();
        $selectSp->from($dao,
                        array("num" => "COUNT(DISTINCT(entityid))",
                              'type' => new Zend_Db_Expr("'Service Provider'"))
                       )
                 ->where('type = ?', 'saml20-sp');

        $select = $dao->select()
                ->union(array(
                    $selectIdp,
                    $selectSp
                ));

        $rows = $dao->fetchAll($select)->toArray();

        return new Surfnet_Search_Results($params, $rows, 2);
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
            'user' => 'user'
        );

        $entityType = 'idp';
        if ($type==='saml20-sp') {
            $entityType = 'sp';
        }

        $select = $dao->select()->setIntegrityCheck(false)->from(array('ent' => 'janus__entity'))
                ->columns($fields)
                ->joinInner(
                             array('entgrp' => $rev_select),
                             'ent.eid = entgrp.eid and ent.revisionid = entgrp.maxrev'
                           )
                ->join(
                        array('jm' => 'janus__metadata'),
                        '(ent.eid=jm.eid AND jm.revisionid=entgrp.maxrev)',
                        array($entityType=>'value', 'key' =>'key')
                      )
                ->join(
                        array('ju' => 'janus__user'),
                        '(ent.user=ju.uid)',
                        array('userid'=>'userid')
                      )
                ->where('ent.type= ?',$type)
                ->where('jm.key=?', 'name:nl');

        if ($params->getLimit()) {
            $select->limit($params->getLimit(), $params->getOffset());
        }

        if ($params->getSortByField() != '') {
            $select->order($params->getSortByField() . ' ' . $params->getSortDirection());
        }

        $rows = $dao->fetchAll($select)->toArray();

        $totalCount = $dao->fetchRow(
            $select->reset(Zend_Db_Select::LIMIT_COUNT)
                    ->reset(Zend_Db_Select::LIMIT_OFFSET)
                    ->columns(array('count'=>'COUNT(*)'))
        )->offsetGet('count');

        return new Surfnet_Search_Results($params, $rows, $totalCount);
    }
}